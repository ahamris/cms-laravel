<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;

/**
 * @mixin IdeHelperHeroSection
 */
class HeroSection extends BaseModel
{
    const CACHE_KEY = 'hero_sections';

    protected $fillable = [
        'top_header_icon',
        'top_header_text',
        'top_header_url',
        'title',
        'subtitle',
        'slogan',
        'list_items',
        'primary_button_text',
        'primary_button_url',
        'secondary_button_text',
        'secondary_button_url',
        'card1_icon',
        'card1_bgcolor',
        'card1_title',
        'card1_description',
        'card2_icon',
        'card2_bgcolor',
        'card2_title',
        'card2_description',
        'image',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'list_items' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        // Clear cache on model events
        static::created(fn () => Cache::forget(self::CACHE_KEY));
        static::updated(fn () => Cache::forget(self::CACHE_KEY));
        static::deleted(fn () => Cache::forget(self::CACHE_KEY));
    }

    public static function getCached()
    {
        if (! Cache::has(self::CACHE_KEY)) {
            return Cache::remember(self::CACHE_KEY, 60 * 60,
                fn () => self::query()
                    ->where('is_active', true)
                    ->first()
            );
        }

        return Cache::get(self::CACHE_KEY);
    }

    /**
     * Get the active hero section
     */
    public static function getActive()
    {
        return static::query()
            ->where('is_active', true)
            ->first();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Activate this hero section
     */
    public function activate(): bool
    {
        return $this->update(['is_active' => true]);
    }

    /**
     * Deactivate this hero section
     */
    public function deactivate(): bool
    {
        return $this->update(['is_active' => false]);
    }
}

