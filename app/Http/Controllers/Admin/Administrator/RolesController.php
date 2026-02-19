<?php

namespace App\Http\Controllers\Admin\Administrator;

use App\Http\Controllers\Admin\AdminBaseController;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends AdminBaseController
{
    public function index(): View
    {
        // Eager load permissions and count of users for each role
        $roles = Role::with('permissions')
            ->withCount('users')
            ->paginate(25);

        return view('admin.administrator.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $permissions = Permission::all();

        return view('admin.administrator.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created role in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        try {
            // Create the role
            $role = Role::create(['name' => $validated['name']]);

            // Sync permissions if any
            if (isset($validated['permissions'])) {
                $role->syncPermissions($validated['permissions']);
            }

            return redirect()
                ->route('admin.administrator.roles.index')
                ->with('success', 'Role created successfully.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error creating role: '.$e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified role.
     *
     * @return \Illuminate\View\View
     */
    public function edit(Role $role)
    {
        $permissions = Permission::all();

        return view('admin.administrator.roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified role in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,'.$role->id,
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        try {
            // Prevent updating the super-admin role name
            if ($role->name === 'super-admin' && $validated['name'] !== 'super-admin') {
                return back()
                    ->withInput()
                    ->with('error', 'Cannot rename the super-admin role.');
            }

            // Update the role
            $role->update(['name' => $validated['name']]);

            // Sync permissions if any (but don't allow removing all permissions from super-admin)
            if (isset($validated['permissions'])) {
                if ($role->name === 'super-admin') {
                    return back()
                        ->withInput()
                        ->with('error', 'Cannot modify permissions for super-admin role.');
                }
                $role->syncPermissions($validated['permissions']);
            } else {
                // If no permissions are selected, remove all permissions (except for super-admin)
                if ($role->name !== 'super-admin') {
                    $role->syncPermissions([]);
                }
            }

            return redirect()
                ->route('admin.administrator.roles.index')
                ->with('success', 'Role updated successfully.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error updating role: '.$e->getMessage());
        }
    }

    /**
     * Remove the specified role from storage.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Role $role)
    {
        try {
            // Prevent deletion of super-admin role
            if ($role->name === 'super-admin') {
                return back()
                    ->with('error', 'Cannot delete the super-admin role.');
            }

            // Prevent users from deleting their own role
            if (auth()->user()->roles->pluck('name')->contains($role->name)) {
                return back()
                    ->with('error', 'You cannot delete your own role.');
            }

            // Check if role is assigned to any users
            if ($role->users()->count() > 0) {
                return back()
                    ->with('error', 'Cannot delete role. There are users assigned to this role.');
            }

            $role->delete();

            return redirect()
                ->route('admin.administrator.roles.index')
                ->with('success', 'Role deleted successfully.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Error deleting role: '.$e->getMessage());
        }
    }
}
