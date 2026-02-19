<?php

namespace Database\Factories;

use App\Models\VacancyModule\Vacancy;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VacancyModule\Vacancy>
 */
class VacancyFactory extends Factory
{
    protected $model = Vacancy::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->jobTitle();
        return [
            'title' => $title,
            'slug' => \Illuminate\Support\Str::slug($title) . '-' . fake()->unique()->lexify('????'),
            'location' => fake()->city(),
            'short_code' => fake()->randomElement(['BE', 'FE', 'QA', 'PM']),
            'type' => 'full-time',
            'department' => fake()->word(),
            'description' => fake()->paragraphs(2, true),
            'requirements' => fake()->paragraph(),
            'responsibilities' => fake()->paragraph(),
            'salary_range' => 'Competitive',
            'is_active' => true,
            'closing_date' => fake()->dateTimeBetween('now', '+3 months'),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => ['is_active' => false]);
    }
}
