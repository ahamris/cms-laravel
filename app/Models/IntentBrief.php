<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin IdeHelperIntentBrief
 */
class IntentBrief extends BaseModel
{
    protected $fillable = [
        'user_id',
        'business_goal',
        'audience',
        'topic',
        'tone',
        'approval_level',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function contentPlan(): HasOne
    {
        return $this->hasOne(ContentPlan::class);
    }

    // Scopes
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}

