<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

/**
 * @mixin IdeHelperSocialMediaPost
 */
class SocialMediaPost extends BaseModel
{
    protected $fillable = [
        'social_media_platform_id',
        'postable_type',
        'postable_id',
        'content',
        'media_urls',
        'hashtags',
        'external_post_id',
        'external_post_url',
        'status',
        'scheduled_at',
        'posted_at',
        'response_data',
        'error_message',
        'engagement_stats',
    ];

    protected $casts = [
        'media_urls' => 'array',
        'hashtags' => 'array',
        'scheduled_at' => 'datetime',
        'posted_at' => 'datetime',
        'response_data' => 'array',
        'engagement_stats' => 'array',
    ];

    // Relationships
    public function socialMediaPlatform(): BelongsTo
    {
        return $this->belongsTo(SocialMediaPlatform::class);
    }

    public function postable(): MorphTo
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopeStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    public function scopeScheduled(Builder $query): Builder
    {
        return $query->where('status', 'scheduled');
    }

    public function scopePosted(Builder $query): Builder
    {
        return $query->where('status', 'posted');
    }

    public function scopeFailed(Builder $query): Builder
    {
        return $query->where('status', 'failed');
    }

    public function scopeDue(Builder $query): Builder
    {
        return $query->where('status', 'scheduled')
                    ->where('scheduled_at', '<=', now());
    }

    public function scopeForPlatform(Builder $query, int $platformId): Builder
    {
        return $query->where('social_media_platform_id', $platformId);
    }

    // Helper methods
    public function isScheduled(): bool
    {
        return $this->status === 'scheduled';
    }

    public function isPosted(): bool
    {
        return $this->status === 'posted';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function isDue(): bool
    {
        return $this->isScheduled() && $this->scheduled_at <= now();
    }

    public function markAsPosted(string $externalPostId = null, string $externalPostUrl = null, array $responseData = []): void
    {
        $this->update([
            'status' => 'posted',
            'posted_at' => now(),
            'external_post_id' => $externalPostId,
            'external_post_url' => $externalPostUrl,
            'response_data' => $responseData,
            'error_message' => null,
        ]);
    }

    public function markAsFailed(string $errorMessage, array $responseData = []): void
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage,
            'response_data' => $responseData,
        ]);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'draft' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Draft</span>',
            'scheduled' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Scheduled</span>',
            'posted' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Posted</span>',
            'failed' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Failed</span>',
            default => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Unknown</span>',
        };
    }

    public function getFormattedScheduledAtAttribute(): string
    {
        if (!$this->scheduled_at) {
            return 'Not scheduled';
        }

        return $this->scheduled_at->format('M d, Y \a\t H:i');
    }

    public function getFormattedPostedAtAttribute(): string
    {
        if (!$this->posted_at) {
            return 'Not posted';
        }

        return $this->posted_at->format('M d, Y \a\t H:i');
    }
}
