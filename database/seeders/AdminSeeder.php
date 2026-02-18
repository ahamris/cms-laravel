<?php

namespace Database\Seeders;

use App\Helpers\AuthHelper;
use App\Helpers\Variable;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles first
        foreach (Variable::$fullRoles as $roleName) {
            Role::firstOrCreate(
                ['name' => $roleName, 'guard_name' => Variable::GUARD_NAME]
            );
        }

        // Create default accounts from Variable::DEFAULT_ACCOUNTS
        foreach (Variable::DEFAULT_ACCOUNTS as $account) {
            [$firstName, $lastName, $email, $password, $roleName] = $account;
            $name = trim($firstName . ' ' . $lastName);

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => Hash::make($password),
                    'is_active' => true,
                ]
            );

            // Assign role
            $role = Role::where('name', $roleName)
                ->where('guard_name', Variable::GUARD_NAME)
                ->first();

            if ($role && !$user->hasRole($roleName)) {
                $user->assignRole($roleName);
            }
        }
    }
}
