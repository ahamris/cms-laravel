<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;

/**
 * @mixin IdeHelperMarketingTestimonial
 */
class MarketingTestimonial extends BaseModel
{
    protected $fillable = [
        'customer_name',
        'company',
        'position',
        'quote',
        'photo',
        'company_logo',
        'rating',
        'tags',
        'featured',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'tags' => 'array',
        'featured' => 'boolean',
        'is_active' => 'boolean',
        'rating' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Cache key for testimonials
     */
    const CACHE_KEY = 'marketing_testimonials';

    const CACHE_DURATION = 3600; // 1 hour

    /**
     * Boot method for cache invalidation
     */
    protected static function boot()
    {
        parent::boot();

        static::created(fn () => self::clearCache());
        static::updated(fn () => self::clearCache());
        static::deleted(fn () => self::clearCache());
    }

    /**
     * Get cached testimonials
     */
    public static function getCached()
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_DURATION, function () {
            return self::active()->ordered()->get();
        });
    }

    /**
     * Get featured testimonials
     */
    public static function getFeatured()
    {
        return Cache::remember(self::CACHE_KEY.'_featured', self::CACHE_DURATION, function () {
            return self::active()->featured()->ordered()->get();
        });
    }

    /**
     * Clear testimonials cache
     */
    public static function clearCache()
    {
        Cache::forget(self::CACHE_KEY);
        Cache::forget(self::CACHE_KEY.'_featured');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at', 'desc');
    }

    public function scopeWithRating($query, $minRating = 4)
    {
        return $query->where('rating', '>=', $minRating);
    }

    /**
     * Accessors
     */
    public function getStarsAttribute()
    {
        return str_repeat('★', $this->rating ?? 0).str_repeat('☆', 5 - ($this->rating ?? 0));
    }

    public function getExcerptAttribute()
    {
        return strlen($this->quote) > 100 ? substr($this->quote, 0, 100).'...' : $this->quote;
    }

    /**
     * Check if testimonial has a photo
     */
    public function hasPhoto(): bool
    {
        return ! empty($this->photo);
    }

    /**
     * Check if testimonial has company logo
     */
    public function hasCompanyLogo(): bool
    {
        return ! empty($this->company_logo);
    }
}
