<?php

namespace Database\Factories;

use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    protected $model = Comment::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'entity_type' => Blog::class,
            'entity_id' => 1,
            'body' => fake()->paragraph(),
            'is_approved' => false,
            'parent_id' => null,
            'likes' => 0,
            'dislikes' => 0,
        ];
    }

    public function forBlog(Blog $blog): static
    {
        return $this->state(fn (array $attributes) => [
            'entity_type' => Blog::class,
            'entity_id' => $blog->id,
        ]);
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => ['is_approved' => true]);
    }
}
