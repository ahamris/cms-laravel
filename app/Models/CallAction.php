<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

/**
 * @mixin IdeHelperCallAction
 */
class CallAction extends BaseModel
{

    protected $fillable = [
        'title',
        'content',
        'primary_button_text',
        'primary_button_url',
        'primary_button_external',
        'secondary_button_text',
        'secondary_button_url',
        'secondary_button_external',
        'background_color',
        'text_color',
        'section_identifier',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'primary_button_external' => 'boolean',
        'secondary_button_external' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Cache key for all call actions
     */
    const CACHE_KEY = 'call_actions_all';

    /**
     * Cache duration in seconds (24 hours)
     */
    const CACHE_DURATION = 86400;

    /**
     * Boot method to handle cache invalidation
     */
    protected static function boot()
    {
        parent::boot();

        // Clear cache when model is created, updated, or deleted
        static::created(fn () => self::clearCache());
        static::saved(fn () => self::clearCache());
        static::deleted(fn () => self::clearCache());
    }

    /**
     * Get all cached call actions
     */
    public static function getCached()
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_DURATION, function () {
            return self::where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('created_at')
                ->get();
        });
    }

    /**
     * Get a specific call action by section identifier with caching
     */
    public static function getByIdentifier(string $identifier)
    {
        $cacheKey = "call_action_{$identifier}";

        return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($identifier) {
            return self::where('section_identifier', $identifier)
                ->where('is_active', true)
                ->first();
        });
    }

    /**
     * Clear all call action caches
     */
    public static function clearCache()
    {
        Cache::forget(self::CACHE_KEY);

        if (self::query()->doesntExist()) {
            return;
        }
        // Clear individual identifier caches
        $identifiers = self::pluck('section_identifier');
        foreach ($identifiers as $identifier) {
            Cache::forget("call_action_{$identifier}");
        }
    }

    /**
     * Scope for active call actions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered call actions
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at');
    }

    /**
     * Get the target attribute for buttons
     */
    public function getPrimaryButtonTargetAttribute()
    {
        return $this->primary_button_external ? '_blank' : '_self';
    }

    public function getSecondaryButtonTargetAttribute()
    {
        return $this->secondary_button_external ? '_blank' : '_self';
    }

    /**
     * Check if the call action has primary button
     */
    public function hasPrimaryButton()
    {
        return ! empty($this->primary_button_text) && ! empty($this->primary_button_url);
    }

    /**
     * Check if the call action has secondary button
     */
    public function hasSecondaryButton()
    {
        return ! empty($this->secondary_button_text) && ! empty($this->secondary_button_url);
    }
}

