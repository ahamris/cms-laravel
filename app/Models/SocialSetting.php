<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;

/**
 * @mixin IdeHelperSocialSetting
 */
class SocialSetting extends BaseModel
{
    const CACHE_KEY = 'social_settings';

    protected $table = 'social_settings';

    protected $fillable = [
        'name',
        'url',
        'icon',
    ];

    public $timestamps = true;

    protected static function boot()
    {
        parent::boot();
        static::created(fn () => self::forgetSocialCache());
        static::updated(fn () => self::forgetSocialCache());
        static::deleted(fn () => self::forgetSocialCache());
    }

    public static function forgetSocialCache(): void
    {
        Cache::forget(self::CACHE_KEY);
        Cache::forget(self::CACHE_KEY.'_rows_v1');
    }

    public static function getCached()
    {
        return self::cacheRememberManyRows(
            self::CACHE_KEY.'_rows_v1',
            60 * 60,
            fn () => self::query()->get(),
            [self::CACHE_KEY],
        );
    }
}
