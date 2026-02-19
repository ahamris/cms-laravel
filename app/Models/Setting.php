<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin IdeHelperSetting
 */
class Setting extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'display_name',
        'description',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'order' => 'integer',
        ];
    }

    public static function getValue($key, $default = null)
    {
        return Cache::remember("settings.{$key}", 60 * 60, function () use ($key, $default) {
            return self::where('key', $key)->first()->value ?? $default;
        });
    }

    public static function setValue($key, $value)
    {
        $existing = self::where('key', $key)->first();

        if ($existing) {
            // Update existing setting
            $existing->update(['value' => $value]);
            $setting = $existing;
        } else {
            // Create new setting with required fields
            $setting = self::create([
                'key' => $key,
                'value' => $value,
                'type' => 'text',
                'group' => 'general',
                'display_name' => ucfirst(str_replace('_', ' ', $key)),
                'description' => null,
                'order' => 0,
            ]);
        }

        Cache::forget("settings.{$key}");

        return $setting;
    }

    protected static function boot()
    {
        parent::boot();
        static::saved(function ($setting) {
            Cache::forget("settings.{$setting->key}");
        });

        static::deleted(function ($setting) {
            Cache::forget("settings.{$setting->key}");
        });
    }

    public static function getCached()
    {

        if (! Cache::has('settings')) {
            return Cache::remember('settings', 60 * 60, function () {
                return self::query()->get()->keyBy('key');
            });
        }

        return Cache::get('settings');
    }

    /**
     * Get the full URL for a file setting (logo, favicon, etc.)
     */
    public static function getFileUrl($key, $default = null)
    {
        $value = self::getValue($key);

        if (! $value) {
            // If no setting value, return default or formatted app name
            $appName = config('app.name', 'Open Publicaties');
            $appUrl = config('app.url');
            $domain = preg_replace('/^https?:\/\//', '', $appUrl);
            $nameParts = explode('.', $domain);
            $mainName = $nameParts[0] ?? $appName;

            return '<span class="font-bold uppercase tracking-wider">'.ucfirst($mainName).'</span>';
        }

        // If it's already a full URL, return as is
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        // Check if file exists in storage
        if (! Storage::disk('public')->exists($value)) {
            // If file does not exist, return formatted app name
            $appName = config('app.name', 'Open Publicaties');
            $appUrl = config('app.url');
            $domain = preg_replace('/^https?:\/\//', '', $appUrl);
            $nameParts = explode('.', $domain);
            $mainName = $nameParts[0] ?? $appName;

            return '<span class="font-bold uppercase tracking-wider">'.ucfirst($mainName).'</span>';
        }

        // Return the storage URL
        return Storage::disk('public')->url($value);
    }

    /**
     * Get logo URL
     */
    public static function getLogoUrl($default = null)
    {
        return self::getFileUrl('site_logo', $default);
    }

    /**
     * Get favicon URL
     */
    public static function getFaviconUrl($default = null)
    {
        return self::getFileUrl('site_favicon', $default);
    }

    /**
     * Check if a file setting exists and the file is accessible
     */
    public static function hasFile($key)
    {
        $value = self::getValue($key);

        if (! $value) {
            return false;
        }

        return Storage::disk('public')->exists($value);
    }
}
