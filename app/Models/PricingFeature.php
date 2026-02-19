<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;

/**
 * @mixin IdeHelperPricingFeature
 */
class PricingFeature extends BaseModel
{
    const CACHE_KEY = 'pricing_features';

    protected $fillable = [
        'category',
        'name',
        'description',
        'available_in_plans',
        'badge',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'available_in_plans' => 'array',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        // Clear cache on model events
        static::created(fn () => Cache::forget(self::CACHE_KEY));
        static::updated(fn () => Cache::forget(self::CACHE_KEY));
        static::deleted(fn () => Cache::forget(self::CACHE_KEY));
    }

    /**
     * Scope for active features
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered features by sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Scope by category
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get cached features grouped by category
     */
    public static function getCachedGrouped()
    {
        if (! Cache::has(self::CACHE_KEY)) {
            return Cache::remember(self::CACHE_KEY, 60 * 60,
                fn () => self::query()
                    ->active()
                    ->ordered()
                    ->get()
                    ->groupBy('category')
            );
        }

        return Cache::get(self::CACHE_KEY);
    }

    /**
     * Check if feature is available in a specific plan
     */
    public function isAvailableInPlan(string $planSlug): bool
    {
        if (empty($this->available_in_plans)) {
            return false;
        }

        return in_array($planSlug, $this->available_in_plans);
    }
}
