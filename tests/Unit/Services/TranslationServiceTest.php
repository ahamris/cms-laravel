<?php

use App\Models\Translation;
use App\Services\TranslationService;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    config(['translation.cache_enabled' => false]);
});

test('TranslationService get returns key when translation not in database', function () {
    $service = app(TranslationService::class);

    $result = $service->get('nonexistent.key.here');

    expect($result)->toBe('nonexistent.key.here');
});

test('TranslationService get returns translation when found in database', function () {
    Translation::create([
        'locale' => 'en',
        'group_name' => 'test',
        'translation_key' => 'welcome.message',
        'translation_value' => 'Welcome!',
        'is_active' => true,
    ]);

    $service = app(TranslationService::class);
    config(['translation.cache_enabled' => false]);

    $result = $service->get('welcome.message', 'en', [], 'test');

    expect($result)->toBe('Welcome!');
});

test('TranslationService get replaces placeholders', function () {
    Translation::create([
        'locale' => 'en',
        'group_name' => null,
        'translation_key' => 'greeting',
        'translation_value' => 'Hello, :name!',
        'is_active' => true,
    ]);

    $service = app(TranslationService::class);

    $result = $service->get('greeting', 'en', ['name' => 'World']);

    expect($result)->toBe('Hello, World!');
});
