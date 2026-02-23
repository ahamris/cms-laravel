<?php

use App\Models\BlogCategory;

beforeEach(function () {
    BlogCategory::firstOrCreate(
        ['slug' => 'test-cat-misc'],
        ['name' => 'Test Category Misc', 'is_active' => true]
    );
});

// ---- Search ----
test('api search suggestions returns 200 with short query', function () {
    $response = $this->getJson(route('search.suggestions', ['q' => 'a']));

    $response->assertStatus(200);
    $response->assertJsonStructure(['suggestions', 'mostSearched']);
    expect($response->json('suggestions'))->toBeArray();
});

test('api search suggestions returns suggestions when q length >= 2', function () {
    $response = $this->getJson(route('search.suggestions', ['q' => 'ab']));

    $response->assertStatus(200);
    $response->assertJsonStructure(['suggestions', 'mostSearched']);
});

// ---- Menus ----
test('api menus index returns 200', function () {
    $response = $this->getJson(route('api.menus.index'));

    $response->assertStatus(200);
});

test('api menus header returns 200 with items and settings', function () {
    $response = $this->getJson(route('api.menus.header'));

    $response->assertStatus(200);
    $response->assertJsonStructure(['items', 'settings']);
});

test('api menus footer returns 200 with columns', function () {
    $response = $this->getJson(route('api.menus.footer'));

    $response->assertStatus(200);
    $response->assertJsonStructure(['columns']);
});

test('api menus sticky returns 200', function () {
    $response = $this->getJson(route('api.menus.sticky'));

    $response->assertStatus(200);
});

// ---- Vacancies ----
test('api vacancies index returns 200 with data and meta', function () {
    $response = $this->getJson(route('api.vacancies.index'));

    $response->assertStatus(200);
    $response->assertJsonStructure(['data', 'meta', 'filters']);
});

test('api vacancies index accepts search and per_page', function () {
    $response = $this->getJson(route('api.vacancies.index', ['search' => 'dev', 'per_page' => 5]));

    $response->assertStatus(200);
});

test('api vacancies show returns 404 for non-existent slug', function () {
    $response = $this->getJson(route('api.vacancies.show', ['slug' => 'non-existent-vacancy-' . uniqid()]));

    $response->assertStatus(404);
});

// ---- Docs ----
test('api docs index returns 200', function () {
    $response = $this->getJson(route('api.docs.index'));

    $response->assertStatus(200);
});

test('api docs search returns 200', function () {
    $response = $this->getJson(route('api.docs.search', ['q' => 'test']));

    $response->assertStatus(200);
});

// ---- Pricing ----
test('api prijzen index returns 200 with data', function () {
    $response = $this->getJson(route('api.pricing.index'));

    $response->assertStatus(200);
    $response->assertJsonStructure(['data' => ['plans', 'boosters', 'features']]);
});

test('api prijzen configurator returns 200', function () {
    $response = $this->getJson(route('api.pricing.configurator'));

    $response->assertStatus(200);
    $response->assertJsonStructure(['data' => ['boosters']]);
});

// ---- Changelog ----
test('api changelog index returns 200 with data and meta', function () {
    $response = $this->getJson(route('api.changelog.index'));

    $response->assertStatus(200);
    $response->assertJsonStructure(['data', 'meta']);
});

test('api changelog index accepts per_page and status', function () {
    $response = $this->getJson(route('api.changelog.index', ['per_page' => 5, 'status' => 'all']));

    $response->assertStatus(200);
});

// ---- Trial (proefversie) ----
test('api proefversie index returns 200', function () {
    $response = $this->getJson(route('api.trial.index'));

    $response->assertStatus(200);
});

test('api proefversie success returns 200', function () {
    $response = $this->getJson(route('api.trial.success'));

    $response->assertStatus(200);
});

// ---- Course ----
test('api course index returns 200', function () {
    $response = $this->getJson(route('api.course.index'));

    $response->assertStatus(200);
});

test('api course categories returns 200', function () {
    $response = $this->getJson(route('api.course.categories'));

    $response->assertStatus(200);
});

// ---- Live sessions (under course) ----
test('api course live-sessions index returns 200', function () {
    $response = $this->getJson(route('api.course.live-sessions.index'));

    $response->assertStatus(200);
});

test('api course live-sessions recordings returns 200', function () {
    $response = $this->getJson(route('api.course.live-sessions.recordings'));

    $response->assertStatus(200);
});

// ---- Modules ----
test('api modules index returns 200', function () {
    $response = $this->getJson(route('api.modules.index'));

    $response->assertStatus(200);
});

// ---- Features ----
test('api features index returns 200', function () {
    $response = $this->getJson(route('api.features.index'));

    $response->assertStatus(200);
});

// ---- Solutions ----
test('api solutions index returns 200', function () {
    $response = $this->getJson(route('api.solutions.index'));

    $response->assertStatus(200);
});

test('api solutions show returns 404 for non-existent anchor', function () {
    $response = $this->getJson(route('api.solutions.show', ['anchor' => 'non-existent-' . uniqid()]));

    $response->assertStatus(404);
});
