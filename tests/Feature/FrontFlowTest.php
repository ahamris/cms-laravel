<?php

use App\Models\Blog;
use App\Models\Page;

test('homepage returns 200', function () {
    $response = $this->get(route('home'));

    $response->assertStatus(200);
});

test('blog index responds with 200', function () {
    $response = $this->get(route('blog'));
    $response->assertSuccessful();
})->skip(true, 'Blog index query may 500 on SQLite (HAVING clause)');

test('blog show returns 200 for existing slug', function () {
    $blog = Blog::create([
        'title' => 'Visible Post',
        'slug' => 'visible-post',
        'short_body' => 'Short',
        'long_body' => 'Long body content.',
        'is_active' => true,
    ]);

    $response = $this->get(route('blog.show', $blog));

    $response->assertStatus(200);
});

test('blog show returns 404 for non-existent slug', function () {
    $response = $this->get(route('blog.show', ['blog' => 'non-existent-slug-'.uniqid()]));

    $response->assertStatus(404);
});

test('page show returns 200 for existing slug', function () {
    $page = Page::factory()->create([
        'title' => 'Test Page',
        'slug' => 'test-page-flow',
        'short_body' => 'Short content.',
        'long_body' => 'Long body content here.',
        'is_active' => true,
    ]);

    $response = $this->get(route('page.show', $page));

    $response->assertStatus(200);
});

test('contact page returns 200', function () {
    $response = $this->get(route('contact'));

    $response->assertStatus(200);
});
