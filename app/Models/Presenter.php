<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

/**
 * @mixin IdeHelperPresenter
 */
class Presenter extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'title',
        'bio',
        'avatar',
        'email',
        'linkedin_url',
        'twitter_url',
        'company',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function liveSessions()
    {
        return $this->belongsToMany(LiveSession::class, 'live_session_presenter')
                    ->withPivot(['is_primary', 'sort_order'])
                    ->withTimestamps()
                    ->orderBy('pivot_sort_order');
    }

    /**
     * Scopes
     */
    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query)
    {
        return $query->orderBy('sort_order', 'asc')
                    ->orderBy('name', 'asc');
    }

    /**
     * Accessors
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        
        // Default avatar using initials
        $initials = collect(explode(' ', $this->name))
                    ->map(fn($name) => strtoupper(substr($name, 0, 1)))
                    ->take(2)
                    ->implode('');
                    
        return "https://ui-avatars.com/api/?name={$initials}&background=3B82F6&color=ffffff&size=128";
    }

    public function getFullTitleAttribute()
    {
        $parts = array_filter([$this->title, $this->company]);
        return implode(' bij ', $parts);
    }

    /**
     * Accessors
     */
    public function getInitialsAttribute()
    {
        $names = explode(' ', $this->name);
        $initials = '';
        
        foreach ($names as $name) {
            if (!empty($name)) {
                $initials .= strtoupper(substr($name, 0, 1));
            }
        }
        
        return $initials;
    }

    /**
     * Helper methods
     */
    public function getSessionsCount()
    {
        return $this->liveSessions()->count();
    }

    public function getUpcomingSessionsCount()
    {
        return $this->liveSessions()
                    ->where('status', 'upcoming')
                    ->where('session_date', '>', now())
                    ->count();
    }
}
