<?php

use App\Models\AIServiceSetting;
use App\Services\ResolvedAiProviders;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('normalizes claude to anthropic service', function (): void {
    expect(AIServiceSetting::normalizeServiceName('claude'))->toBe('anthropic');
    expect(AIServiceSetting::normalizeServiceName('Claude'))->toBe('anthropic');
});

it('resolves active provider model map including openai and anthropic', function (): void {
    AIServiceSetting::query()->create([
        'service' => 'openai',
        'api_key' => 'test-key',
        'model' => 'gpt-4o-mini',
        'is_active' => true,
        'priority' => 0,
    ]);

    AIServiceSetting::query()->create([
        'service' => 'anthropic',
        'api_key' => 'test-key-2',
        'model' => 'claude-3-5-sonnet-latest',
        'is_active' => true,
        'priority' => 1,
    ]);

    $map = ResolvedAiProviders::providerModelMap();

    expect($map)->toHaveKey('openai');
    expect($map)->toHaveKey('anthropic');
    expect($map['openai'])->toBe('gpt-4o-mini');
    expect($map['anthropic'])->toBe('claude-3-5-sonnet-latest');
});

it('orders provider map for content tasks with gemini before groq when both active', function (): void {
    AIServiceSetting::query()->create([
        'service' => 'groq',
        'api_key' => 'g-key',
        'model' => 'llama-x',
        'is_active' => true,
        'priority' => 0,
    ]);
    AIServiceSetting::query()->create([
        'service' => 'gemini',
        'api_key' => 'gem-key',
        'model' => 'gemini-2.0-flash',
        'is_active' => true,
        'priority' => 99,
    ]);

    $keys = array_keys(ResolvedAiProviders::providerModelMapForTask('content'));

    expect($keys[0])->toBe('gemini');
    expect($keys[1])->toBe('groq');
});

it('appends providers not listed in preferred order', function (): void {
    AIServiceSetting::query()->create([
        'service' => 'openai',
        'api_key' => 'o-key',
        'model' => 'gpt-4o-mini',
        'is_active' => true,
        'priority' => 0,
    ]);

    $map = ResolvedAiProviders::providerModelMapForTask('seo');

    expect($map)->toHaveKey('openai');
    expect(array_key_first($map))->toBe('openai');
});
