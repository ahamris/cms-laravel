<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    /**
     * Display a listing of permissions.
     */
    public function index(): View
    {
        return view('admin.permissions.index');
    }

    /**
     * Show the form for creating a new permission.
     */
    public function create(): View
    {
        $roles = Role::all();

        return view('admin.permissions.create', compact('roles'));
    }

    /**
     * Store a newly created permission.
     */
    public function store(): RedirectResponse
    {
        $validated = request()->validate([
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name'],
            'roles' => ['array'],
            'roles.*' => ['exists:roles,name'],
        ]);

        $permission = Permission::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
        ]);

        if (isset($validated['roles'])) {
            $permission->syncRoles($validated['roles']);
        }

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', 'Permission created successfully.');
    }

    /**
     * Show the form for editing the specified permission.
     */
    public function edit(Permission $permission): View
    {
        $roles = Role::all();

        return view('admin.permissions.edit', compact('permission', 'roles'));
    }

    /**
     * Update the specified permission.
     */
    public function update(Permission $permission): RedirectResponse
    {
        $validated = request()->validate([
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name,' . $permission->id],
            'roles' => ['array'],
            'roles.*' => ['exists:roles,name'],
        ]);

        $permission->update(['name' => $validated['name']]);

        if (isset($validated['roles'])) {
            $permission->syncRoles($validated['roles']);
        } else {
            $permission->syncRoles([]);
        }

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', 'Permission updated successfully.');
    }

    /**
     * Remove the specified permission.
     */
    public function destroy(Permission $permission): RedirectResponse
    {
        $permission->delete();

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', 'Permission deleted successfully.');
    }
}
