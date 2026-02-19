<?php

namespace App\Services;

use App\Models\Translation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class TranslationService
{
    protected $defaultLocale;

    protected $fallbackLocale;

    protected $cacheDriver;

    protected $cacheEnabled;

    public function __construct()
    {
        $this->defaultLocale = config('app.locale', 'en');
        $this->fallbackLocale = config('app.fallback_locale', 'en');
        $this->cacheDriver = config('translation.cache_driver', 'file'); // redis, file, or none
        $this->cacheEnabled = config('translation.cache_enabled', true);
    }

    /**
     * Get a translation by key
     */
    public function get(string $key, ?string $locale = null, array $replace = [], ?string $group = null): string
    {
        $locale = $locale ?? app()->getLocale() ?? $this->defaultLocale;

        // Try to get from cache first (if enabled)
        $translation = $this->cacheEnabled ? $this->getFromCache($key, $locale, $group) : null;

        if ($translation === null) {
            // Fallback to database
            $translation = Translation::getTranslation($key, $locale, $group);

            // If not found, try fallback locale
            if ($translation === null && $locale !== $this->fallbackLocale) {
                $translation = Translation::getTranslation($key, $this->fallbackLocale, $group);
            }

            // If still not found, return the key itself
            if ($translation === null) {
                $translation = $key;
            }

            // Cache for future use
            if ($this->cacheEnabled) {
                $this->cacheTranslation($key, $locale, $group, $translation);
            }
        }

        // Replace placeholders
        if (! empty($replace)) {
            $translation = $this->replacePlaceholders($translation, $replace);
        }

        return $translation;
    }

    /**
     * Set a translation
     */
    public function set(string $key, string $value, ?string $locale = null, ?string $group = null, ?string $description = null): Translation
    {
        $locale = $locale ?? $this->defaultLocale;

        $translation = Translation::setTranslation($key, $locale, $value, $group, $description);

        // Clear cache
        if ($this->cacheEnabled) {
            $this->clearTranslationCache($key, $locale, $group);
        }

        return $translation;
    }

    /**
     * Get all translations for a locale
     */
    public function getAllForLocale(?string $locale = null, ?string $group = null): array
    {
        $locale = $locale ?? app()->getLocale() ?? $this->defaultLocale;

        // Try cache first (if enabled)
        $translations = $this->cacheEnabled ? $this->getAllFromCache($locale, $group) : null;

        if ($translations === null) {
            // Fallback to database
            $translations = Translation::getAllForLocale($locale, $group);

            // Cache for future use
            if ($this->cacheEnabled) {
                $this->cacheAllTranslations($locale, $group, $translations);
            }
        }

        return $translations;
    }

    /**
     * Load translations from database to cache
     */
    public function loadToCache(?string $locale = null): void
    {
        if (! $this->cacheEnabled) {
            return;
        }

        $locales = $locale ? [$locale] : $this->getAvailableLocales();

        foreach ($locales as $loc) {
            $translations = Translation::getAllForLocale($loc);
            $this->cacheAllTranslations($loc, null, $translations);

            // Also cache by groups
            $groups = Translation::forLocale($loc)->distinct('group_name')->pluck('group_name');
            foreach ($groups as $group) {
                $groupTranslations = Translation::getAllForLocale($loc, $group);
                $this->cacheAllTranslations($loc, $group, $groupTranslations);
            }
        }
    }

    /**
     * Clear all translation caches
     */
    public function clearCache(?string $locale = null): void
    {
        // Treat empty string as null to clear all caches
        $localeToClear = $locale ?: null;

        // Clear Laravel cache
        Translation::clearTranslationCache($localeToClear);

        // Clear external cache (Redis/File)
        if ($this->cacheEnabled) {
            $this->clearAllCache($localeToClear);
        }
    }

    /**
     * Get available locales
     */
    public function getAvailableLocales(): array
    {
        return Translation::distinct('locale')->pluck('locale')->toArray();
    }

    /**
     * Replace placeholders in translation
     */
    protected function replacePlaceholders(string $translation, array $replace): string
    {
        foreach ($replace as $key => $value) {
            $translation = str_replace([':'.$key, '{'.$key.'}'], $value, $translation);
        }

        return $translation;
    }

    /**
     * Get translation from cache (abstracted)
     */
    protected function getFromCache(string $key, string $locale, ?string $group): ?string
    {
        switch ($this->cacheDriver) {
            case 'redis':
                return $this->getFromRedis($key, $locale, $group);
            case 'file':
                return $this->getFromFileCache($key, $locale, $group);
            default:
                return null;
        }
    }

    /**
     * Cache translation (abstracted)
     */
    protected function cacheTranslation(string $key, string $locale, ?string $group, string $value): void
    {
        switch ($this->cacheDriver) {
            case 'redis':
                $this->cacheToRedis($key, $locale, $group, $value);
                break;
            case 'file':
                $this->cacheToFile($key, $locale, $group, $value);
                break;
        }
    }

    /**
     * Get all translations from cache (abstracted)
     */
    protected function getAllFromCache(string $locale, ?string $group): ?array
    {
        switch ($this->cacheDriver) {
            case 'redis':
                return $this->getAllFromRedis($locale, $group);
            case 'file':
                return $this->getAllFromFileCache($locale, $group);
            default:
                return null;
        }
    }

    /**
     * Cache all translations (abstracted)
     */
    protected function cacheAllTranslations(string $locale, ?string $group, array $translations): void
    {
        switch ($this->cacheDriver) {
            case 'redis':
                $this->cacheAllToRedis($locale, $group, $translations);
                break;
            case 'file':
                $this->cacheAllToFile($locale, $group, $translations);
                break;
        }
    }

    /**
     * Clear translation cache (abstracted)
     */
    protected function clearTranslationCache(string $key, string $locale, ?string $group): void
    {
        switch ($this->cacheDriver) {
            case 'redis':
                $this->clearRedisCache($key, $locale, $group);
                break;
            case 'file':
                $this->clearFileCache($key, $locale, $group);
                break;
        }
    }

    /**
     * Clear all caches (abstracted)
     */
    protected function clearAllCache(?string $locale): void
    {
        switch ($this->cacheDriver) {
            case 'redis':
                $this->clearAllRedisCache($locale);
                break;
            case 'file':
                $this->clearAllFileCache($locale);
                break;
        }
    }

    // ============ FILE CACHE METHODS ============

    /**
     * Get translation from file cache
     */
    protected function getFromFileCache(string $key, string $locale, ?string $group): ?string
    {
        try {
            $cacheKey = $this->getFileCacheKey($key, $locale, $group);

            return Cache::get($cacheKey);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Cache translation to file
     */
    protected function cacheToFile(string $key, string $locale, ?string $group, string $value): void
    {
        try {
            $cacheKey = $this->getFileCacheKey($key, $locale, $group);
            Cache::put($cacheKey, $value, 3600); // Cache for 1 hour
        } catch (\Exception $e) {
            // Cache not available, ignore
        }
    }

    /**
     * Get all translations from file cache
     */
    protected function getAllFromFileCache(string $locale, ?string $group): ?array
    {
        try {
            $cacheKey = "translations_all:{$locale}:".($group ?? 'all');

            return Cache::get($cacheKey);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Cache all translations to file
     */
    protected function cacheAllToFile(string $locale, ?string $group, array $translations): void
    {
        try {
            $cacheKey = "translations_all:{$locale}:".($group ?? 'all');
            Cache::put($cacheKey, $translations, 3600);
        } catch (\Exception $e) {
            // Cache not available, ignore
        }
    }

    /**
     * Clear file cache for specific translation
     */
    protected function clearFileCache(string $key, string $locale, ?string $group): void
    {
        try {
            $cacheKey = $this->getFileCacheKey($key, $locale, $group);
            Cache::forget($cacheKey);

            // Also clear the all translations cache
            Cache::forget("translations_all:{$locale}:".($group ?? 'all'));
        } catch (\Exception $e) {
            // Cache not available, ignore
        }
    }

    /**
     * Clear all file translation caches
     */
    protected function clearAllFileCache(?string $locale): void
    {
        try {
            if ($locale) {
                // Clear specific locale caches
                Cache::forget("translations_all:{$locale}:all");

                // Clear group-specific caches for this locale
                $groups = Translation::forLocale($locale)->distinct('group_name')->pluck('group_name');
                foreach ($groups as $group) {
                    Cache::forget("translations_all:{$locale}:{$group}");
                }
            } else {
                // Clear all translation caches - this is more complex with file cache
                // We'll use cache tags if available, otherwise clear by known patterns
                $locales = $this->getAvailableLocales();
                foreach ($locales as $loc) {
                    Cache::forget("translations_all:{$loc}:all");
                    $groups = Translation::forLocale($loc)->distinct('group_name')->pluck('group_name');
                    foreach ($groups as $group) {
                        Cache::forget("translations_all:{$loc}:{$group}");
                    }
                }
            }
        } catch (\Exception $e) {
            // Cache not available, ignore
        }
    }

    /**
     * Generate file cache key
     */
    protected function getFileCacheKey(string $key, string $locale, ?string $group): string
    {
        return "translation_single:{$locale}:".($group ?? 'default').":{$key}";
    }

    // ============ REDIS CACHE METHODS ============

    /**
     * Get translation from Redis
     */
    protected function getFromRedis(string $key, string $locale, ?string $group): ?string
    {
        if (! $this->isRedisAvailable()) {
            return null;
        }

        try {
            $redisKey = $this->getRedisKey($key, $locale, $group);
            $value = Redis::get($redisKey);

            return $value !== null ? $value : null;
        } catch (\Exception $e) {
            // Redis not available, return null to fallback to database
            return null;
        }
    }

    /**
     * Cache translation to Redis
     */
    protected function cacheToRedis(string $key, string $locale, ?string $group, string $value): void
    {
        if (! $this->isRedisAvailable()) {
            return;
        }

        try {
            $redisKey = $this->getRedisKey($key, $locale, $group);
            Redis::setex($redisKey, 3600, $value); // Cache for 1 hour
        } catch (\Exception $e) {
            // Redis not available, ignore
        }
    }

    /**
     * Get all translations from Redis
     */
    protected function getAllFromRedis(string $locale, ?string $group): ?array
    {
        if (! $this->isRedisAvailable()) {
            return null;
        }

        try {
            $redisKey = "translations:{$locale}:".($group ?? 'all');
            $value = Redis::get($redisKey);

            return $value !== null ? json_decode($value, true) : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Cache all translations to Redis
     */
    protected function cacheAllToRedis(string $locale, ?string $group, array $translations): void
    {
        if (! $this->isRedisAvailable()) {
            return;
        }

        try {
            $redisKey = "translations:{$locale}:".($group ?? 'all');
            Redis::setex($redisKey, 3600, json_encode($translations));
        } catch (\Exception $e) {
            // Redis not available, ignore
        }
    }

    /**
     * Clear Redis cache for specific translation
     */
    protected function clearRedisCache(string $key, string $locale, ?string $group): void
    {
        if (! $this->isRedisAvailable()) {
            return;
        }

        try {
            $redisKey = $this->getRedisKey($key, $locale, $group);
            Redis::del($redisKey);

            // Also clear the all translations cache
            Redis::del("translations:{$locale}:".($group ?? 'all'));
        } catch (\Exception $e) {
            // Redis not available, ignore
        }
    }

    /**
     * Clear all Redis translation caches
     */
    protected function clearAllRedisCache(?string $locale): void
    {
        if (! $this->isRedisAvailable()) {
            return;
        }

        try {
            if ($locale) {
                $pattern = "translations:{$locale}:*";
                $keys = Redis::keys($pattern);
                if (! empty($keys)) {
                    Redis::del($keys);
                }
            } else {
                $pattern = 'translations:*';
                $keys = Redis::keys($pattern);
                if (! empty($keys)) {
                    Redis::del($keys);
                }
            }
        } catch (\Exception $e) {
            // Redis not available, ignore
        }
    }

    /**
     * Generate Redis key
     */
    protected function getRedisKey(string $key, string $locale, ?string $group): string
    {
        return "translation:{$locale}:".($group ?? 'default').":{$key}";
    }

    /**
     * Check if Redis is available and configured
     */
    protected function isRedisAvailable(): bool
    {
        try {
            return $this->cacheDriver === 'redis' && Redis::ping();
        } catch (\Exception $e) {
            return false;
        }
    }
}
