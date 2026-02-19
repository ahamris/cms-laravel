<?php

namespace Database\Factories;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blog>
 */
class BlogFactory extends Factory
{
    protected $model = Blog::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(3);
        return [
            'blog_category_id' => BlogCategory::factory(),
            'author_id' => User::factory(),
            'title' => $title,
            'slug' => \Illuminate\Support\Str::slug($title) . '-' . fake()->unique()->lexify('????'),
            'short_body' => fake()->paragraph(),
            'long_body' => fake()->paragraphs(3, true),
            'is_active' => true,
            'is_featured' => false,
        ];
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => ['is_featured' => true]);
    }
}
