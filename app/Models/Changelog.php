<?php

namespace App\Models;

use App\Models\Traits\ClearsSitemapCache;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

/**
 * @mixin IdeHelperChangelog
 */
class Changelog extends BaseModel
{
    use ClearsSitemapCache, HasFactory, Sluggable;

    /**
     * Cache key for changelog entries
     */
    const CACHE_KEY = 'changelogs';

    /**
     * Cache duration in seconds (24 hours)
     */
    const CACHE_DURATION = 60 * 60 * 24;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'description',
        'content',
        'video_url',
        'date',
        'status',
        'slug',
        'features',
        'steps',
        'is_active',
        'sort_order',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'date' => 'date',
        'features' => 'array',
        'steps' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get all social media posts for this changelog
     */
    public function socialMediaPosts()
    {
        return $this->morphMany(SocialMediaPost::class, 'postable');
    }

    /**
     * Return the sluggable configuration array for this model.
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
                'onUpdate' => true,
            ],
        ];
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(fn () => self::clearCache());

        static::updated(function (self $changelog) {
            if ($changelog->wasChanged('slug') && $changelog->getOriginal('slug')) {
                $old = $changelog->getOriginal('slug');
                Cache::forget(self::CACHE_KEY."_slug_{$old}");
                Cache::forget(self::CACHE_KEY."_slug_{$old}_row_v1");
            }
            self::clearCache();
        });

        static::deleted(function (self $changelog) {
            if ($changelog->slug) {
                Cache::forget(self::CACHE_KEY."_slug_{$changelog->slug}");
                Cache::forget(self::CACHE_KEY."_slug_{$changelog->slug}_row_v1");
            }
            self::clearCache();
        });
    }

    /**
     * Scope a query to only include active changelogs.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order by sort order and date.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('date', 'desc');
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Get cached changelog entries.
     */
    public static function getCached()
    {
        return self::cacheRememberManyRows(
            self::CACHE_KEY.'_rows_v1',
            self::CACHE_DURATION,
            fn () => self::active()->ordered()->get(),
            [self::CACHE_KEY],
        );
    }

    /**
     * Get cached changelog entries.
     */
    public static function getCachedFour()
    {
        return self::cacheRememberManyRows(
            self::CACHE_KEY.'_four_rows_v1',
            self::CACHE_DURATION,
            fn () => self::active()->whereNotIn('status', ['api'])->ordered()->take(4)->get(),
            [self::CACHE_KEY.'_four'],
        );
    }

    /**
     * Get cached changelog entries.
     */
    public static function getCachedFourByStatus($status)
    {
        return self::cacheRememberManyRows(
            self::CACHE_KEY."_four_by_status_{$status}_rows_v1",
            self::CACHE_DURATION,
            fn () => self::active()->byStatus($status)->ordered()->take(4)->get(),
            [self::CACHE_KEY."_four_by_status_{$status}"],
        );
    }

    /**
     * Get changelog by slug with caching.
     */
    public static function getBySlug($slug)
    {
        $rowKey = self::CACHE_KEY."_slug_{$slug}_row_v1";
        $legacyKey = self::CACHE_KEY."_slug_{$slug}";

        return self::cacheRememberNullableModelRow(
            $rowKey,
            self::CACHE_DURATION,
            fn () => self::active()->where('slug', $slug)->first(),
            [$legacyKey],
        );
    }

    /**
     * Get recent changelog entries.
     */
    public static function getRecent($limit = 5)
    {
        $legacyKey = self::CACHE_KEY."_recent_{$limit}";

        return self::cacheRememberManyRows(
            self::CACHE_KEY."_recent_{$limit}_rows_v1",
            self::CACHE_DURATION,
            fn () => self::active()->ordered()->limit($limit)->get(),
            [$legacyKey],
        );
    }

    /**
     * Get changelog entries by status.
     */
    public static function getByStatus($status)
    {
        $legacyKey = self::CACHE_KEY."_status_{$status}";

        return self::cacheRememberManyRows(
            self::CACHE_KEY."_status_{$status}_rows_v1",
            self::CACHE_DURATION,
            fn () => self::active()->byStatus($status)->ordered()->get(),
            [$legacyKey],
        );
    }

    /**
     * Clear changelog cache.
     */
    public static function clearCache()
    {
        $keys = [
            self::CACHE_KEY,
            self::CACHE_KEY.'_rows_v1',
            self::CACHE_KEY.'_four',
            self::CACHE_KEY.'_four_rows_v1',
        ];

        $statuses = ['new', 'improved', 'fixed', 'api'];
        foreach ($statuses as $status) {
            $keys[] = self::CACHE_KEY."_status_{$status}";
            $keys[] = self::CACHE_KEY."_status_{$status}_rows_v1";
            $keys[] = self::CACHE_KEY."_four_by_status_{$status}";
            $keys[] = self::CACHE_KEY."_four_by_status_{$status}_rows_v1";
        }

        for ($i = 1; $i <= 50; $i++) {
            $keys[] = self::CACHE_KEY."_recent_{$i}";
            $keys[] = self::CACHE_KEY."_recent_{$i}_rows_v1";
        }

        foreach (self::query()->pluck('slug')->filter() as $slug) {
            $keys[] = self::CACHE_KEY."_slug_{$slug}";
            $keys[] = self::CACHE_KEY."_slug_{$slug}_row_v1";
        }

        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }

    /**
     * Get status badge color.
     */
    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'new' => 'success',
            'improved' => 'primary',
            'fixed' => 'warning',
            'api' => 'info',
            default => 'secondary'
        };
    }

    /**
     * Get status display name.
     */
    public function getStatusDisplayAttribute()
    {
        return match ($this->status) {
            'new' => 'New Feature',
            'improved' => 'Improvement',
            'fixed' => 'Bug Fix',
            'api' => 'API Update',
            default => ucfirst($this->status)
        };
    }

    /**
     * Get formatted date for display.
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->date->format('M j, Y');
    }

    /**
     * Get human readable date in days format (e.g., "1 day ago", "150 days ago").
     */
    public function getHumanReadableDateAttribute(): string
    {
        $diffInDays = $this->date->diffInDays(today());

        if ($diffInDays === 0) {

            return 'vandaag';
        } elseif ($diffInDays === 1) {
            return '1 dag geleden';
        } else {
            return $diffInDays.' dagen geleden';
        }
    }

    /**
     * Get route key name for model binding.
     */
    public function getRouteKeyName()
    {
        // Use slug for frontend routes, id for admin routes
        if (request()->is('admin/*')) {
            return 'id';
        }

        return 'slug';
    }
}
