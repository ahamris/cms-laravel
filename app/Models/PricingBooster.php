<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Facades\Cache;

/**
 * @mixin IdeHelperPricingBooster
 */
class PricingBooster extends BaseModel
{
    use Sluggable;

    const CACHE_KEY = 'pricing_boosters';

    protected $fillable = [
        'name',
        'slug',
        'price',
        'description',
        'link_text',
        'link_url',
        'footnote',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public static function forgetPricingBoosterCache(): void
    {
        Cache::forget(self::CACHE_KEY);
        Cache::forget(self::CACHE_KEY.'_rows_v1');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(fn () => self::forgetPricingBoosterCache());
        static::updated(fn () => self::forgetPricingBoosterCache());
        static::deleted(fn () => self::forgetPricingBoosterCache());
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
     * Scope for active boosters
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered boosters by sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Get cached active boosters
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
}
