<?php

use App\Helpers\Variable;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

test('guest is redirected to login when accessing admin index', function () {
    $response = $this->get(route('admin.index'));

    $response->assertRedirect(route('admin.login'));
});

test('non-admin user cannot access admin index', function () {
    Role::firstOrCreate(['name' => Variable::ROLE_USER]);
    $user = User::create([
        'name' => 'Customer',
        'last_name' => 'User',
        'email' => 'customer-auth@example.com',
        'password' => Hash::make('password'),
        'email_verified_at' => now(),
    ]);
    $user->assignRole(Variable::ROLE_USER);

    $response = $this->actingAs($user)->get(route('admin.index'));

    $response->assertStatus(403);
});

test('admin user can access admin index', function () {
    Role::firstOrCreate(['name' => Variable::ROLE_ADMIN]);
    $user = User::create([
        'name' => 'Admin',
        'last_name' => 'User',
        'email' => 'admin-auth@example.com',
        'password' => Hash::make('password'),
        'email_verified_at' => now(),
    ]);
    $user->assignRole(Variable::ROLE_ADMIN);

    $response = $this->actingAs($user)->get(route('admin.index'));

    $response->assertStatus(200);
});
