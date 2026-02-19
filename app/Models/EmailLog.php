<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @mixin IdeHelperEmailLog
 */
class EmailLog extends BaseModel
{
    protected $table = 'email_logs';

    protected $fillable = [
        'subject',
        'to_email',
        'to_name',
        'from_email',
        'from_name',
        'cc',
        'bcc',
        'body_html',
        'body_text',
        'mail_class',
        'status',
        'error_message',
        'sent_at',
        'failed_at',
        'related_type',
        'related_id',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
            'failed_at' => 'datetime',
            'metadata' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the related model (polymorphic).
     */
    public function related(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Mark email as sent.
     */
    public function markAsSent(): void
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    /**
     * Mark email as failed.
     */
    public function markAsFailed(string $errorMessage): void
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage,
            'failed_at' => now(),
        ]);
    }

    /**
     * Scope for sent emails.
     */
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    /**
     * Scope for failed emails.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for pending emails.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for filtering by email.
     */
    public function scopeToEmail($query, string $email)
    {
        return $query->where('to_email', $email);
    }

    /**
     * Scope for filtering by mail class.
     */
    public function scopeByMailClass($query, string $mailClass)
    {
        return $query->where('mail_class', $mailClass);
    }

    /**
     * Get status badge color for UI.
     */
    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'sent' => 'success',
            'failed' => 'danger',
            'pending' => 'warning',
            default => 'secondary'
        };
    }
}
