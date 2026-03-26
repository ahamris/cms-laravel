<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * @mixin IdeHelperCaseStudy
 */
class CaseStudy extends BaseModel
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'client_name',
        'client_company',
        'client_industry',
        'client_logo',
        'challenge',
        'solution',
        'results',
        'metrics',
        'key_quote',
        'featured_image',
        'product_feature_ids',
        'tags',
        'is_featured',
        'is_published',
        'sort_order',
        'published_at',
    ];

    protected $casts = [
        'metrics' => 'array',
        'product_feature_ids' => 'array',
        'tags' => 'array',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'sort_order' => 'integer',
        'published_at' => 'datetime',
    ];

    /**
     * Cache configuration
     */
    const CACHE_KEY = 'case_studies';

    const CACHE_DURATION = 3600; // 1 hour

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($caseStudy) {
            if (empty($caseStudy->slug)) {
                $caseStudy->slug = Str::slug($caseStudy->title);
            }
            if (empty($caseStudy->published_at) && $caseStudy->is_published) {
                $caseStudy->published_at = now();
            }
        });

        static::created(fn () => self::clearCache());
        static::updated(fn () => self::clearCache());
        static::deleted(fn () => self::clearCache());
    }

    /**
     * Get cached case studies
     */
    public static function getCached()
    {
        return self::cacheRememberManyRows(
            self::CACHE_KEY.'_rows_v1',
            self::CACHE_DURATION,
            fn () => self::published()->ordered()->get(),
            [self::CACHE_KEY],
        );
    }

    /**
     * Get featured case studies
     */
    public static function getFeatured()
    {
        return self::cacheRememberManyRows(
            self::CACHE_KEY.'_featured_rows_v1',
            self::CACHE_DURATION,
            fn () => self::published()->featured()->ordered()->limit(3)->get(),
            [self::CACHE_KEY.'_featured'],
        );
    }

    /**
     * Clear cache
     */
    public static function clearCache()
    {
        Cache::forget(self::CACHE_KEY);
        Cache::forget(self::CACHE_KEY.'_rows_v1');
        Cache::forget(self::CACHE_KEY.'_featured');
        Cache::forget(self::CACHE_KEY.'_featured_rows_v1');
    }

    /**
     * Scopes
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)->whereNotNull('published_at');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('published_at', 'desc');
    }

    public function scopeByIndustry($query, string $industry)
    {
        return $query->where('client_industry', $industry);
    }

    public function scopeByProductFeature($query, int $featureId)
    {
        return $query->whereJsonContains('product_feature_ids', $featureId);
    }

    /**
     * Relationships
     */
    public function productFeatures()
    {
        if (empty($this->product_feature_ids)) {
            return collect();
        }

        return ProductFeature::whereIn('id', $this->product_feature_ids)->get();
    }

    /**
     * Get all industries
     */
    public static function getIndustries()
    {
        return Cache::remember('case_study_industries', self::CACHE_DURATION, function () {
            return self::published()->distinct()->pluck('client_industry')->filter()->sort()->values();
        });
    }

    /**
     * Get formatted metrics
     */
    public function getFormattedMetricsAttribute()
    {
        if (empty($this->metrics)) {
            return [];
        }

        return collect($this->metrics)->map(function ($value, $key) {
            return [
                'label' => ucfirst(str_replace('_', ' ', $key)),
                'value' => $value,
            ];
        })->values();
    }

    /**
     * Check if case study has metrics
     */
    public function hasMetrics(): bool
    {
        return ! empty($this->metrics);
    }

    /**
     * Check if case study has client logo
     */
    public function hasClientLogo(): bool
    {
        return ! empty($this->client_logo);
    }

    /**
     * Check if case study has featured image
     */
    public function hasFeaturedImage(): bool
    {
        return ! empty($this->featured_image);
    }

    /**
     * Get client full name
     */
    public function getClientFullNameAttribute()
    {
        return trim($this->client_name.' - '.$this->client_company, ' -');
    }
}
