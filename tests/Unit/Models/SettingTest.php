<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    Cache::flush();
});

test('Setting getValue returns default when key does not exist', function () {
    $result = Setting::getValue('nonexistent_key_'.uniqid(), 'my-default');

    expect($result)->toBe('my-default');
});

test('Setting getValue returns default null when key does not exist and no default given', function () {
    $result = Setting::getValue('nonexistent_key_'.uniqid());

    expect($result)->toBeNull();
});
