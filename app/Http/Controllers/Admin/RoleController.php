<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of roles.
     */
    public function index(): View
    {
        return view('admin.roles.index');
    }

    /**
     * Show the form for creating a new role.
     */
    public function create(): View
    {
        $permissions = Permission::all()->groupBy(function ($permission) {
            $parts = explode('_', $permission->name);
            return $parts[0] ?? 'other';
        });

        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created role.
     */
    public function store(): RedirectResponse
    {
        $validated = request()->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'permissions' => ['array'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        $role = Role::create(['name' => $validated['name'], 'guard_name' => 'web']);

        if (isset($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role): View
    {
        $permissions = Permission::all()->groupBy(function ($permission) {
            $parts = explode('_', $permission->name);
            return $parts[0] ?? 'other';
        });

        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified role.
     */
    public function update(Role $role): RedirectResponse
    {
        $validated = request()->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name,' . $role->id],
            'permissions' => ['array'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        // Prevent updating admin role name
        if ($role->name === 'admin' && $validated['name'] !== 'admin') {
            return back()
                ->withInput()
                ->with('error', 'Cannot rename the admin role.');
        }

        $role->update(['name' => $validated['name']]);

        if (isset($validated['permissions'])) {
            if ($role->name === 'admin') {
                return back()
                    ->withInput()
                    ->with('error', 'Cannot modify permissions for admin role.');
            }
            $role->syncPermissions($validated['permissions']);
        } else {
            if ($role->name !== 'admin') {
                $role->syncPermissions([]);
            }
        }

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified role.
     */
    public function destroy(Role $role): RedirectResponse
    {
        // Prevent deletion of admin role
        if ($role->name === 'admin') {
            return back()
                ->with('error', 'Cannot delete the admin role.');
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
            ->route('admin.roles.index')
            ->with('success', 'Role deleted successfully.');
    }
}
