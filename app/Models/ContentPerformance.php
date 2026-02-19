<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @mixin IdeHelperContentPerformance
 */
class ContentPerformance extends BaseModel
{
    protected $fillable = [
        'contentable_type',
        'contentable_id',
        'ctr',
        'impressions',
        'engagement',
        'ranking_data',
        'measured_at',
    ];

    protected $casts = [
        'ctr' => 'decimal:4',
        'engagement' => 'decimal:2',
        'ranking_data' => 'array',
        'measured_at' => 'datetime',
    ];

    // Relationships
    public function contentable(): MorphTo
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('measured_at', '>=', now()->subDays($days));
    }

    public function scopeForContent($query, string $type, int $id)
    {
        return $query->where('contentable_type', $type)
            ->where('contentable_id', $id);
    }
}

