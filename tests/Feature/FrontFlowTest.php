<?php

use App\Models\Page;

test('homepage redirects to API documentation', function () {
    $response = $this->get(route('home'));

    $response->assertStatus(302);
    $response->assertRedirect('/api/documentation');
});

test('search redirects to API documentation', function () {
    $response = $this->get(route('search'));

    $response->assertStatus(302);
    $response->assertRedirect('/api/documentation');
});

test('page show via API returns 200 for existing slug', function () {
    $page = Page::factory()->create([
        'title' => 'Test Page',
        'slug' => 'test-page-flow',
        'short_body' => 'Short content.',
        'long_body' => 'Long body content here.',
        'is_active' => true,
    ]);

    $response = $this->getJson(route('api.pages.show', ['slug' => $page->slug]));

    $response->assertStatus(200);
});
