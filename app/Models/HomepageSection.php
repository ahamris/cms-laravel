<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;

/**
 * Homepage content sections for the React SPA (hero, feature cards, about OPMS, how it works, etc.).
 * Each section has a unique section_key and flexible content (JSON).
 *
 * @property int $id
 * @property string|null $section_key
 * @property array|null $content
 * @property bool $is_active
 */
class HomepageSection extends BaseModel
{
    public const CACHE_KEY = 'homepage_sections_api';

    public const SECTION_KEYS = [
        'hero',
        'feature_cards',
        'about_opms',
        'how_it_works',
        'user_features',
        'competition',
        'latest_updates',
        'bottom_cta',
    ];

    protected $fillable = [
        'section_name',
        'module_type',
        'identifier',
        'title',
        'description',
        'button_text',
        'sort_order',
        'is_active',
        'section_key',
        'content',
    ];

    protected $casts = [
        'content' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public static function getByKey(string $key): ?self
    {
        return self::where('section_key', $key)->where('is_active', true)->first();
    }

    /**
     * Get all sections keyed by section_key for the API (cached).
     */
    public static function getAllForApi(): array
    {
        return Cache::remember(self::CACHE_KEY, 60 * 60, function () {
            $sections = self::where('is_active', true)
                ->whereNotNull('section_key')
                ->whereNotNull('content')
                ->get()
                ->keyBy('section_key');

            return $sections->map(fn ($s) => $s->content ?? [])->toArray();
        });
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget(self::CACHE_KEY));
        static::deleted(fn () => Cache::forget(self::CACHE_KEY));
    }
}
