<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;

/**
 * @mixin IdeHelperActivityLog
 */
class ActivityLog extends BaseModel
{
    protected $fillable = [
        'user_id',
        'user_name',
        'user_type',
        'description',
        'subject_id',
        'subject_type',
        'performed_at',
    ];

    protected function casts(): array
    {
        return [
            'performed_at' => 'datetime',
        ];
    }

    /**
     * Get the user who performed the action
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the subject model that was acted upon
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Create a simple activity log entry
     */
    public static function log(string $description, $subject = null, ?User $user = null, ?string $guestName = null, ?string $guestEmail = null): self
    {
        $user = $user ?? Auth::user();

        $data = [
            'description' => $description,
            'performed_at' => now(),
        ];

        // Set user information
        if ($user) {
            $data['user_id'] = $user->id;
            $data['user_name'] = $user->name;
            $data['user_type'] = $user->isAdmin() ? 'admin' : 'user';
        } elseif ($guestName) {
            // Guest customer (not registered)
            $data['user_id'] = null;
            $data['user_name'] = $guestName;
            $data['user_type'] = 'customer';
        } else {
            // System action
            $data['user_name'] = 'System';
            $data['user_type'] = 'system';
        }

        // Set subject information if provided
        if ($subject) {
            $data['subject_id'] = $subject->id;
            $data['subject_type'] = get_class($subject);
        }

        return static::create($data);
    }

    /**
     * Scope for filtering by user type
     */
    public function scopeByUserType($query, string $userType)
    {
        return $query->where('user_type', $userType);
    }

    /**
     * Scope for filtering by user
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for filtering by subject
     */
    public function scopeBySubject($query, string $subjectType, int $subjectId)
    {
        return $query->where('subject_type', $subjectType)
            ->where('subject_id', $subjectId);
    }

    /**
     * Scope for recent activities
     */
    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('performed_at', '>=', now()->subDays($days));
    }

    /**
     * Get formatted performed date
     */
    public function getFormattedPerformedDateAttribute(): string
    {
        return $this->performed_at->format('d-m-Y H:i:s');
    }
}
