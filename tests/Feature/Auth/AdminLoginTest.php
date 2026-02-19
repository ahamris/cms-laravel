<?php

use App\Helpers\Variable;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => Variable::ROLE_ADMIN]);
});

test('admin login page is accessible by guest', function () {
    $response = $this->get(route('admin.login'));

    $response->assertStatus(200);
});

test('admin can login with valid credentials and is redirected to admin', function () {
    $user = User::create([
        'name' => 'Admin',
        'last_name' => 'User',
        'email' => 'admin-login-test@example.com',
        'password' => Hash::make('password'),
        'email_verified_at' => now(),
    ]);
    $user->assignRole(Variable::ROLE_ADMIN);

    $response = $this->post(route('admin.login.post'), [
        'email' => 'admin-login-test@example.com',
        'password' => 'password',
    ]);

    $response->assertRedirect('/admin');
    $this->assertAuthenticatedAs($user);
});

test('admin login fails with invalid credentials', function () {
    $user = User::create([
        'name' => 'Admin',
        'last_name' => 'User',
        'email' => 'admin-fail@example.com',
        'password' => Hash::make('password'),
        'email_verified_at' => now(),
    ]);
    $user->assignRole(Variable::ROLE_ADMIN);

    $response = $this->post(route('admin.login.post'), [
        'email' => 'admin-fail@example.com',
        'password' => 'wrong-password',
    ]);

    $response->assertRedirect();
    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('admin login requires email and password', function () {
    $response = $this->post(route('admin.login.post'), []);

    $response->assertSessionHasErrors(['email', 'password']);
    $this->assertGuest();
});

test('admin can logout', function () {
    $user = User::create([
        'name' => 'Admin',
        'last_name' => 'Logout',
        'email' => 'admin-logout@example.com',
        'password' => Hash::make('password'),
        'email_verified_at' => now(),
    ]);
    $user->assignRole(Variable::ROLE_ADMIN);

    $response = $this->actingAs($user)->post(route('admin.logout'));

    $response->assertRedirect();
    $this->assertGuest();
});
