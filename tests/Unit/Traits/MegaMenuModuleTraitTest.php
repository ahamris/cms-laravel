<?php

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('MegaMenuModuleTrait getLinksTreeAttribute returns structure with parent and children', function () {
    $page = Page::factory()->create([
        'title' => 'About Us',
        'slug' => 'about-us',
        'is_active' => true,
    ]);

    $tree = $page->links_tree;

    expect($tree)->toHaveKeys(['id', 'title', 'slug', 'parent', 'children']);
    expect($tree['id'])->toBe($page->id);
    expect($tree['title'])->toBe('About Us');
    expect($tree['slug'])->toBe('about-us');
    expect($tree['parent'])->toHaveKeys(['id', 'title', 'url']);
    expect($tree['parent']['url'])->toBe('/page/about-us');
    expect($tree['children'])->toBeArray();
    expect($tree['children'])->toBeEmpty();
});

test('MegaMenuModuleTrait scopeForMegaMenu filters active and orders by sort_order', function () {
    Page::factory()->create(['is_active' => false, 'title' => 'Inactive', 'slug' => 'inactive']);
    $active = Page::factory()->create(['is_active' => true, 'title' => 'Active', 'slug' => 'active']);

    $results = Page::forMegaMenu()->get();
    expect($results)->toHaveCount(1);
    expect($results->first()->id)->toBe($active->id);
});
