<?php

namespace Database\Seeders;

use App\Helpers\Variable;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions from Variable::$fullPermissions
        foreach (array_keys(Variable::$fullPermissions) as $permissionName) {
            Permission::firstOrCreate(
                ['name' => $permissionName, 'guard_name' => Variable::GUARD_NAME]
            );
        }

        // Assign permissions to roles based on Variable::$fullPermissions
        foreach (Variable::$fullPermissions as $permissionName => $allowedRoles) {
            $permission = Permission::where('name', $permissionName)
                ->where('guard_name', Variable::GUARD_NAME)
                ->first();

            if ($permission) {
                foreach ($allowedRoles as $roleName) {
                    $role = Role::where('name', $roleName)
                        ->where('guard_name', Variable::GUARD_NAME)
                        ->first();

                    if ($role && !$role->hasPermissionTo($permission)) {
                        $role->givePermissionTo($permission);
                    }
                }
            }
        }
    }
}
