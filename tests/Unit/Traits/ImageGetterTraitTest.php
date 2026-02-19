<?php

use App\Models\Blog;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
});

test('getImage returns null when image and cover_image are empty', function () {
    $blog = Blog::create([
        'title' => 'No Image',
        'slug' => 'no-image',
        'short_body' => 'Short',
        'long_body' => 'Long body.',
        'is_active' => true,
    ]);

    expect($blog->getImageUrl())->toBeNull();
    expect($blog->image)->toBeNull();
});

test('getImageUrl returns full URL when image is already a URL', function () {
    $blog = Blog::create([
        'title' => 'URL Image',
        'slug' => 'url-image',
        'short_body' => 'Short',
        'long_body' => 'Long.',
        'is_active' => true,
        'image' => 'https://example.com/pic.jpg',
    ]);

    expect($blog->getImageUrl())->toBe('https://example.com/pic.jpg');
});

test('getImageUrl returns asset path when image is relative path', function () {
    $blog = Blog::create([
        'title' => 'Asset Image',
        'slug' => 'asset-image',
        'short_body' => 'Short',
        'long_body' => 'Long.',
        'is_active' => true,
        'image' => 'images/placeholder.jpg',
    ]);

    $url = $blog->getImageUrl();
    expect($url)->toContain('images/placeholder.jpg');
});
