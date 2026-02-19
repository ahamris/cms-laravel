<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperPageBlockPreset
 */
class PageBlockPreset extends BaseModel
{
    protected $fillable = [
        'name',
        'description',
        'type',
        'blocks',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'blocks' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user who created this preset
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope a query to only include active presets
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include header presets
     */
    public function scopeHeader($query)
    {
        return $query->where('type', 'header');
    }

    /**
     * Scope a query to only include body presets
     */
    public function scopeBody($query)
    {
        return $query->where('type', 'body');
    }
}

