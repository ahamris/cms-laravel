<?php

namespace App\Http\Controllers\Admin\Administrator;

use App\Helpers\Variable;
use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Requests\UserCrudRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserCrudController extends AdminBaseController
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (! Variable::hasPermission('user_access')) {
                abort(JsonResponse::HTTP_FORBIDDEN);
            }

            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $showNonAdmins = request()->get('show_non_admins', false);
        
        $query = User::with('roles', 'permissions');

        if ($showNonAdmins) {
            // Show users without admin role
            $query->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'admin');
            });
        } else {
            // Only show users with admin role
            $query->whereHas('roles', function ($q) {
                $q->where('name', 'admin');
            });
        }

        // Role filter
        if (request()->has('role') && ! empty(request('role'))) {
            $query->whereHas('roles', function ($q) {
                $q->where('id', request('role'));
            });
        }

        // Permission filter
        if (request()->has('permissions') && ! empty(request('permissions'))) {
            $query->whereHas('permissions', function ($q) {
                $q->where('id', request('permissions'));
            });
        }

        $users = $query->latest()->paginate(10);
        $roles = Role::all();
        $permissions = Permission::all();

        if (request()->ajax()) {
            return response()->json([
                'html' => view('admin.administrator.users.partials.users_table', [
                    'users' => $users,
                    'roles' => $roles,
                    'permissions' => $permissions,
                    'showNonAdmins' => $showNonAdmins,
                ])->render(),
                'pagination' => (string) $users->withQueryString()->links('pagination::bootstrap-4'),
            ]);
        }

        return view('admin.administrator.users.index', compact('users', 'roles', 'permissions', 'showNonAdmins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        if (! Variable::hasPermission('user_create')) {
            abort(JsonResponse::HTTP_FORBIDDEN);
        }
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();

        // Create role-permission mapping for JavaScript
        $rolePermissions = $roles->mapWithKeys(function ($role) {
            return [$role->id => $role->permissions->pluck('id')->toArray()];
        });

        return view('admin.administrator.users.create', compact('roles', 'permissions', 'rolePermissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserCrudRequest $request): RedirectResponse
    {
        if (! Variable::hasPermission('user_create')) {
            abort(JsonResponse::HTTP_FORBIDDEN);
        }
        $validated = $request->validated();

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create([
            'name' => $validated['name'],
            'last_name' => $validated['last_name'] ?? null,
            'email' => $validated['email'],
            'secondary_email' => $validated['secondary_email'] ?? null,
            'password' => Hash::make($validated['password']),
            'avatar' => $validated['avatar'] ?? null,
        ]);

        // Assign roles
        if (isset($validated['roles'])) {
            $roleIds = is_array($validated['roles']) ? $validated['roles'] : [$validated['roles']];
            $roles = Role::whereIn('id', $roleIds)->pluck('name')->toArray();
            $user->syncRoles($roles);
        }

        // Assign direct permissions if any
        if (isset($validated['permissions'])) {
            $permissionIds = is_array($validated['permissions']) ? $validated['permissions'] : [$validated['permissions']];
            $permissions = Permission::whereIn('id', $permissionIds)->pluck('name')->toArray();
            $user->syncPermissions($permissions);
        }

        return redirect()
            ->route('admin.administrator.users.index')
            ->with('success', 'User created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): View
    {
        $user->load('roles', 'permissions');

        return view('admin.administrator.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        if (! Variable::hasPermission('user_edit')) {
            abort(JsonResponse::HTTP_FORBIDDEN);
        }
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        $user->load('permissions');

        // Create role-permission mapping for JavaScript
        $rolePermissions = $roles->mapWithKeys(function ($role) {
            return [$role->id => $role->permissions->pluck('id')->toArray()];
        });

        return view('admin.administrator.users.edit', compact('user', 'roles', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserCrudRequest $request, User $user): RedirectResponse
    {
        if (! Variable::hasPermission('user_edit')) {
            abort(JsonResponse::HTTP_FORBIDDEN);
        }
        $validated = $request->validated();
        $updateData = [
            'name' => $validated['name'],
            'last_name' => $validated['last_name'] ?? null,
            'email' => $validated['email'],
            'secondary_email' => $validated['secondary_email'] ?? null,
        ];

        // Handle avatar deletion
        if ($request->has('remove_image') && $request->input('remove_image') == '1') {
            // Delete old avatar from storage if exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            // Set avatar to null in database
            $updateData['avatar'] = null;
        }
        // Handle avatar upload
        elseif ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $updateData['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        // Only update password if provided
        if (! empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        // Get role IDs from the request
        $roleIds = $request->input('roles', []);
        $roles = Role::whereIn('id', $roleIds)->pluck('name')->toArray();

        // Get permission IDs from the request
        $permissionIds = $request->input('permissions', []);
        $permissions = Permission::whereIn('id', $permissionIds)->pluck('name')->toArray();

        // Sync roles and permissions
        $user->syncRoles($roles);
        $user->syncPermissions($permissions);

        return redirect()
            ->route('admin.administrator.users.edit', $user)
            ->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        if (! Variable::hasPermission('user_delete')) {
            abort(JsonResponse::HTTP_FORBIDDEN);
        }
        
        // Prevent deleting user ID 1 (super admin)
        if ($user->id === 1) {
            return back()->with('error', 'Cannot delete the super admin account (ID: 1)');
        }
        
        // Prevent deleting yourself
        if (auth()->id() === $user->id) {
            return back()->with('error', 'You cannot delete your own account');
        }

        $user->delete();

        return redirect()
            ->route('admin.administrator.users.index')
            ->with('success', 'User deleted successfully');
    }

    /**
     * Search for non-admin users by email
     */
    public function searchNonAdmins(Request $request): JsonResponse
    {
        $search = $request->get('search', '');
        
        $users = User::whereDoesntHave('roles', function ($q) {
                $q->where('name', 'admin');
            })
            ->where('email', 'like', '%' . $search . '%')
            ->select('id', 'name', 'email')
            ->limit(10)
            ->get();

        return response()->json([
            'users' => $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ];
            })
        ]);
    }

    /**
     * Assign admin role to selected users
     */
    public function assignAdminRole(Request $request): RedirectResponse
    {
        if (! Variable::hasPermission('user_edit')) {
            abort(JsonResponse::HTTP_FORBIDDEN);
        }

        $userIds = $request->input('user_ids', []);
        
        if (empty($userIds)) {
            return back()->with('error', 'No users selected');
        }

        $adminRole = Role::where('name', 'admin')->first();
        
        if (!$adminRole) {
            return back()->with('error', 'Admin role not found');
        }

        $users = User::whereIn('id', $userIds)->get();
        $assignedCount = 0;

        foreach ($users as $user) {
            if (!$user->hasRole('admin')) {
                $user->assignRole('admin');
                $assignedCount++;
            }
        }

        return back()->with('success', "Admin role assigned to {$assignedCount} user(s)");
    }
}
