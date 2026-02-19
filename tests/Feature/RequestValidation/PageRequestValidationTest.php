<?php

use App\Helpers\Variable;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => Variable::ROLE_ADMIN]);
    $this->admin = User::create([
        'name' => 'Admin',
        'last_name' => 'Validator',
        'email' => 'admin-page-validation@example.com',
        'password' => Hash::make('password'),
        'email_verified_at' => now(),
    ]);
    $this->admin->assignRole(Variable::ROLE_ADMIN);
});

test('page store fails when required fields are missing', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.content.page.store'), []);

    $response->assertSessionHasErrors(['title', 'slug', 'short_body', 'long_body']);
});

test('page store fails when page_type is invalid', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.content.page.store'), [
        'title' => 'Test',
        'slug' => 'test-page',
        'page_type' => 'invalid-type',
        'short_body' => 'At least ten chars here.',
        'long_body' => 'At least ten characters in long body.',
        'is_active' => true,
    ]);

    $response->assertSessionHasErrors(['page_type']);
});
