<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Cviebrock\EloquentSluggable\Sluggable;
use Carbon\Carbon;

/**
 * @mixin IdeHelperLiveSession
 */
class LiveSession extends BaseModel
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'title',
        'description',
        'content',
        'slug',
        'session_date',
        'duration_minutes',
        'max_participants',
        'status',
        'type',
        'meeting_url',
        'recording_url',
        'thumbnail',
        'icon',
        'color',
        'is_featured',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'session_date' => 'datetime',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Return the sluggable configuration array for this model.
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    /**
     * Relationships
     */
    public function presenters()
    {
        return $this->belongsToMany(Presenter::class, 'live_session_presenter')
            ->withPivot(['is_primary', 'sort_order'])
            ->withTimestamps()
            ->orderBy('pivot_sort_order');
    }

    public function registrations()
    {
        return $this->hasMany(SessionRegistration::class);
    }

    /**
     * Scopes
     */
    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured(Builder $query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeUpcoming(Builder $query)
    {
        return $query->where('status', 'upcoming')
            ->where('session_date', '>', now());
    }

    public function scopeCompleted(Builder $query)
    {
        return $query->where('status', 'completed')
            ->orWhere('session_date', '<', now());
    }

    public function scopeOrdered(Builder $query)
    {
        return $query->orderBy('session_date', 'asc')
            ->orderBy('sort_order', 'asc');
    }

    /**
     * Accessors
     */
    public function getFormattedDateAttribute()
    {
        return $this->session_date->format('l j F Y');
    }

    public function getFormattedTimeAttribute()
    {
        return $this->session_date->format('H:i');
    }

    public function getFormattedDateTimeAttribute()
    {
        return $this->session_date->format('l j F Y \o\m H:i \u\u\r');
    }

    public function getDurationFormattedAttribute()
    {
        if ($this->duration_minutes < 60) {
            return $this->duration_minutes . ' minuten';
        }

        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;

        if ($minutes === 0) {
            return $hours . ' uur';
        }

        return $hours . ' uur ' . $minutes . ' minuten';
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'upcoming' => 'blue',
            'live' => 'green',
            'completed' => 'gray',
            'cancelled' => 'red',
            default => 'gray'
        };
    }

    public function getStatusDisplayAttribute()
    {
        return match ($this->status) {
            'upcoming' => 'Aankomend',
            'live' => 'Live',
            'completed' => 'Voltooid',
            'cancelled' => 'Geannuleerd',
            default => 'Onbekend'
        };
    }

    public function getTypeDisplayAttribute()
    {
        return match ($this->type) {
            'introduction' => 'Introductie',
            'webinar' => 'Webinar',
            'workshop' => 'Workshop',
            'qa' => 'Q&A Sessie',
            default => 'Sessie'
        };
    }

    public function getRegistrationCountAttribute()
    {
        return $this->registrations()->where('status', 'registered')->count();
    }

    public function getAvailableSpotsAttribute()
    {
        return max(0, $this->max_participants - $this->registration_count);
    }

    public function getIsFullAttribute()
    {
        return $this->registration_count >= $this->max_participants;
    }

    public function getIsLiveAttribute()
    {
        return $this->status === 'live' ||
            ($this->session_date->isPast() &&
                $this->session_date->addMinutes($this->duration_minutes)->isFuture());
    }

    /**
     * Helper methods
     */
    public function updateStatus()
    {
        $now = now();
        $sessionEnd = $this->session_date->addMinutes($this->duration_minutes);

        if ($now->isBefore($this->session_date)) {
            $this->status = 'upcoming';
        } elseif ($now->isBetween($this->session_date, $sessionEnd)) {
            $this->status = 'live';
        } else {
            $this->status = 'completed';
        }

        $this->save();
    }
}
