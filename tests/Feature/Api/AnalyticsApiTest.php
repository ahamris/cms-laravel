<?php

use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    Cache::flush();
});

test('api analytics track returns 200 with valid payload', function () {
    $response = $this->postJson(route('api.analytics.track'), [
        'url' => '/contact',
        'page_title' => 'Contact',
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure(['status']);
    expect($response->json('status'))->toBeIn(['tracked', 'skipped', 'error']);
});

test('api analytics track returns 200 skipped for api url', function () {
    $response = $this->postJson(route('api.analytics.track'), [
        'url' => '/api/pages',
        'page_title' => 'API',
    ]);

    $response->assertStatus(200);
    $response->assertJson(['status' => 'skipped']);
});

test('api analytics track returns 200 when url missing (best-effort returns error status)', function () {
    // Controller catches ValidationException and returns 200 with status 'error'
    $response = $this->postJson(route('api.analytics.track'), [
        'page_title' => 'No URL',
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure(['status']);
});

test('api analytics batch-track returns 200 with valid views', function () {
    $response = $this->postJson(route('api.analytics.batch-track'), [
        'views' => [
            ['url' => '/', 'page_title' => 'Home'],
            ['url' => '/contact', 'page_title' => 'Contact'],
        ],
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure(['status', 'count']);
});

test('api analytics batch-track returns 200 when views missing (best-effort)', function () {
    $response = $this->postJson(route('api.analytics.batch-track'), []);

    $response->assertStatus(200);
    $response->assertJsonStructure(['status']);
});

test('api analytics batch-track returns 200 when views exceed limit (best-effort)', function () {
    $views = array_fill(0, 11, ['url' => '/page', 'page_title' => 'Page']);
    $response = $this->postJson(route('api.analytics.batch-track'), ['views' => $views]);

    $response->assertStatus(200);
});

test('api analytics guest-activity returns 200', function () {
    $response = $this->postJson(route('api.analytics.guest-activity'), [
        'url' => '/',
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure(['status']);
});

test('api analytics performance returns 200 with valid payload', function () {
    $response = $this->postJson(route('api.analytics.performance'), [
        'url' => '/',
        'metrics' => ['lcp' => 100, 'fid' => 50],
    ]);

    $response->assertStatus(200);
    $response->assertJson(['status' => 'tracked']);
});

test('api analytics performance returns 200 when metrics missing (best-effort)', function () {
    $response = $this->postJson(route('api.analytics.performance'), [
        'url' => '/',
    ]);

    $response->assertStatus(200);
});

test('api analytics stats returns 200 with structure', function () {
    $response = $this->getJson(route('api.analytics.stats'));

    $response->assertStatus(200);
    $response->assertJsonStructure(['today_views', 'status']);
});
