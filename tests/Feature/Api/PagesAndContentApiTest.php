<?php

use App\Models\Legal;
use App\Models\Page;
use App\Models\StaticPage;

beforeEach(function () {
    // No Origin/Referer => frontend.origins allows request
});

// ---- Pages ----
test('api pages index returns 200 and paginated structure', function () {
    $response = $this->getJson(route('api.pages.index'));

    $response->assertStatus(200);
    $response->assertJsonStructure(['data', 'links', 'meta']);
});

test('api pages index respects per_page', function () {
    $response = $this->getJson(route('api.pages.index', ['per_page' => 5]));

    $response->assertStatus(200);
    $data = $response->json('data');
    expect(count($data))->toBeLessThanOrEqual(5);
});

test('api pages show returns 200 for active page', function () {
    $page = Page::factory()->create([
        'slug' => 'about-us-api',
        'title' => 'About Us',
        'is_active' => true,
    ]);

    $response = $this->getJson(route('api.pages.show', ['slug' => $page->slug]));

    $response->assertStatus(200);
    $response->assertJsonPath('data.slug', $page->slug);
    $response->assertJsonPath('data.title', $page->title);
});

test('api pages show returns 404 for inactive page', function () {
    $page = Page::factory()->create([
        'slug' => 'inactive-page-api',
        'is_active' => false,
    ]);

    $response = $this->getJson(route('api.pages.show', ['slug' => $page->slug]));

    $response->assertStatus(404);
});

test('api pages show returns 404 for non-existent slug', function () {
    $response = $this->getJson(route('api.pages.show', ['slug' => 'non-existent-' . uniqid()]));

    $response->assertStatus(404);
});

// ---- Static pages ----
test('api static show returns 200 for active static page', function () {
    $static = StaticPage::create([
        'title' => 'FAQ',
        'slug' => 'faq-api',
        'body' => 'Frequently asked questions.',
        'is_active' => true,
    ]);

    $response = $this->getJson(route('api.static.show', ['slug' => $static->slug]));

    $response->assertStatus(200);
    $response->assertJsonPath('data.slug', $static->slug);
    $response->assertJsonPath('data.title', $static->title);
});

test('api static show returns 404 for inactive static page', function () {
    $static = StaticPage::create([
        'title' => 'Hidden',
        'slug' => 'hidden-static-api',
        'body' => 'Hidden content.',
        'is_active' => false,
    ]);

    $response = $this->getJson(route('api.static.show', ['slug' => $static->slug]));

    $response->assertStatus(404);
    $response->assertJson(['message' => 'Static page not found.']);
});

test('api static show returns 404 for non-existent slug', function () {
    $response = $this->getJson(route('api.static.show', ['slug' => 'missing-' . uniqid()]));

    $response->assertStatus(404);
});

// ---- Legal ----
test('api legal show returns 200 for active legal page', function () {
    $legal = Legal::create([
        'title' => 'Privacy',
        'slug' => 'privacy-api',
        'body' => 'Privacy policy content.',
        'is_active' => true,
    ]);

    $response = $this->getJson(route('api.legal.show', ['slug' => $legal->slug]));

    $response->assertStatus(200);
    $response->assertJsonPath('data.slug', $legal->slug);
    $response->assertJsonPath('data.title', $legal->title);
});

test('api legal show returns 404 for non-existent slug', function () {
    $response = $this->getJson(route('api.legal.show', ['slug' => 'missing-legal-' . uniqid()]));

    $response->assertStatus(404);
});

// ---- Sitemap ----
test('api sitemap returns 200 and data array', function () {
    $response = $this->getJson(route('api.sitemap'));

    $response->assertStatus(200);
    $response->assertJsonStructure(['data']);
    $data = $response->json('data');
    expect($data)->toBeArray();
    foreach (array_slice($data, 0, 3) as $item) {
        expect($item)->toHaveKeys(['url', 'loc', 'priority']);
    }
});

// ---- Settings (Homepage) ----
test('api settings returns 200 with site and theme', function () {
    $response = $this->getJson(route('api.settings'));

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'site' => ['name', 'tagline', 'description', 'logo', 'favicon', 'hero_background'],
        'theme' => ['base_color', 'accent_color'],
    ]);
});
