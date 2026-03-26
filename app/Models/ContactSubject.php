<?php

namespace App\Models;

use App\Helpers\Variable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * @mixin IdeHelperContactSubject
 */
class ContactSubject extends BaseModel
{
    protected $fillable = [
        'title',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    const CACHE_KEY = 'contact_subjects';

    protected static function boot(): void
    {
        parent::boot();
        static::created(fn () => self::forgetContactSubjectCache());
        static::updated(fn () => self::forgetContactSubjectCache());
        static::deleted(fn () => self::forgetContactSubjectCache());
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Cached list of active subjects for contact form dropdown (API / frontend).
     */
    public static function forgetContactSubjectCache(): void
    {
        Cache::forget(self::CACHE_KEY);
        Cache::forget(self::CACHE_KEY.'_rows_v1');
    }

    public static function getCached(): Collection
    {
        return self::cacheRememberManyRows(
            self::CACHE_KEY.'_rows_v1',
            Variable::CACHE_TTL,
            fn () => self::active()->ordered()->get(),
            [self::CACHE_KEY],
        );
    }
}
