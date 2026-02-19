<?php

use App\Models\Blog;
use Illuminate\Support\Facades\Cache;

test('creating a model that uses ClearsSitemapCache forgets sitemap_xml cache', function () {
    Cache::put('sitemap_xml', '<xml>cached</xml>');

    Blog::create([
        'title' => 'Sitemap Clear Test',
        'slug' => 'sitemap-clear-test',
        'short_body' => 'Short',
        'long_body' => 'Long body for sitemap.',
        'is_active' => true,
    ]);

    expect(Cache::get('sitemap_xml'))->toBeNull();
});

test('updating a model that uses ClearsSitemapCache forgets sitemap_xml cache', function () {
    $blog = Blog::create([
        'title' => 'Original',
        'slug' => 'original-sitemap',
        'short_body' => 'Short',
        'long_body' => 'Long.',
        'is_active' => true,
    ]);
    Cache::put('sitemap_xml', '<xml>cached</xml>');

    $blog->update(['title' => 'Updated']);

    expect(Cache::get('sitemap_xml'))->toBeNull();
});

test('deleting a model that uses ClearsSitemapCache forgets sitemap_xml cache', function () {
    $blog = Blog::create([
        'title' => 'To Delete',
        'slug' => 'to-delete-sitemap',
        'short_body' => 'Short',
        'long_body' => 'Long.',
        'is_active' => true,
    ]);
    Cache::put('sitemap_xml', '<xml>cached</xml>');

    $blog->delete();

    expect(Cache::get('sitemap_xml'))->toBeNull();
});
