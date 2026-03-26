<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;

/**
 * @mixin IdeHelperExternalCode
 */
class ExternalCode extends BaseModel
{
    const CACHE_KEY = 'external_codes';

    public const string NESTED_ROWS_CACHE_KEY = 'external_codes_nested_rows_v1';

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
        static::created(fn () => self::forgetExternalCodeCache());
        static::updated(fn () => self::forgetExternalCodeCache());
        static::deleted(fn () => self::forgetExternalCodeCache());
    }

    public static function forgetExternalCodeCache(): void
    {
        Cache::forget(self::CACHE_KEY);
        Cache::forget(self::NESTED_ROWS_CACHE_KEY);
    }

    public static function getCached()
    {
        $data = Cache::get(self::NESTED_ROWS_CACHE_KEY);
        if (! self::nestedRowPayloadIsValid($data)) {
            self::forgetExternalCodeCache();
            $data = Cache::remember(self::NESTED_ROWS_CACHE_KEY, 60 * 60, function () {
                return [
                    'header' => self::query()
                        ->active()
                        ->beforeHeader()
                        ->ordered()
                        ->get()
                        ->map->getAttributes()
                        ->values()
                        ->all(),
                    'body' => self::query()
                        ->active()
                        ->beforeBody()
                        ->ordered()
                        ->get()
                        ->map->getAttributes()
                        ->values()
                        ->all(),
                ];
            });
        }

        if (! self::nestedRowPayloadIsValid($data)) {
            return [
                'header' => self::hydrate([]),
                'body' => self::hydrate([]),
            ];
        }

        return [
            'header' => self::hydrate($data['header']),
            'body' => self::hydrate($data['body']),
        ];
    }

    /**
     * @param  mixed  $data
     */
    protected static function nestedRowPayloadIsValid($data): bool
    {
        if (! is_array($data) || ! isset($data['header'], $data['body'])) {
            return false;
        }

        return self::cachePayloadIsListOfRowArrays($data['header'])
            && self::cachePayloadIsListOfRowArrays($data['body']);
    }
}
