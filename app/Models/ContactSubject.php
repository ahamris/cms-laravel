<?php

namespace App\Models;

use App\Helpers\Variable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class ContactSubject extends Model
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
        static::created(fn () => Cache::forget(self::CACHE_KEY));
        static::updated(fn () => Cache::forget(self::CACHE_KEY));
        static::deleted(fn () => Cache::forget(self::CACHE_KEY));
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
    public static function getCached(): \Illuminate\Database\Eloquent\Collection
    {
        if (! Cache::has(self::CACHE_KEY)) {
            return Cache::remember(self::CACHE_KEY, Variable::CACHE_TTL, function () {
                return self::active()->ordered()->get();
            });
        }

        return Cache::get(self::CACHE_KEY);
    }
}
