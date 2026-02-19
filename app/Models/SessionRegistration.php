<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

/**
 * @mixin IdeHelperSessionRegistration
 */
class SessionRegistration extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'live_session_id',
        'organization',
        'name',
        'email',
        'marketing_consent',
        'status',
        'registered_at',
        'attended_at',
        'notes',
    ];

    protected $casts = [
        'marketing_consent' => 'boolean',
        'registered_at' => 'datetime',
        'attended_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function liveSession()
    {
        return $this->belongsTo(LiveSession::class);
    }

    /**
     * Scopes
     */
    public function scopeRegistered(Builder $query)
    {
        return $query->where('status', 'registered');
    }

    public function scopeAttended(Builder $query)
    {
        return $query->where('status', 'attended');
    }

    public function scopeNoShow(Builder $query)
    {
        return $query->where('status', 'no_show');
    }

    public function scopeCancelled(Builder $query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeForSession(Builder $query, $sessionId)
    {
        return $query->where('live_session_id', $sessionId);
    }

    /**
     * Accessors
     */
    public function getStatusDisplayAttribute()
    {
        return match($this->status) {
            'registered' => 'Geregistreerd',
            'attended' => 'Aanwezig',
            'no_show' => 'Niet verschenen',
            'cancelled' => 'Geannuleerd',
            default => 'Onbekend'
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'registered' => 'blue',
            'attended' => 'green',
            'no_show' => 'yellow',
            'cancelled' => 'red',
            default => 'gray'
        };
    }

    /**
     * Helper methods
     */
    public function markAsAttended()
    {
        $this->update([
            'status' => 'attended',
            'attended_at' => now(),
        ]);
    }

    public function markAsNoShow()
    {
        $this->update([
            'status' => 'no_show',
        ]);
    }

    public function cancel()
    {
        $this->update([
            'status' => 'cancelled',
        ]);
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($registration) {
            if (!$registration->registered_at) {
                $registration->registered_at = now();
            }
        });
    }
}
