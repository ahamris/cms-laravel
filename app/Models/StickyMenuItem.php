<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

/**
 * @mixin IdeHelperStickyMenuItem
 */
class StickyMenuItem extends BaseModel
{
    use HasFactory;

    public const string ROWS_CACHE_KEY = 'sticky_menu_items_rows_v1';

    protected $fillable = [
        'title',
        'icon',
        'link',
        'link_type',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the is_external attribute (computed from link_type).
     */
    public function getIsExternalAttribute(): bool
    {
        return $this->link_type === 'external';
    }

    /**
     * Scope to get only active items.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order items by sort_order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    /**
     * Get all active sticky menu items with caching.
     */
    public static function getActiveItems()
    {
        return self::cacheRememberManyRows(
            self::ROWS_CACHE_KEY,
            3600,
            fn () => self::active()->ordered()->get(),
            ['sticky_menu_items'],
        );
    }

    /**
     * Clear the cache when model is saved or deleted.
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            Cache::forget('sticky_menu_items');
            Cache::forget(self::ROWS_CACHE_KEY);
        });

        static::deleted(function () {
            Cache::forget('sticky_menu_items');
            Cache::forget(self::ROWS_CACHE_KEY);
        });
    }

    /**
     * Get the target attribute for links.
     */
    public function getTargetAttribute()
    {
        return $this->link_type === 'external' ? '_blank' : '_self';
    }

    /**
     * Get the rel attribute for external links.
     */
    public function getRelAttribute()
    {
        return $this->link_type === 'external' ? 'noopener noreferrer' : null;
    }

    /**
     * Check if the link is external.
     */
    public function isExternal()
    {
        return $this->link_type === 'external';
    }

    /**
     * Check if the link is internal.
     */
    public function isInternal()
    {
        return $this->link_type === 'internal';
    }
}
