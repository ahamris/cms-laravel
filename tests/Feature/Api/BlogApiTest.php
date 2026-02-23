<?php

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Comment;

beforeEach(function () {
    // Ensure we have a category for blog factory
    $this->category = BlogCategory::firstOrCreate(
        ['slug' => 'test-cat-api'],
        ['name' => 'Test Category API', 'is_active' => true]
    );
});

test('api blog index returns 200 and paginated list', function () {
    $response = $this->getJson(route('api.blog.index'));

    $response->assertStatus(200);
    $response->assertJsonStructure(['data', 'has_more', 'next_page']);
    $data = $response->json('data');
    expect($data)->toBeArray();
    expect(count($data))->toBeLessThanOrEqual(6);
});

test('api blog index returns active posts only', function () {
    Blog::factory()->count(2)->create(['is_active' => true, 'blog_category_id' => $this->category->id]);
    Blog::factory()->create(['is_active' => false, 'blog_category_id' => $this->category->id, 'slug' => 'inactive-blog-api']);

    $response = $this->getJson(route('api.blog.index'));

    $response->assertStatus(200);
    $slugs = collect($response->json('data'))->pluck('slug')->toArray();
    expect($slugs)->not->toContain('inactive-blog-api');
});

test('api blog show returns 200 for active blog slug', function () {
    $blog = Blog::factory()->create([
        'slug' => 'my-post-api',
        'title' => 'My Post',
        'is_active' => true,
        'blog_category_id' => $this->category->id,
    ]);

    $response = $this->getJson(route('api.blog.show', ['slug' => $blog->slug]));

    $response->assertStatus(200);
    $response->assertJsonPath('data.slug', $blog->slug);
    $response->assertJsonPath('data.title', $blog->title);
});

test('api blog show returns 404 for inactive blog', function () {
    $blog = Blog::factory()->create([
        'slug' => 'inactive-post-api',
        'is_active' => false,
        'blog_category_id' => $this->category->id,
    ]);

    $response = $this->getJson(route('api.blog.show', ['slug' => $blog->slug]));

    $response->assertStatus(404);
});

test('api blog show returns 404 for non-existent slug', function () {
    $response = $this->getJson(route('api.blog.show', ['slug' => 'non-existent-' . uniqid()]));

    $response->assertStatus(404);
});

test('api blog index accepts page and per_page', function () {
    $response = $this->getJson(route('api.blog.index', ['page' => 1, 'per_page' => 4]));

    $response->assertStatus(200);
    $data = $response->json('data');
    expect(count($data))->toBeLessThanOrEqual(4);
});

test('api blog index accepts search and category', function () {
    $response = $this->getJson(route('api.blog.index', [
        'page' => 1,
        'search' => 'test',
        'category' => $this->category->slug,
    ]));

    $response->assertStatus(200);
    $response->assertJsonStructure(['data', 'has_more', 'next_page']);
});

// ---- Comments ----
test('api blog comment store succeeds as guest with valid data', function () {
    $blog = Blog::factory()->create([
        'slug' => 'comment-target-api',
        'is_active' => true,
        'blog_category_id' => $this->category->id,
    ]);

    $response = $this->postJson(route('api.blog.comments.store', ['slug' => $blog->slug]), [
        'body' => 'A nice comment.',
        'guest_name' => 'Guest User',
        'guest_email' => 'guest@example.com',
    ]);

    $response->assertStatus(201);
    $response->assertJson(['success' => true]);
    $blog->refresh();
    expect($blog->comments()->count())->toBe(1);
});

test('api blog comment store fails when required fields missing', function () {
    $blog = Blog::factory()->create(['slug' => 'missing-fields-api', 'is_active' => true, 'blog_category_id' => $this->category->id]);

    $response = $this->postJson(route('api.blog.comments.store', ['slug' => $blog->slug]), [
        'body' => 'Comment',
    ]);

    $response->assertStatus(422);
});

test('api blog comment store returns 404 for unknown blog slug', function () {
    $response = $this->postJson(route('api.blog.comments.store', ['slug' => 'non-existent-blog-slug']), [
        'body' => 'Comment',
        'guest_name' => 'Guest',
        'guest_email' => 'guest@example.com',
    ]);

    $response->assertStatus(404);
});

test('api comment like returns 200 for existing comment', function () {
    $blog = Blog::factory()->create([
        'slug' => 'like-target-api',
        'is_active' => true,
        'blog_category_id' => $this->category->id,
    ]);
    $comment = $blog->comments()->create([
        'body' => 'Comment to like',
        'guest_name' => 'Guest',
        'guest_email' => 'g@example.com',
        'is_approved' => 0,
    ]);

    $response = $this->postJson(route('api.blog.comments.like', ['slug' => $blog->slug, 'comment' => $comment->id]));

    $response->assertStatus(200);
});

test('api comment dislike returns 200 for existing comment', function () {
    $blog = Blog::factory()->create([
        'slug' => 'dislike-target-api',
        'is_active' => true,
        'blog_category_id' => $this->category->id,
    ]);
    $comment = $blog->comments()->create([
        'body' => 'Comment to dislike',
        'guest_name' => 'Guest',
        'guest_email' => 'g2@example.com',
        'is_approved' => 0,
    ]);

    $response = $this->postJson(route('api.blog.comments.dislike', ['slug' => $blog->slug, 'comment' => $comment->id]));

    $response->assertStatus(200);
});
