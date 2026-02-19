<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

/**
 * @mixin IdeHelperTranslation
 */
class Translation extends BaseModel
{
    protected $fillable = [
        'translation_key',
        'locale',
        'translation_value',
        'group_name',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Scope to filter by locale
     */
    public function scopeForLocale(Builder $query, string $locale): Builder
    {
        return $query->where('locale', $locale);
    }

    /**
     * Scope to filter by group
     */
    public function scopeForGroup(Builder $query, ?string $group): Builder
    {
        return $query->where('group_name', $group);
    }

    /**
     * Scope to filter active translations
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Get translation by key and locale
     */
    public static function getTranslation(string $key, string $locale, ?string $group = null): ?string
    {
        $cacheKey = "translation.{$locale}.{$group}.{$key}";

        return Cache::remember($cacheKey, 3600, function () use ($key, $locale, $group) {
            return static::active()
                ->forLocale($locale)
                ->forGroup($group)
                ->where('translation_key', $key)
                ->value('translation_value');
        });
    }

    /**
     * Get all translations for a locale
     */
    public static function getAllForLocale(string $locale, ?string $group = null): array
    {
        $cacheKey = "translations.{$locale}.".($group ?? 'all');

        return Cache::remember($cacheKey, 3600, function () use ($locale, $group) {
            $query = static::active()->forLocale($locale);

            if ($group !== null) {
                $query->forGroup($group);
            }

            return $query->pluck('translation_value', 'translation_key')->toArray();
        });
    }

    /**
     * Set a translation
     */
    public static function setTranslation(string $key, string $locale, string $value, ?string $group = null, ?string $description = null): self
    {
        $translation = static::updateOrCreate(
            [
                'translation_key' => $key,
                'locale' => $locale,
                'group_name' => $group,
            ],
            [
                'translation_value' => $value,
                'description' => $description,
                'is_active' => true,
            ]
        );

        // Clear cache
        static::clearTranslationCache($locale, $group, $key);

        return $translation;
    }

    /**
     * Clear translation cache
     */
    public static function clearTranslationCache(?string $locale = null, ?string $group = null, ?string $key = null): void
    {
        if ($key) {
            // If a specific key is provided, just forget that one
            Cache::forget("translation.{$locale}.{$group}.{$key}");
        } elseif ($locale) {
            // If a locale is provided, clear all groups for that locale
            $groups = static::where('locale', $locale)->distinct()->pluck('group_name')->push(null);
            foreach ($groups as $grp) {
                Cache::forget("translations.{$locale}.".($grp ?? 'all'));
            }
        } else {
            // If no locale, clear everything for all locales
            $locales = static::distinct()->pluck('locale');
            foreach ($locales as $loc) {
                $groups = static::where('locale', $loc)->distinct()->pluck('group_name')->push(null);
                foreach ($groups as $grp) {
                    Cache::forget("translations.{$loc}.".($grp ?? 'all'));
                }
            }
        }
    }

    /**
     * Boot method to clear cache on model events
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($translation) {
            static::clearTranslationCache($translation->locale, $translation->group_name, $translation->translation_key);
        });

        static::deleted(function ($translation) {
            static::clearTranslationCache($translation->locale, $translation->group_name, $translation->translation_key);
        });
    }
}
