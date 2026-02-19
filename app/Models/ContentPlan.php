<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperContentPlan
 */
class ContentPlan extends BaseModel
{
    protected $fillable = [
        'intent_brief_id',
        'status',
        'autopilot_mode',
        'approved_at',
        'start_date',
        'end_date',
        'strategy_data',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'start_date' => 'date',
        'end_date' => 'date',
        'strategy_data' => 'array',
    ];

    // Relationships
    public function intentBrief(): BelongsTo
    {
        return $this->belongsTo(IntentBrief::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ContentPlanItem::class);
    }

    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class);
    }

    // Scopes
    public function scopePendingApproval($query)
    {
        return $query->where('status', 'pending_approval');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Helper methods
    public function approve(): void
    {
        $this->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);
    }

    public function isAssisted(): bool
    {
        return $this->autopilot_mode === 'assisted';
    }

    public function isGuided(): bool
    {
        return $this->autopilot_mode === 'guided';
    }

    public function isFullAutopilot(): bool
    {
        return $this->autopilot_mode === 'full_autopilot';
    }
}

