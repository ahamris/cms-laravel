<?php

namespace App\Models;

use App\Models\Traits\ImageGetterTrait;
use Illuminate\Support\Facades\Cache;

/**
 * @mixin IdeHelperFeatureBlock
 */
class FeatureBlock extends BaseModel
{
    use ImageGetterTrait;

    const CACHE_KEY = 'feature_blocks';

    protected $fillable = [
        'identifier',
        'section_title',
        'section_subtitle',
        'items',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'items' => 'array',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Scope for active feature blocks
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered feature blocks
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Get all cached feature blocks
     */
    public static function getCached()
    {
        return Cache::remember(self::CACHE_KEY, 86400, function () {
            return self::active()->ordered()->get();
        });
    }

    /**
     * Get feature block by identifier
     */
    public static function getByIdentifier($identifier)
    {
        return Cache::remember(self::CACHE_KEY."_{$identifier}", 86400, function () use ($identifier) {
            return self::where('identifier', $identifier)
                ->where('is_active', true)
                ->first();
        });
    }

    /**
     * Clear cache
     */
    public static function clearCache()
    {
        Cache::forget(self::CACHE_KEY);

        // Clear individual identifier caches
        $identifiers = self::pluck('identifier');
        foreach ($identifiers as $identifier) {
            Cache::forget(self::CACHE_KEY."_{$identifier}");
        }
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            self::clearCache();
        });

        static::deleted(function () {
            self::clearCache();
        });
    }
}

