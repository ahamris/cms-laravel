<?php

namespace App\Models\Concerns;

use Closure;
use DateInterval;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\Cache;

/**
 * Store model data in cache as row arrays (not Eloquent instances) so drivers with
 * serializable_classes disabled do not yield __PHP_Incomplete_Class.
 */
trait CachesSerializableRows
{
    /**
     * @param  array<int, string>  $legacyKeysToForget
     */
    protected static function cacheRememberModelRow(
        string $cacheKey,
        array|DateInterval|DateTimeInterface|int|null $ttl,
        Closure $loader,
        array $legacyKeysToForget = [],
    ): static {
        $attrs = Cache::get($cacheKey);
        if (! is_array($attrs)) {
            foreach ($legacyKeysToForget as $legacy) {
                Cache::forget($legacy);
            }
            Cache::forget($cacheKey);
            if ($ttl === null) {
                $attrs = Cache::rememberForever($cacheKey, fn () => $loader()->getAttributes());
            } else {
                $attrs = Cache::remember($cacheKey, $ttl, fn () => $loader()->getAttributes());
            }
        }

        /** @var static */
        return (new static)->newFromBuilder($attrs);
    }

    /**
     * @param  Closure(): ?static  $loader
     * @param  array<int, string>  $legacyKeysToForget
     */
    protected static function cacheRememberNullableModelRow(
        string $cacheKey,
        array|DateInterval|DateTimeInterface|int $ttl,
        Closure $loader,
        array $legacyKeysToForget = [],
    ): ?static {
        $wrapped = Cache::get($cacheKey);
        if (! is_array($wrapped) || ! array_key_exists('a', $wrapped)) {
            foreach ($legacyKeysToForget as $legacy) {
                Cache::forget($legacy);
            }
            Cache::forget($cacheKey);
            $wrapped = Cache::remember($cacheKey, $ttl, function () use ($loader) {
                $model = $loader();

                return ['a' => $model?->getAttributes()];
            });
        }

        if (! is_array($wrapped) || $wrapped['a'] === null) {
            return null;
        }

        /** @var static */
        return (new static)->newFromBuilder($wrapped['a']);
    }

    /**
     * @param  Closure(): EloquentCollection<int, static>  $loader
     * @param  array<int, string>|string|null  $reloadRelations
     * @param  array<int, string>  $legacyKeysToForget
     * @return EloquentCollection<int, static>
     */
    protected static function cacheRememberManyRows(
        string $cacheKey,
        array|DateInterval|DateTimeInterface|int $ttl,
        Closure $loader,
        array $legacyKeysToForget = [],
        array|string|null $reloadRelations = null,
    ): EloquentCollection {
        $payload = Cache::get($cacheKey);
        if (! static::cachePayloadIsListOfRowArrays($payload)) {
            foreach ($legacyKeysToForget as $legacy) {
                Cache::forget($legacy);
            }
            Cache::forget($cacheKey);
            $payload = Cache::remember($cacheKey, $ttl, function () use ($loader) {
                return $loader()->map->getAttributes()->values()->all();
            });
        }

        if (! static::cachePayloadIsListOfRowArrays($payload)) {
            return static::hydrate([]);
        }

        $collection = static::hydrate($payload);
        if ($reloadRelations !== null && $reloadRelations !== []) {
            $collection->load(is_array($reloadRelations) ? $reloadRelations : [$reloadRelations]);
        }

        return $collection;
    }

    protected static function cachePayloadIsListOfRowArrays(mixed $payload): bool
    {
        if (! is_array($payload)) {
            return false;
        }
        if ($payload === []) {
            return true;
        }
        if (! array_is_list($payload)) {
            return false;
        }
        foreach ($payload as $row) {
            if (! is_array($row)) {
                return false;
            }
        }

        return true;
    }
}
