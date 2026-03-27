<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\Cache;

/**
 * @mixin IdeHelperAIServiceSetting
 */
class AIServiceSetting extends BaseModel
{
    /**
     * @var array<int, string>
     */
    public const array SUPPORTED_SERVICES = [
        'groq',
        'gemini',
        'ollama',
        'openai',
        'anthropic',
    ];

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
        $normalizedService = self::normalizeServiceName($service);
        $id = Cache::remember("ai_service_setting_id_{$normalizedService}", 3600, function () use ($normalizedService) {
            return self::query()->where('service', $normalizedService)->value('id');
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
        $normalizedService = self::normalizeServiceName($service);
        if ($normalizedService === 'ollama') {
            return ! empty($setting->base_url);
        }

        return ! empty($setting->api_key);
    }

    /**
     * Get API key for a service (null for Ollama)
     */
    public static function getApiKey(string $service): ?string
    {
        $normalizedService = self::normalizeServiceName($service);
        $setting = self::getForService($normalizedService);
        if ($setting && $setting->is_active && ! empty($setting->api_key)) {
            return $setting->api_key;
        }

        return match ($normalizedService) {
            'groq' => (string) config('ai.providers.groq.key', ''),
            'gemini' => (string) config('ai.providers.gemini.key', ''),
            'openai' => (string) config('ai.providers.openai.key', ''),
            'anthropic' => (string) config('ai.providers.anthropic.key', ''),
            default => null,
        };
    }

    /**
     * Get base URL for a service (e.g. Ollama: http://localhost:11434)
     */
    public static function getBaseUrl(string $service): ?string
    {
        $normalizedService = self::normalizeServiceName($service);
        $setting = self::getForService($normalizedService);
        if ($setting && $setting->is_active && ! empty($setting->base_url)) {
            return $setting->base_url ?? null;
        }

        if ($normalizedService === 'ollama') {
            return (string) config('ai.providers.ollama.url', '');
        }

        return null;
    }

    /**
     * Get model for a service
     */
    public static function getModel(string $service, ?string $default = null): ?string
    {
        $normalizedService = self::normalizeServiceName($service);
        $setting = self::getForService($normalizedService);

        if ($setting && $setting->is_active && $setting->model !== 'default') {
            return $setting->model;
        }

        $configDefault = match ($normalizedService) {
            'groq' => (string) config('services.groq.model', ''),
            'gemini' => (string) config('services.gemini.model', ''),
            default => '',
        };

        if ($configDefault !== '') {
            return $configDefault;
        }

        return $default;
    }

    /**
     * Clear cache when settings are updated
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($setting) {
            $service = self::normalizeServiceName((string) $setting->service);
            Cache::forget("ai_service_setting_{$service}");
            Cache::forget("ai_service_setting_id_{$service}");
            Cache::forget('ai_service_settings_active');
            Cache::forget('ai_service_settings_active_ids');
            Cache::forget('admin.ai.service-status.v1');
        });

        static::deleted(function ($setting) {
            $service = self::normalizeServiceName((string) $setting->service);
            Cache::forget("ai_service_setting_{$service}");
            Cache::forget("ai_service_setting_id_{$service}");
            Cache::forget('ai_service_settings_active');
            Cache::forget('ai_service_settings_active_ids');
            Cache::forget('admin.ai.service-status.v1');
        });
    }

    public static function normalizeServiceName(string $service): string
    {
        return match (strtolower(trim($service))) {
            'claude' => 'anthropic',
            default => strtolower(trim($service)),
        };
    }
}
