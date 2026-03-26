<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * @mixin IdeHelperMarketingEvent
 */
class MarketingEvent extends BaseModel
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'type',
        'start_date',
        'end_date',
        'timezone',
        'location',
        'meeting_url',
        'agenda',
        'speakers',
        'max_attendees',
        'registered_count',
        'price',
        'featured_image',
        'tags',
        'is_featured',
        'is_published',
        'registration_open',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'speakers' => 'array',
        'tags' => 'array',
        'max_attendees' => 'integer',
        'registered_count' => 'integer',
        'price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'registration_open' => 'boolean',
    ];

    /**
     * Cache configuration
     */
    const CACHE_KEY = 'marketing_events';

    const CACHE_DURATION = 1800; // 30 minutes

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = Str::slug($event->title);
            }
        });

        static::created(fn () => self::clearCache());
        static::updated(fn () => self::clearCache());
        static::deleted(fn () => self::clearCache());
    }

    /**
     * Get cached events
     */
    public static function getCached()
    {
        return self::cacheRememberManyRows(
            self::CACHE_KEY.'_rows_v1',
            self::CACHE_DURATION,
            fn () => self::published()->upcoming()->ordered()->get(),
            [self::CACHE_KEY],
        );
    }

    /**
     * Clear cache
     */
    public static function clearCache()
    {
        Cache::forget(self::CACHE_KEY);
        Cache::forget(self::CACHE_KEY.'_rows_v1');
        Cache::forget(self::CACHE_KEY.'_upcoming');
    }

    /**
     * Scopes
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now());
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('start_date');
    }

    /**
     * Check if event is upcoming
     */
    public function isUpcoming(): bool
    {
        return $this->start_date > now();
    }

    /**
     * Check if registration is available
     */
    public function canRegister(): bool
    {
        return $this->registration_open &&
               $this->isUpcoming() &&
               (! $this->max_attendees || $this->registered_count < $this->max_attendees);
    }

    /**
     * Get event status
     */
    public function getStatusAttribute()
    {
        if ($this->start_date < now()) {
            return 'completed';
        }

        if (! $this->registration_open) {
            return 'registration_closed';
        }

        if ($this->max_attendees && $this->registered_count >= $this->max_attendees) {
            return 'full';
        }

        return 'upcoming';
    }
}
