<?php

namespace App\Models;


/**
 * @mixin IdeHelperFormSubmission
 */
class FormSubmission extends BaseModel
{
    protected $fillable = [
        'form_builder_id',
        'data',
        'ip_address',
        'user_agent',
        'is_read',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
    ];

    /**
     * Relationship: Form builder
     */
    public function formBuilder()
    {
        return $this->belongsTo(FormBuilder::class, 'form_builder_id');
    }

    /**
     * Scope: Unread submissions
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope: Read submissions
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Mark as read
     */
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    /**
     * Mark as unread
     */
    public function markAsUnread()
    {
        $this->update(['is_read' => false]);
    }
}
