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
        static::created(fn () => Cache::forget(self::CACHE_KEY));
        static::updated(fn () => Cache::forget(self::CACHE_KEY));
        static::deleted(fn () => Cache::forget(self::CACHE_KEY));
    }

    public static function getCached()
    {
        if (! Cache::has(self::CACHE_KEY)) {
            return Cache::remember(self::CACHE_KEY, 60 * 60,
                fn () => self::query()->get()
            );
        }

        return Cache::get(self::CACHE_KEY);
    }
}
