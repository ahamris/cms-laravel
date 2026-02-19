<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;

/**
 * @mixin IdeHelperWidgetData
 */
class WidgetData extends BaseModel
{
    protected $table = 'widget_data';

    const CACHE_KEY = 'widget_data';
    const CACHE_DURATION = 86400; // 24 hours

    protected $fillable = [
        'widget_type',
        'name',
        'identifier',
        'data',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'data' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Boot method to handle cache invalidation
     */
    protected static function boot()
    {
        parent::boot();

        // Clear cache when model is created, updated, or deleted
        static::created(fn () => self::clearCache());
        static::saved(fn () => self::clearCache());
        static::deleted(fn () => self::clearCache());
    }

    /**
     * Scope for active widget data
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered widget data
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at');
    }

    /**
     * Scope for specific widget type
     */
    public function scopeOfType($query, string $widgetType)
    {
        return $query->where('widget_type', $widgetType);
    }

    /**
     * Get widget data by type with caching
     */
    public static function getByType(string $widgetType)
    {
        $cacheKey = self::CACHE_KEY."_{$widgetType}";

        return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($widgetType) {
            return self::where('widget_type', $widgetType)
                ->where('is_active', true)
                ->ordered()
                ->get();
        });
    }

    /**
     * Get widget data by identifier with caching
     */
    public static function getByIdentifier(string $identifier, ?string $widgetType = null)
    {
        $cacheKey = self::CACHE_KEY."_identifier_{$identifier}";
        if ($widgetType) {
            $cacheKey .= "_{$widgetType}";
        }

        return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($identifier, $widgetType) {
            $query = self::where('identifier', $identifier)
                ->where('is_active', true);

            if ($widgetType) {
                $query->where('widget_type', $widgetType);
            }

            return $query->first();
        });
    }

    /**
     * Get widget data by ID with type check
     */
    public static function getById(int $id, ?string $widgetType = null)
    {
        $cacheKey = self::CACHE_KEY."_id_{$id}";
        if ($widgetType) {
            $cacheKey .= "_{$widgetType}";
        }

        return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($id, $widgetType) {
            $query = self::where('id', $id)
                ->where('is_active', true);

            if ($widgetType) {
                $query->where('widget_type', $widgetType);
            }

            return $query->first();
        });
    }

    /**
     * Clear all widget data caches
     */
    public static function clearCache()
    {
        Cache::forget(self::CACHE_KEY);

        if (self::query()->doesntExist()) {
            return;
        }

        // Clear type-specific caches
        $types = self::distinct()->pluck('widget_type');
        foreach ($types as $type) {
            Cache::forget(self::CACHE_KEY."_{$type}");
        }

        // Clear identifier caches
        $identifiers = self::whereNotNull('identifier')->distinct()->pluck('identifier');
        foreach ($identifiers as $identifier) {
            Cache::forget(self::CACHE_KEY."_identifier_{$identifier}");
        }

        // Clear ID caches (limited to recent ones)
        $ids = self::orderBy('id', 'desc')->limit(100)->pluck('id');
        foreach ($ids as $id) {
            Cache::forget(self::CACHE_KEY."_id_{$id}");
        }
    }

    /**
     * Get all cached widget data
     */
    public static function getCached()
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_DURATION, function () {
            return self::active()->ordered()->get();
        });
    }
}
