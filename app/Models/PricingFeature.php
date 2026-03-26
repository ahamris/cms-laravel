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

    public static function forgetPricingFeatureCache(): void
    {
        Cache::forget(self::CACHE_KEY);
        Cache::forget(self::CACHE_KEY.'_rows_v1');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(fn () => self::forgetPricingFeatureCache());
        static::updated(fn () => self::forgetPricingFeatureCache());
        static::deleted(fn () => self::forgetPricingFeatureCache());
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
        $models = self::cacheRememberManyRows(
            self::CACHE_KEY.'_rows_v1',
            60 * 60,
            fn () => self::query()
                ->active()
                ->ordered()
                ->get(),
            [self::CACHE_KEY],
        );

        return $models->groupBy('category');
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
