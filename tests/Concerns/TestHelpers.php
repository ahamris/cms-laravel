<?php

namespace Tests\Concerns;

use App\Helpers\Variable;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

trait TestHelpers
{
    /**
     * Create an admin user, assign role, and act as that user.
     */
    protected function actingAsAdmin(): self
    {
        Role::firstOrCreate(['name' => Variable::ROLE_ADMIN]);
        $user = User::create([
            'name' => 'Admin',
            'last_name' => 'Test',
            'email' => 'admin-test-'.uniqid().'@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $user->assignRole(Variable::ROLE_ADMIN);

        return $this->actingAs($user);
    }

    /**
     * Create a blog category and a blog belonging to it.
     *
     * @return array{0: \App\Models\Blog, 1: \App\Models\BlogCategory}
     */
    protected function createBlogWithCategory(): array
    {
        $category = BlogCategory::create([
            'name' => 'Test Category '.uniqid(),
            'slug' => 'test-category-'.uniqid(),
            'is_active' => true,
        ]);
        $blog = Blog::create([
            'blog_category_id' => $category->id,
            'title' => 'Test Blog '.uniqid(),
            'slug' => 'test-blog-'.uniqid(),
            'short_body' => 'Short body content.',
            'long_body' => 'Long body content for the blog.',
            'is_active' => true,
        ]);

        return [$blog, $category];
    }
}
