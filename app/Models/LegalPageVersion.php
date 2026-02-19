<?php

namespace App\Models;

/**
 * @mixin IdeHelperLegalPageVersion
 */
class LegalPageVersion extends BaseModel
{
    protected $table = 'legal_page_versions';

    protected $fillable = [
        'legal_page_id',
        'version_number',
        'title',
        'slug',
        'body',
        'is_active',
        'meta_title',
        'meta_description',
        'keywords',
        'image',
        'selected_call_actions',
        'created_by',
        'version_notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'version_number' => 'integer',
        'selected_call_actions' => 'array',
    ];

    /**
     * Get the legal page that owns this version.
     */
    public function legalPage()
    {
        return $this->belongsTo(Legal::class, 'legal_page_id');
    }

    /**
     * Get the user who created this version.
     */
    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    /**
     * Scope a query to only include versions for a specific page.
     */
    public function scopeForPage($query, $legalPageId)
    {
        return $query->where('legal_page_id', $legalPageId);
    }

    /**
     * Scope a query to order by latest version first.
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('version_number', 'desc');
    }

    /**
     * Get the version label attribute.
     */
    public function getVersionLabelAttribute(): string
    {
        return "v{$this->version_number}";
    }
}
