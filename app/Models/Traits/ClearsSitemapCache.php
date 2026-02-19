<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Cache;

trait ClearsSitemapCache
{
    /**
     * Boot the trait and register model events
     */
    protected static function bootClearsSitemapCache(): void
    {
        static::created(fn() => Cache::forget('sitemap_xml'));
        static::updated(fn() => Cache::forget('sitemap_xml'));
        static::deleted(fn() => Cache::forget('sitemap_xml'));
    }
}
