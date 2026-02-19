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
    use ImageGetterTrait, MegaMenuModuleTrait, Sluggable, ClearsSitemapCache;

    const CACHE_KEY = 'solutions';

    protected $fillable = [
        'anchor',
        'nav_title',
        'title',
        'subtitle',
        'short_body',
        'long_body',
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
        // Header configuration
        'button1_text',
        'button1_url',
        'button2_text',
        'button2_url',
        'show_buttons',
        // Module activation
        'show_cta',
        'show_news_articles',
        'show_modules_header',
    ];

    protected function casts(): array
    {
        return [
            'list_items' => 'array',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            // Header configuration
            'show_buttons' => 'boolean',
            // Module activation
            'show_news_articles' => 'boolean',
            'show_cta' => 'boolean',
            'show_modules_header' => 'boolean',
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
     * Many-to-many relationship with modules
     */
    public function modules()
    {
        return $this->belongsToMany(Module::class, 'module_solution');
    }

    /**
     * Get full anchor URL
     */
    public function getAnchorUrlAttribute(): string
    {
        return '#'.$this->anchor;
    }

    /**
     * Get link URL based on anchor
     */
    public function getLinkUrlAttribute(): string
    {
        return route('solutions.show', ['solution' => $this->anchor]);
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
                    ->with('modules')
                    ->where('is_active', true)
                    ->ordered()
                    ->get()
            );
        }

        return Cache::get(self::CACHE_KEY);
    }
}
