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

test('api blog-posts returns 200 and list of up to 3 posts', function () {
    $response = $this->getJson(route('api.blog-posts'));

    $response->assertStatus(200);
    $response->assertJsonStructure(['data']);
    $data = $response->json('data');
    expect($data)->toBeArray();
    expect(count($data))->toBeLessThanOrEqual(3);
});

test('api blog-posts returns active posts only', function () {
    Blog::factory()->count(2)->create(['is_active' => true, 'blog_category_id' => $this->category->id]);
    Blog::factory()->create(['is_active' => false, 'blog_category_id' => $this->category->id, 'slug' => 'inactive-blog-api']);

    $response = $this->getJson(route('api.blog-posts'));

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

test('api artikelen load-more returns 200 with data and has_more', function () {
    $response = $this->getJson(route('api.blog.load-more'));

    $response->assertStatus(200);
    $response->assertJsonStructure(['data', 'has_more', 'next_page']);
});

test('api artikelen load-more accepts page and per_page', function () {
    $response = $this->getJson(route('api.blog.load-more', ['page' => 1, 'per_page' => 4]));

    $response->assertStatus(200);
    $data = $response->json('data');
    expect(count($data))->toBeLessThanOrEqual(4);
});

test('api artikelen load-more accepts search and category', function () {
    $response = $this->getJson(route('api.blog.load-more', [
        'page' => 1,
        'search' => 'test',
        'category' => $this->category->slug,
    ]));

    $response->assertStatus(200);
    $response->assertJsonStructure(['data', 'has_more', 'next_page']);
});

// ---- Comments (artikelen/reactie) ----
test('api comment store succeeds as guest with valid data', function () {
    $blog = Blog::factory()->create([
        'slug' => 'comment-target-api',
        'is_active' => true,
        'blog_category_id' => $this->category->id,
    ]);

    $response = $this->postJson(route('api.comment.store'), [
        'body' => 'A nice comment.',
        'entity_type' => Blog::class,
        'entity_id' => $blog->id,
        'guest_name' => 'Guest User',
        'guest_email' => 'guest@example.com',
    ]);

    $response->assertStatus(201);
    $response->assertJson(['success' => true]);
    $blog->refresh();
    expect($blog->comments()->count())->toBe(1);
});

test('api comment store fails when required fields missing', function () {
    $response = $this->postJson(route('api.comment.store'), [
        'body' => 'Comment',
    ]);

    $response->assertStatus(422);
});

test('api comment store fails for invalid entity_type', function () {
    $response = $this->postJson(route('api.comment.store'), [
        'body' => 'Comment',
        'entity_type' => 'InvalidClass',
        'entity_id' => 1,
        'guest_name' => 'Guest',
        'guest_email' => 'guest@example.com',
    ]);

    $response->assertStatus(422);
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

    $response = $this->postJson(route('api.comment.like', ['comment' => $comment->id]));

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

    $response = $this->postJson(route('api.comment.dislike', ['comment' => $comment->id]));

    $response->assertStatus(200);
});
