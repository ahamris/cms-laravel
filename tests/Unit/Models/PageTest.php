<?php

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('page homepage flag unsets other homepages when set', function () {
    $p1 = Page::factory()->create(['home_page' => false, 'is_active' => true]);
    $p2 = Page::factory()->create(['home_page' => true, 'is_active' => true]);

    expect($p2->home_page)->toBeTrue();

    $p1->update(['home_page' => true]);

    expect($p1->fresh()->home_page)->toBeTrue();
    expect($p2->fresh()->home_page)->toBeFalse();
});

test('page widget_config is cast to array', function () {
    $page = Page::factory()->create([
        'widget_config' => ['blocks' => [['type' => 'hero']]],
    ]);

    expect($page->widget_config)->toBeArray();
    expect($page->widget_config['blocks'])->toHaveCount(1);
    expect($page->widget_config['blocks'][0]['type'])->toBe('hero');
});

test('page isHomepage returns true when home_page is true', function () {
    $page = Page::factory()->create(['home_page' => true]);
    expect($page->isHomepage())->toBeTrue();

    $page->update(['home_page' => false]);
    expect($page->fresh()->isHomepage())->toBeFalse();
});

test('page getHomepage returns active homepage', function () {
    Page::factory()->create(['home_page' => false, 'is_active' => true]);
    $home = Page::factory()->create(['home_page' => true, 'is_active' => true]);

    expect(Page::getHomepage()?->id)->toBe($home->id);
});
