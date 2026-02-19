<?php

namespace Database\Factories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Page>
 */
class PageFactory extends Factory
{
    protected $model = Page::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(3);
        return [
            'title' => $title,
            'slug' => \Illuminate\Support\Str::slug($title) . '-' . fake()->unique()->lexify('????'),
            'page_type' => 'static',
            'design_type' => 'general',
            'short_body' => fake()->paragraph(),
            'long_body' => fake()->paragraphs(2, true),
            'is_active' => true,
            'home_page' => false,
            'hide_header' => false,
            'hide_footer' => false,
            'widget_config' => null,
        ];
    }
}
