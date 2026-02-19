<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperContactFormMessage
 */
class ContactFormMessage extends BaseModel
{
    protected $fillable = [
        'contact_form_id',
        'user_id',
        'direction',
        'subject',
        'message',
        'attachments',
        'sent_at',
        'status',
        'error_message',
    ];

    protected $casts = [
        'attachments' => 'array',
        'sent_at' => 'datetime',
    ];

    /**
     * Get the contact form that owns this message
     */
    public function contactForm(): BelongsTo
    {
        return $this->belongsTo(ContactForm::class);
    }

    /**
     * Get the user who sent this message (admin)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for inbound messages
     */
    public function scopeInbound($query)
    {
        return $query->where('direction', 'inbound');
    }

    /**
     * Scope for outbound messages
     */
    public function scopeOutbound($query)
    {
        return $query->where('direction', 'outbound');
    }

    /**
     * Scope for sent messages
     */
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    /**
     * Scope for failed messages
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
}
