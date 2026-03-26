<?php

namespace App\Models;

use App\Models\Traits\ClearsSitemapCache;
use App\Models\Traits\ImageGetterTrait;
use App\Models\Traits\MegaMenuModuleTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Facades\Cache;

/**
 * @mixin IdeHelperSolution
 */
class Solution extends BaseModel
{
    use ClearsSitemapCache, ImageGetterTrait, MegaMenuModuleTrait, Sluggable;

    const CACHE_KEY = 'solutions';

    protected $fillable = [
        'anchor',
        'nav_title',
        'title',
        'subtitle',
        'short_body',
        'long_body',
        'faq',
        'list_items',
        'link_text',
        'link_url',
        'testimonial_quote',
        'testimonial_author',
        'testimonial_company',
        'image',
        'image_position',
        'sort_order',
        'is_active',
        'slug',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected function casts(): array
    {
        return [
            'list_items' => 'array',
            'faq' => 'array',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
                'maxLength' => 255,
                'separator' => '-',
                'includeTrashed' => true,
                'reserved' => ['admin', 'api', 'www'],
            ],
        ];
    }

    /**
     * Scope for active solutions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered solutions by sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Features belonging to this solution.
     */
    public function features()
    {
        return $this->hasMany(Feature::class);
    }

    /**
     * Get full anchor URL
     */
    public function getAnchorUrlAttribute(): string
    {
        return '#'.$this->anchor;
    }

    /**
     * API path for this solution (headless; frontend uses this to fetch or build URL).
     */
    public function getLinkUrlAttribute(): string
    {
        $anchor = $this->anchor ?? '';

        return $anchor !== '' ? api_path('solution', $anchor) : api_path('solutions');
    }

    /**
     * Set the slug attribute with proper cleaning
     */
    public function setSlugAttribute($value)
    {
        if (! empty($value)) {
            // Clean the slug using the sluggable package helper
            $this->attributes['slug'] = \Str::slug($value, '-');
        } else {
            // Let the sluggable trait handle auto-generation
            $this->attributes['slug'] = null;
        }
    }

    /**
     * Set the anchor attribute with proper cleaning
     */
    public function setAnchorAttribute($value)
    {
        if (! empty($value)) {
            // Clean the anchor using the slug helper (no special chars for anchors)
            $this->attributes['anchor'] = \Str::slug($value, '-');
        }
    }

    public static function forgetSolutionCache(): void
    {
        Cache::forget(self::CACHE_KEY);
        Cache::forget(self::CACHE_KEY.'_rows_v1');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(fn () => self::forgetSolutionCache());
        static::updated(fn () => self::forgetSolutionCache());
        static::deleted(fn () => self::forgetSolutionCache());

    }

    public static function getCached()
    {
        return self::cacheRememberManyRows(
            self::CACHE_KEY.'_rows_v1',
            60 * 60,
            fn () => self::query()
                ->where('is_active', true)
                ->ordered()
                ->get(),
            [self::CACHE_KEY],
            'features',
        );
    }
}
