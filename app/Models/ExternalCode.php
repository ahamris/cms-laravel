<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;

/**
 * @mixin IdeHelperExternalCode
 */
class ExternalCode extends BaseModel
{
    const CACHE_KEY = 'external_codes';

    protected $fillable = [ 
        'name',
        'content',
        'before_header',
        'before_body',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'before_header' => 'boolean',
            'before_body' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Scope for active external codes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered external codes by sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Scope for codes that should be inserted before header
     */
    public function scopeBeforeHeader($query)
    {
        return $query->where('before_header', true);
    }

    /**
     * Scope for codes that should be inserted before body
     */
    public function scopeBeforeBody($query)
    {
        return $query->where('before_body', true);
    }

    /**
     * Get external codes for header insertion
     */
    public static function getHeaderCodes()
    {
        $cached = self::getCached();
        return $cached['header']->pluck('content')->implode("\n");
    }

    /**
     * Get external codes for body insertion
     */
    public static function getBodyCodes()
    {
        $cached = self::getCached();
        return $cached['body']->pluck('content')->implode("\n");
    }

    /**
     * Check if there are any active header codes
     */
    public static function hasHeaderCodes()
    {
        $cached = self::getCached();
        return $cached['header']->isNotEmpty();
    }

    /**
     * Check if there are any active body codes
     */
    public static function hasBodyCodes()
    {
        $cached = self::getCached();
        return $cached['body']->isNotEmpty();
    }

    /**
     * Get all cached external codes data for advanced usage
     */
    public static function getAllCached()
    {
        return self::getCached();
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
            $cachedData = [
                'header' => self::query()
                    ->active()
                    ->beforeHeader()
                    ->ordered()
                    ->get(),
                'body' => self::query()
                    ->active()
                    ->beforeBody()
                    ->ordered()
                    ->get()
            ];

            return Cache::remember(self::CACHE_KEY, 60 * 60, fn () => $cachedData);
        }

        return Cache::get(self::CACHE_KEY);
    }
}
