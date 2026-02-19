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
    use HasFactory, Sluggable, ClearsSitemapCache;

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
        return $this->morphMany(\App\Models\SocialMediaPost::class, 'postable');
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

        // Clear cache when model is created, updated, or deleted
        static::created(fn () => self::clearCache());
        static::updated(fn () => self::clearCache());
        static::deleted(fn () => self::clearCache());
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
        return Cache::remember(self::CACHE_KEY, self::CACHE_DURATION, function () {
            return self::active()->ordered()->get();
        });
    }

    /**
     * Get cached changelog entries.
     */
    public static function getCachedFour()
    {
        return Cache::remember(self::CACHE_KEY.'_four', self::CACHE_DURATION, function () {
            return self::active()->whereNotIn('status', ['api'])->ordered()->take(4)->get();
        });
    }

    /**
     * Get cached changelog entries.
     */
    public static function getCachedFourByStatus($status)
    {
        return Cache::remember(self::CACHE_KEY.'_four_by_status_'.$status, self::CACHE_DURATION, function () use ($status) {
            return self::active()->byStatus($status)->ordered()->take(4)->get();
        });
    }

    /**
     * Get changelog by slug with caching.
     */
    public static function getBySlug($slug)
    {
        $cacheKey = self::CACHE_KEY."_slug_{$slug}";

        return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($slug) {
            return self::active()->where('slug', $slug)->first();
        });
    }

    /**
     * Get recent changelog entries.
     */
    public static function getRecent($limit = 5)
    {
        $cacheKey = self::CACHE_KEY."_recent_{$limit}";

        return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($limit) {
            return self::active()->ordered()->limit($limit)->get();
        });
    }

    /**
     * Get changelog entries by status.
     */
    public static function getByStatus($status)
    {
        $cacheKey = self::CACHE_KEY."_status_{$status}";

        return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($status) {
            return self::active()->byStatus($status)->ordered()->get();
        });
    }

    /**
     * Clear changelog cache.
     */
    public static function clearCache()
    {
        Cache::forget(self::CACHE_KEY);

        // Clear related caches
        $statuses = ['new', 'improved', 'fixed', 'api'];
        foreach ($statuses as $status) {
            Cache::forget(self::CACHE_KEY."_status_{$status}");
        }

        // Clear recent cache variations
        for ($i = 1; $i <= 10; $i++) {
            Cache::forget(self::CACHE_KEY."_recent_{$i}");
        }

        Cache::forget(self::CACHE_KEY.'_four');

        // Note: Individual slug caches will be cleared naturally when they expire
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

