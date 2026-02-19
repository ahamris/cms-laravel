<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @mixin IdeHelperContentPlanItem
 */
class ContentPlanItem extends BaseModel
{
    protected $fillable = [
        'content_plan_id',
        'item_type',
        'status',
        'priority',
        'scheduled_at',
        'content_data',
        'related_content_id',
        'related_content_type',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'content_data' => 'array',
    ];

    // Relationships
    public function contentPlan(): BelongsTo
    {
        return $this->belongsTo(ContentPlan::class);
    }

    public function relatedContent(): MorphTo
    {
        return $this->morphTo('related_content');
    }

    // Scopes
    public function scopePlanned($query)
    {
        return $query->where('status', 'planned');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDue($query)
    {
        return $query->where('status', 'scheduled')
            ->where('scheduled_at', '<=', now());
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('item_type', $type);
    }

    // Helper methods
    public function isPillar(): bool
    {
        return $this->item_type === 'pillar';
    }

    public function isSupporting(): bool
    {
        return $this->item_type === 'supporting';
    }

    public function isSocial(): bool
    {
        return $this->item_type === 'social';
    }

    public function isEvergreen(): bool
    {
        return $this->item_type === 'evergreen';
    }

    public function markAsPublished(): void
    {
        $this->update(['status' => 'published']);
    }
}

