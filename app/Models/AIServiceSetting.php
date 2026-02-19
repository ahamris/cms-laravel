<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;

/**
 * @mixin IdeHelperAIServiceSetting
 */
class AIServiceSetting extends BaseModel
{
    protected $table = 'ai_service_settings';
    
    protected $fillable = [
        'service',
        'api_key',
        'model',
        'is_active',
        'priority',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'priority' => 'integer',
    ];

    /**
     * Get settings for a specific service
     */
    public static function getForService(string $service): ?self
    {
        return Cache::remember("ai_service_setting_{$service}", 3600, function () use ($service) {
            return self::where('service', $service)->first();
        });
    }

    /**
     * Get active services ordered by priority
     */
    public static function getActiveServices(): \Illuminate\Database\Eloquent\Collection
    {
        return Cache::remember('ai_service_settings_active', 3600, function () {
            return self::where('is_active', true)
                ->orderBy('priority')
                ->get();
        });
    }

    /**
     * Check if a service is configured and active
     */
    public static function isServiceActive(string $service): bool
    {
        $setting = self::getForService($service);
        return $setting && $setting->is_active && !empty($setting->api_key);
    }

    /**
     * Get API key for a service
     */
    public static function getApiKey(string $service): ?string
    {
        $setting = self::getForService($service);
        return $setting && $setting->is_active ? $setting->api_key : null;
    }

    /**
     * Get model for a service
     */
    public static function getModel(string $service, string $default = null): ?string
    {
        $setting = self::getForService($service);
        return $setting && $setting->is_active ? ($setting->model !== 'default' ? $setting->model : $default) : $default;
    }

    /**
     * Clear cache when settings are updated
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($setting) {
            Cache::forget("ai_service_setting_{$setting->service}");
            Cache::forget('ai_service_settings_active');
        });

        static::deleted(function ($setting) {
            Cache::forget("ai_service_setting_{$setting->service}");
            Cache::forget('ai_service_settings_active');
        });
    }
}
