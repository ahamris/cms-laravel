<?php

namespace App\Models;

use App\Helpers\Variable;
use Illuminate\Support\Facades\Cache;

/**
 * @mixin IdeHelperFooterLink
 */
class FooterLink extends BaseModel
{
    protected $fillable = [
        'title',
        'url',
        'column',
        'order',
        'is_active',
    ];

    const CACHE_KEY = 'footer_links';

    protected static function boot()
    {
        parent::boot();
        static::created(fn () => Cache::forget(self::CACHE_KEY));
        static::updated(fn () => Cache::forget(self::CACHE_KEY));
        static::deleted(fn () => Cache::forget(self::CACHE_KEY));
    }

    public static function getCached()
    {
        if (! Cache::has(self::CACHE_KEY)) {
            return Cache::remember(self::CACHE_KEY, Variable::CACHE_TTL,
                fn () => self::query()
                    ->where('is_active', true)
                    ->orderBy('column')
                    ->orderBy('order')
                    ->get()
                    ->groupBy('column')
            );
        }

        return Cache::get(self::CACHE_KEY);
    }
}
