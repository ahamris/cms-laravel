<?php

use App\Models\VacancyModule\Vacancy;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('vacancy scopeActive filters active and not closed', function () {
    Vacancy::factory()->create(['is_active' => true, 'closing_date' => null]);
    Vacancy::factory()->create(['is_active' => true, 'closing_date' => now()->addDays(5)]);
    Vacancy::factory()->create(['is_active' => false, 'closing_date' => null]);
    Vacancy::factory()->create(['is_active' => true, 'closing_date' => now()->subDay()]);

    $active = Vacancy::active()->get();
    expect($active)->toHaveCount(2);
});

test('vacancy slug is auto-filled from title on create', function () {
    $v = Vacancy::factory()->create([
        'title' => 'Senior PHP Developer',
        'slug' => null,
    ]);
    expect($v->slug)->toBe('senior-php-developer');
});

test('vacancy slug is not overwritten when provided', function () {
    $v = Vacancy::factory()->create([
        'title' => 'Some Title',
        'slug' => 'custom-slug',
    ]);
    expect($v->slug)->toBe('custom-slug');
});
