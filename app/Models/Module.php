<?php

namespace App\Models;

use App\Models\Traits\ClearsSitemapCache;
use App\Models\Traits\ImageGetterTrait;
use App\Models\Traits\MegaMenuModuleTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Facades\Cache;

/**
 * @mixin IdeHelperModule
 */
class Module extends BaseModel
{
    use ClearsSitemapCache, ImageGetterTrait, MegaMenuModuleTrait, Sluggable;

    const CACHE_KEY = 'modules';

    protected $fillable = [
        'feature_id',
        'title',
        'short_body',
        'long_body',
        'slug',
        'sort_order',
        'is_active',
        'meta_title',
        'meta_description',
        'meta_keywords',
        // Solution-specific fields
        'anchor',
        'nav_title',
        'subtitle',
        'list_items',
        'link_text',
        'link_url',
        'testimonial_quote',
        'testimonial_author',
        'testimonial_company',
        'image',
        'image_position',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'list_items' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get all social media posts for this module
     */
    public function socialMediaPosts()
    {
        return $this->morphMany(SocialMediaPost::class, 'postable');
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
                'maxLength' => 255,
                'separator' => '-',
                'includeTrashed' => true,
            ],
        ];
    }

    /**
     * Scope for active modules
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered modules by sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Feature this module belongs to.
     */
    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }

    /**
     * API path for this module (headless; frontend uses this to fetch or build URL).
     */
    public function getLinkUrlAttribute(): string
    {
        $slug = $this->slug ?? $this->anchor ?? '';

        return $slug !== '' ? api_path('module', $slug) : api_path('modules');
    }

    public static function forgetModuleCache(): void
    {
        Cache::forget(self::CACHE_KEY);
        Cache::forget(self::CACHE_KEY.'_rows_v1');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(fn () => self::forgetModuleCache());
        static::updated(fn () => self::forgetModuleCache());
        static::deleted(fn () => self::forgetModuleCache());
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
        );
    }
}
