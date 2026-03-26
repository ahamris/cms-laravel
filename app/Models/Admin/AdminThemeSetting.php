<?php

namespace App\Models\Admin;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

/**
 * @mixin IdeHelperAdminThemeSetting
 */
class AdminThemeSetting extends BaseModel
{
    use HasFactory;

    public const string ROW_CACHE_KEY = 'admin_theme_settings_row_v1';

    protected $fillable = [
        'base_color',
        'accent_color',
    ];

    /**
     * In-memory cache for the current request to prevent duplicate cache queries.
     */
    protected static ?self $cachedInstance = null;

    public static function getSettings(): self
    {
        // Return cached instance if already loaded in this request
        if (static::$cachedInstance !== null) {
            return static::$cachedInstance;
        }

        static::$cachedInstance = static::cacheRememberModelRow(
            self::ROW_CACHE_KEY,
            3600,
            fn () => static::firstOrCreate(
                ['id' => 1],
                [
                    'base_color' => 'zinc',
                    'accent_color' => 'indigo',
                ]
            ),
            ['admin_theme_settings'],
        );

        return static::$cachedInstance;
    }

    /**
     * Clear cache when settings are updated.
     */
    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('admin_theme_settings');
            Cache::forget(self::ROW_CACHE_KEY);
            static::$cachedInstance = null;
        });

        static::deleted(function () {
            Cache::forget('admin_theme_settings');
            Cache::forget(self::ROW_CACHE_KEY);
            static::$cachedInstance = null;
        });
    }

    public function getBaseColorAttribute(string $value): string
    {
        return $value ?: 'zinc';
    }

    public function getAccentColorAttribute(string $value): string
    {
        return $value ?: 'indigo';
    }
}
