<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * @mixin IdeHelperProductFeature
 */
class ProductFeature extends BaseModel
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'category',
        'benefits',
        'is_premium',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'benefits' => 'array',
        'is_premium' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Cache configuration
     */
    const CACHE_KEY = 'product_features';

    const CACHE_DURATION = 3600; // 1 hour

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($feature) {
            if (empty($feature->slug)) {
                $feature->slug = Str::slug($feature->name);
            }
        });

        static::created(fn () => self::clearCache());
        static::updated(fn () => self::clearCache());
        static::deleted(fn () => self::clearCache());
    }

    /**
     * Get cached features
     */
    public static function getCached()
    {
        return self::cacheRememberManyRows(
            self::CACHE_KEY.'_rows_v1',
            self::CACHE_DURATION,
            fn () => self::active()->ordered()->get(),
            [self::CACHE_KEY],
        );
    }

    /**
     * Get features by category
     */
    public static function getByCategory(string $category)
    {
        $legacyKey = self::CACHE_KEY."_category_{$category}";

        return self::cacheRememberManyRows(
            self::CACHE_KEY."_category_{$category}_rows_v1",
            self::CACHE_DURATION,
            fn () => self::active()->where('category', $category)->ordered()->get(),
            [$legacyKey],
        );
    }

    /**
     * Clear cache
     */
    public static function clearCache()
    {
        Cache::flush(); // Clear all cache with product_features prefix
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function scopePremium($query)
    {
        return $query->where('is_premium', true);
    }

    public function scopeFree($query)
    {
        return $query->where('is_premium', false);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Relationships
     */
    public function helpArticles()
    {
        return $this->belongsToMany(HelpArticle::class, 'help_article_product_feature');
    }

    public function caseStudies()
    {
        return $this->belongsToMany(CaseStudy::class, 'case_study_product_feature');
    }

    /**
     * Get all categories
     */
    public static function getCategories()
    {
        return Cache::remember('product_feature_categories', self::CACHE_DURATION, function () {
            return self::active()->distinct()->pluck('category')->filter()->sort()->values();
        });
    }

    /**
     * Check if feature has benefits
     */
    public function hasBenefits(): bool
    {
        return ! empty($this->benefits);
    }

    /**
     * Get benefits as formatted list
     */
    public function getBenefitsListAttribute()
    {
        return $this->hasBenefits() ? implode(', ', $this->benefits) : '';
    }
}
