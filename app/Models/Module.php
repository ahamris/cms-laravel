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
    use ImageGetterTrait, MegaMenuModuleTrait, Sluggable, ClearsSitemapCache;

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
        return $this->morphMany(\App\Models\SocialMediaPost::class, 'postable');
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

    protected static function boot()
    {
        parent::boot();

        // Clear cache on model events
        static::created(fn () => Cache::forget(self::CACHE_KEY));
        static::updated(fn () => Cache::forget(self::CACHE_KEY));
        static::deleted(fn () => Cache::forget(self::CACHE_KEY));
    }

    public static function getCached()
    {
        if (! Cache::has(self::CACHE_KEY)) {
            return Cache::remember(self::CACHE_KEY, 60 * 60,
                fn () => self::query()
                    ->where('is_active', true)
                    ->ordered()
                    ->get()
            );
        }

        return Cache::get(self::CACHE_KEY);
    }
}
