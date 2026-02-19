<?php

use App\Models\Changelog;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('changelog slug is generated from title', function () {
    $changelog = Changelog::factory()->create(['title' => 'My Feature Release', 'slug' => null]);
    expect($changelog->slug)->not->toBeNull();
    expect($changelog->slug)->toContain('feature');
});

test('changelog scopeByStatus filters by status', function () {
    Changelog::factory()->create(['status' => 'new', 'is_active' => true]);
    Changelog::factory()->create(['status' => 'fixed', 'is_active' => true]);

    $newOnes = Changelog::byStatus('new')->get();
    expect($newOnes)->toHaveCount(1);
    expect($newOnes->first()->status)->toBe('new');
});

test('changelog getStatusColorAttribute returns correct color', function () {
    $c = Changelog::factory()->make(['status' => 'new']);
    expect($c->status_color)->toBe('success');

    $c->status = 'improved';
    expect($c->status_color)->toBe('primary');

    $c->status = 'fixed';
    expect($c->status_color)->toBe('warning');

    $c->status = 'api';
    expect($c->status_color)->toBe('info');
});

test('changelog getCached returns collection', function () {
    Changelog::factory()->create(['is_active' => true, 'title' => 'Cached']);

    $result = Changelog::getCached();
    expect($result)->toBeInstanceOf(\Illuminate\Support\Collection::class);
    expect($result->count())->toBeGreaterThan(0);
});
