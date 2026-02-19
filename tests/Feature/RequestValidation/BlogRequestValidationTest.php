<?php

use App\Helpers\Variable;
use App\Models\BlogCategory;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => Variable::ROLE_ADMIN]);
    $this->admin = User::create([
        'name' => 'Admin',
        'last_name' => 'Blog',
        'email' => 'admin-blog-validation@example.com',
        'password' => Hash::make('password'),
        'email_verified_at' => now(),
    ]);
    $this->admin->assignRole(Variable::ROLE_ADMIN);
});

test('blog store fails when required fields are missing', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.content.blog.store'), []);

    $response->assertSessionHasErrors(['blog_category_id', 'author_id', 'title', 'slug', 'short_body', 'long_body']);
});

test('blog store fails when short_body is too short', function () {
    $category = BlogCategory::create(['name' => 'Test Cat', 'slug' => 'test-cat', 'is_active' => true]);

    $response = $this->actingAs($this->admin)->post(route('admin.content.blog.store'), [
        'blog_category_id' => $category->id,
        'author_id' => $this->admin->id,
        'title' => 'Short Body Test',
        'slug' => 'short-body-test',
        'short_body' => 'tiny',
        'long_body' => 'At least twenty characters here for long body.',
    ]);

    $response->assertSessionHasErrors(['short_body']);
});
