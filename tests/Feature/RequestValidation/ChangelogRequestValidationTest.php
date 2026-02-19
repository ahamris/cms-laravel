<?php

use App\Helpers\Variable;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => Variable::ROLE_ADMIN]);
    $this->admin = User::create([
        'name' => 'Admin',
        'last_name' => 'Changelog',
        'email' => 'admin-changelog-validation@example.com',
        'password' => Hash::make('password'),
        'email_verified_at' => now(),
    ]);
    $this->admin->assignRole(Variable::ROLE_ADMIN);
});

test('changelog store fails when required fields are missing', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.content.changelog.store'), []);

    $response->assertSessionHasErrors(['title', 'description', 'date', 'status']);
});

test('changelog store fails when status is invalid', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.content.changelog.store'), [
        'title' => 'Test',
        'description' => 'A valid description here.',
        'date' => now()->format('Y-m-d'),
        'status' => 'invalid-status',
    ]);

    $response->assertSessionHasErrors(['status']);
});
