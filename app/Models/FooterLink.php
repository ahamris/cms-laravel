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
        static::created(fn () => self::forgetFooterCache());
        static::updated(fn () => self::forgetFooterCache());
        static::deleted(fn () => self::forgetFooterCache());
    }

    public static function forgetFooterCache(): void
    {
        Cache::forget(self::CACHE_KEY);
        Cache::forget(self::CACHE_KEY.'_rows_v1');
    }

    public static function getCached()
    {
        $models = self::cacheRememberManyRows(
            self::CACHE_KEY.'_rows_v1',
            Variable::CACHE_TTL,
            fn () => self::query()
                ->where('is_active', true)
                ->orderBy('column')
                ->orderBy('order')
                ->get(),
            [self::CACHE_KEY],
        );

        return $models->groupBy('column');
    }
}
