<?php

namespace Database\Factories;

use App\Models\Changelog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Changelog>
 */
class ChangelogFactory extends Factory
{
    protected $model = Changelog::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'content' => fake()->paragraphs(2, true),
            'date' => fake()->dateTimeBetween('-1 year'),
            'status' => fake()->randomElement(['new', 'improved', 'fixed', 'api']),
            'is_active' => true,
            'sort_order' => 0,
        ];
    }
}
