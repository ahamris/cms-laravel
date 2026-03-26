<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Facades\Cache;

/**
 * @mixin IdeHelperPricingPlan
 */
class PricingPlan extends BaseModel
{
    const CACHE_KEY = 'pricing_plans';

    use Sluggable;

    protected $fillable = [
        'name',
        'slug',
        'organization_category',
        'organization_category_description',
        'price',
        'discounted_price',
        'discount_percentage',
        'description',
        'features',
        'button_text',
        'button_url',
        'footnote',
        'sort_order',
        'is_active',
        'is_popular',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discounted_price' => 'decimal:2',
        'discount_percentage' => 'integer',
        'features' => 'array',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
        'is_popular' => 'boolean',
    ];

    public static function forgetPricingPlanCache(): void
    {
        Cache::forget(self::CACHE_KEY);
        Cache::forget(self::CACHE_KEY.'_rows_v1');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(fn () => self::forgetPricingPlanCache());
        static::updated(fn () => self::forgetPricingPlanCache());
        static::deleted(fn () => self::forgetPricingPlanCache());
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
                'maxLength' => 255,
                'separator' => '-',
                'includeTrashed' => true,
            ],
        ];
    }

    /**
     * Scope for active plans
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered plans by sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Get cached active plans
     */
    public static function getCached()
    {
        return self::cacheRememberManyRows(
            self::CACHE_KEY.'_rows_v1',
            60 * 60,
            fn () => self::query()
                ->active()
                ->ordered()
                ->get(),
            [self::CACHE_KEY],
        );
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        return '€ '.number_format($this->price, 2);
    }

    /**
     * Get formatted discounted price
     */
    public function getFormattedDiscountedPriceAttribute(): string
    {
        if ($this->discounted_price) {
            return '€ '.number_format($this->discounted_price, 2);
        }

        return '';
    }
}
