<?php

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('page can be created with required attributes', function () {
    $page = Page::factory()->create([
        'title' => 'Test Page',
        'slug' => 'test-page',
        'short_body' => 'Short content.',
        'long_body' => 'Long body content here.',
        'is_active' => true,
    ]);

    expect($page->title)->toBe('Test Page');
    expect($page->slug)->toBe('test-page');
    expect($page->is_active)->toBeTrue();
});
