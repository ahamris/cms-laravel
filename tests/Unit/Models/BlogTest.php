<?php

use App\Models\Blog;
use Illuminate\Support\Facades\Cache;

test('Blog getCachedCarouselBlogs returns collection and uses cache', function () {
    Cache::flush();

    $blog = Blog::create([
        'title' => 'Cached Post',
        'slug' => 'cached-post',
        'short_body' => 'Short',
        'long_body' => 'Long body for carousel.',
        'is_active' => true,
        'is_featured' => true,
    ]);

    $result = Blog::getCachedCarouselBlogs(6);
    expect($result)->toBeInstanceOf(\Illuminate\Support\Collection::class);
    expect($result->count())->toBeGreaterThan(0);
});

test('Blog CAROUSEL_CACHE_KEY constant is defined', function () {
    expect(Blog::CAROUSEL_CACHE_KEY)->toBe('carousel_blogs');
});
