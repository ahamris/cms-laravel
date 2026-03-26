<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
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
        'base_url',
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
        $id = Cache::remember("ai_service_setting_id_{$service}", 3600, function () use ($service) {
            return self::query()->where('service', $service)->value('id');
        });

        return $id ? self::query()->find($id) : null;
    }

    /**
     * Get active services ordered by priority
     */
    public static function getActiveServices(): EloquentCollection
    {
        $ids = Cache::remember('ai_service_settings_active_ids', 3600, function () {
            return self::query()
                ->where('is_active', true)
                ->orderBy('priority')
                ->pluck('id')
                ->all();
        });

        if ($ids === []) {
            return new EloquentCollection;
        }

        $models = self::query()->whereIn('id', $ids)->get();

        return new EloquentCollection(
            $models->sortBy(fn (self $m) => array_search($m->id, $ids, true))->values()->all()
        );
    }

    /**
     * Check if a service is configured and active
     */
    public static function isServiceActive(string $service): bool
    {
        $setting = self::getForService($service);
        if (! $setting || ! $setting->is_active) {
            return false;
        }
        if ($service === 'ollama') {
            return ! empty($setting->base_url);
        }

        return ! empty($setting->api_key);
    }

    /**
     * Get API key for a service (null for Ollama)
     */
    public static function getApiKey(string $service): ?string
    {
        $setting = self::getForService($service);

        return $setting && $setting->is_active ? $setting->api_key : null;
    }

    /**
     * Get base URL for a service (e.g. Ollama: http://localhost:11434)
     */
    public static function getBaseUrl(string $service): ?string
    {
        $setting = self::getForService($service);

        return $setting && $setting->is_active ? ($setting->base_url ?? null) : null;
    }

    /**
     * Get model for a service
     */
    public static function getModel(string $service, ?string $default = null): ?string
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
            Cache::forget("ai_service_setting_id_{$setting->service}");
            Cache::forget('ai_service_settings_active');
            Cache::forget('ai_service_settings_active_ids');
        });

        static::deleted(function ($setting) {
            Cache::forget("ai_service_setting_{$setting->service}");
            Cache::forget("ai_service_setting_id_{$setting->service}");
            Cache::forget('ai_service_settings_active');
            Cache::forget('ai_service_settings_active_ids');
        });
    }
}
