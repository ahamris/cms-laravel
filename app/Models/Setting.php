<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin IdeHelperSetting
 */
class Setting extends BaseModel
{
    use SoftDeletes;

    /** Aggregate cache for getCached(); stores key => row array only (not Eloquent), for Laravel cache without serializable classes. */
    public const string AGGREGATE_CACHE_KEY = 'settings_by_key_v1';

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
            $setting = self::where('key', $key)->first();

            return $setting?->value ?? $default;
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
            self::forgetAggregateCache();
        });

        static::deleted(function ($setting) {
            Cache::forget("settings.{$setting->key}");
            self::forgetAggregateCache();
        });
    }

    public static function forgetAggregateCache(): void
    {
        Cache::forget('settings');
        Cache::forget(self::AGGREGATE_CACHE_KEY);
    }

    public static function getCached(): Collection
    {
        $payload = Cache::get(self::AGGREGATE_CACHE_KEY);

        if (! self::isValidAggregatePayload($payload)) {
            self::forgetAggregateCache();
            $payload = Cache::remember(self::AGGREGATE_CACHE_KEY, 60 * 60, fn () => self::buildAggregateCachePayload());
        }

        return collect($payload)->map(fn (array $attrs) => (new self)->newFromBuilder($attrs));
    }

    /**
     * @param  mixed  $payload
     */
    protected static function isValidAggregatePayload($payload): bool
    {
        if (! is_array($payload)) {
            return false;
        }

        foreach ($payload as $k => $row) {
            if (! is_string($k) || ! is_array($row)) {
                return false;
            }
        }

        return true;
    }

    protected static function buildAggregateCachePayload(): array
    {
        return self::query()->get()->mapWithKeys(fn (self $setting) => [
            $setting->key => $setting->getAttributes(),
        ])->all();
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
