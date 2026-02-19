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

    protected static function boot()
    {
        parent::boot();

        // Clear cache on model events
        static::created(fn () => Cache::forget(self::CACHE_KEY));
        static::updated(fn () => Cache::forget(self::CACHE_KEY));
        static::deleted(fn () => Cache::forget(self::CACHE_KEY));

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
        if (! Cache::has(self::CACHE_KEY)) {
            return Cache::remember(self::CACHE_KEY, 60 * 60,
                fn () => self::query()
                    ->active()
                    ->ordered()
                    ->get()
            );
        }

        return Cache::get(self::CACHE_KEY);
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        return '€ '.number_format($this->price, 2);
    }
}
