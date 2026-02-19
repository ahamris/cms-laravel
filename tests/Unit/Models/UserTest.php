<?php

use App\Helpers\Variable;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => Variable::ROLE_ADMIN]);
    Role::firstOrCreate(['name' => Variable::ROLE_USER]);
});

test('user isAdmin returns true when user has admin role', function () {
    $user = User::create([
        'name' => 'Admin',
        'last_name' => 'User',
        'email' => 'admin-role-test@example.com',
        'password' => Hash::make('password'),
        'email_verified_at' => now(),
    ]);
    $user->assignRole(Variable::ROLE_ADMIN);

    expect($user->isAdmin())->toBeTrue();
});

test('user isAdmin returns false when user does not have admin role', function () {
    $user = User::create([
        'name' => 'Customer',
        'last_name' => 'User',
        'email' => 'customer-role-test@example.com',
        'password' => Hash::make('password'),
        'email_verified_at' => now(),
    ]);
    $user->assignRole(Variable::ROLE_USER);

    expect($user->isAdmin())->toBeFalse();
});

test('user hasRole returns true for assigned role', function () {
    $user = User::create([
        'name' => 'Test',
        'last_name' => 'User',
        'email' => 'hasrole-test@example.com',
        'password' => Hash::make('password'),
        'email_verified_at' => now(),
    ]);
    $user->assignRole(Variable::ROLE_ADMIN);

    expect($user->hasRole(Variable::ROLE_ADMIN))->toBeTrue();
});
