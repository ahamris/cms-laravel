<?php

/**
 * Search API endpoints: common response structure, short-query handling, whereAny behaviour.
 * Covers: blog, changelog, docs, course, solutions, features, live-sessions, pages, vacancies.
 *
 * Note: Uses RefreshDatabase. If you see "This database driver does not support dropping foreign keys by name",
 * run tests with MySQL (e.g. set DB_CONNECTION=mysql and DB_DATABASE in phpunit.xml or .env.testing).
 */

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Changelog;
use App\Models\Page;
use App\Models\VacancyModule\Vacancy;

beforeEach(function () {
    $this->blogCategory = BlogCategory::firstOrCreate(
        ['slug' => 'test-cat-search'],
        ['name' => 'Test Category Search', 'is_active' => true]
    );
});

// ---- Common response structure (data, template, query, count, meta) ----
test('blog search returns 200 with common structure when q length >= 2', function () {
    $response = $this->getJson(route('api.blog.search', ['q' => 'te']));

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'data',
        'template',
        'query',
        'count',
        'meta' => [
            'current_page',
            'last_page',
            'per_page',
            'total',
        ],
    ]);
    expect($response->json('template'))->toBe('blog-search');
    expect($response->json('query'))->toBe('te');
});

test('changelog search returns 200 with common structure', function () {
    $response = $this->getJson(route('api.changelog.search', ['q' => 'up']));

    $response->assertStatus(200);
    $response->assertJsonStructure(['data', 'template', 'query', 'count', 'meta']);
    expect($response->json('template'))->toBe('changelog-search');
});

test('docs search returns 200 with common structure', function () {
    $response = $this->getJson(route('api.docs.search', ['q' => 'do']));

    $response->assertStatus(200);
    $response->assertJsonStructure(['data', 'template', 'query', 'count', 'meta']);
    expect($response->json('template'))->toBe('docs-search');
});

test('course search returns 200 with common structure', function () {
    $response = $this->getJson(route('api.course.search', ['q' => 'vi']));

    $response->assertStatus(200);
    $response->assertJsonStructure(['data', 'template', 'query', 'count', 'meta']);
    expect($response->json('template'))->toBe('course-search');
});

test('solutions search returns 200 with common structure', function () {
    $response = $this->getJson(route('api.solutions.search', ['q' => 'so']));

    $response->assertStatus(200);
    $response->assertJsonStructure(['data', 'template', 'query', 'count', 'meta']);
    expect($response->json('template'))->toBe('solutions-search');
});

test('features search returns 200 with common structure', function () {
    $response = $this->getJson(route('api.features.search', ['q' => 'fe']));

    $response->assertStatus(200);
    $response->assertJsonStructure(['data', 'template', 'query', 'count', 'meta']);
    expect($response->json('template'))->toBe('features-search');
});

test('live-sessions search returns 200 with common structure', function () {
    $response = $this->getJson(route('api.course.live-sessions.search', ['q' => 'se']));

    $response->assertStatus(200);
    $response->assertJsonStructure(['data', 'template', 'query', 'count', 'meta']);
    expect($response->json('template'))->toBe('live-sessions-search');
});

test('pages search returns 200 with common structure', function () {
    $response = $this->getJson(route('api.pages.search', ['q' => 'pa']));

    $response->assertStatus(200);
    $response->assertJsonStructure(['data', 'template', 'query', 'count', 'meta']);
    expect($response->json('template'))->toBe('pages-search');
});

test('vacancies search returns 200 with common structure', function () {
    $response = $this->getJson(route('api.vacancies.search', ['q' => 'jo']));

    $response->assertStatus(200);
    $response->assertJsonStructure(['data', 'template', 'query', 'count', 'meta']);
    expect($response->json('template'))->toBe('vacancies-search');
});

// ---- Short query (q < 2 chars) returns empty data ----
test('blog search returns empty data when q length < 2', function () {
    $response = $this->getJson(route('api.blog.search', ['q' => 'a']));

    $response->assertStatus(200);
    $response->assertJsonPath('data', []);
    $response->assertJsonPath('count', 0);
    $response->assertJsonPath('query', 'a');
});

test('changelog search returns empty data when q length < 2', function () {
    $response = $this->getJson(route('api.changelog.search', ['q' => 'x']));

    $response->assertStatus(200);
    $response->assertJsonPath('data', []);
    $response->assertJsonPath('count', 0);
});

test('pages search returns empty data when q length < 2', function () {
    $response = $this->getJson(route('api.pages.search', ['q' => '']));

    $response->assertStatus(200);
    $response->assertJsonPath('data', []);
    $response->assertJsonPath('count', 0);
});

test('vacancies search returns empty data when q length < 2', function () {
    $response = $this->getJson(route('api.vacancies.search', ['q' => 'a']));

    $response->assertStatus(200);
    $response->assertJsonPath('data', []);
    $response->assertJsonPath('count', 0);
});

// ---- whereAny: search finds records by title ----
test('blog search finds post by title', function () {
    $unique = 'UniqueBlogSearch'.bin2hex(random_bytes(4));
    Blog::factory()->create([
        'title' => $unique,
        'slug' => \Illuminate\Support\Str::slug($unique).'-search',
        'is_active' => true,
        'blog_category_id' => $this->blogCategory->id,
    ]);

    $response = $this->getJson(route('api.blog.search', ['q' => $unique]));

    $response->assertStatus(200);
    expect($response->json('count'))->toBeGreaterThan(0);
    $data = $response->json('data');
    expect($data)->toBeArray();
    $titles = collect($data)->pluck('title')->toArray();
    expect($titles)->toContain($unique);
});

test('blog search finds post by short_body', function () {
    $needle = 'NeedleInShortBody'.bin2hex(random_bytes(2));
    Blog::factory()->create([
        'title' => 'Generic Title',
        'slug' => 'generic-title-search-'.uniqid(),
        'short_body' => "Some intro. {$needle} more text.",
        'is_active' => true,
        'blog_category_id' => $this->blogCategory->id,
    ]);

    $response = $this->getJson(route('api.blog.search', ['q' => $needle]));

    $response->assertStatus(200);
    expect($response->json('count'))->toBeGreaterThan(0);
});

test('page search finds page by title', function () {
    $unique = 'UniquePageSearch'.bin2hex(random_bytes(4));
    Page::factory()->create([
        'title' => $unique,
        'slug' => \Illuminate\Support\Str::slug($unique).'-search',
        'is_active' => true,
    ]);

    $response = $this->getJson(route('api.pages.search', ['q' => $unique]));

    $response->assertStatus(200);
    expect($response->json('count'))->toBeGreaterThan(0);
    $titles = collect($response->json('data'))->pluck('title')->toArray();
    expect($titles)->toContain($unique);
});

test('changelog search finds entry by title', function () {
    $unique = 'UniqueChangelogSearch'.bin2hex(random_bytes(4));
    Changelog::factory()->create([
        'title' => $unique,
        'description' => 'Description',
        'content' => 'Content',
        'is_active' => true,
        'sort_order' => 0,
    ]);

    $response = $this->getJson(route('api.changelog.search', ['q' => $unique]));

    $response->assertStatus(200);
    expect($response->json('count'))->toBeGreaterThan(0);
    $titles = collect($response->json('data'))->pluck('title')->toArray();
    expect($titles)->toContain($unique);
});

test('vacancy search finds vacancy by title', function () {
    $unique = 'UniqueVacancySearch'.bin2hex(random_bytes(4));
    Vacancy::factory()->create([
        'title' => $unique,
        'slug' => \Illuminate\Support\Str::slug($unique).'-search',
        'description' => 'Description',
        'is_active' => true,
    ]);

    $response = $this->getJson(route('api.vacancies.search', ['q' => $unique]));

    $response->assertStatus(200);
    expect($response->json('count'))->toBeGreaterThan(0);
    $data = $response->json('data');
    $titles = collect($data)->pluck('title')->toArray();
    expect($titles)->toContain($unique);
});

// ---- per_page and meta ----
test('blog search respects per_page', function () {
    $response = $this->getJson(route('api.blog.search', ['q' => 'test', 'per_page' => 5]));

    $response->assertStatus(200);
    $response->assertJsonPath('meta.per_page', 5);
    $data = $response->json('data');
    expect(count($data))->toBeLessThanOrEqual(5);
});

test('pages search returns meta with total and pagination', function () {
    $response = $this->getJson(route('api.pages.search', ['q' => 'e', 'per_page' => 3]));

    $response->assertStatus(200);
    $meta = $response->json('meta');
    expect($meta)->toHaveKeys(['current_page', 'last_page', 'per_page', 'total']);
    expect($meta['per_page'])->toBe(3);
});
