<?php

namespace App\Models;

use App\Jobs\ProcessSentEmailJob;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @mixin IdeHelperSentEmail
 */
class SentEmail extends BaseModel
{
    protected $fillable = [
        'to_email',
        'cc_emails',
        'bcc_emails',
        'subject',
        'message',
        'user_id',
        'attachments',
        'attachments_count',
        'related_type',
        'related_id',
        'status',
        'error_message',
        'sent_at',
        'is_processed',
    ];

    protected $casts = [
        'cc_emails' => 'array',
        'bcc_emails' => 'array',
        'attachments' => 'array',
        'sent_at' => 'datetime',
    ];

    /**
     * Get the user who sent the email
     */
    public function sentBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the related model (contact, ticket, etc.)
     */
    public function related(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope for successful emails
     */
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    /**
     * Scope for failed emails
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for emails sent by a specific user
     */
    public function scopeSentByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for emails sent to a specific email address
     */
    public function scopeToEmail($query, $email)
    {
        return $query->where('to_email', $email);
    }

    /**
     * Get all recipients (TO, CC, BCC) as a flat array
     */
    public function getAllRecipients(): array
    {
        $recipients = [$this->to_email];

        if ($this->cc_emails) {
            $recipients = array_merge($recipients, $this->cc_emails);
        }

        if ($this->bcc_emails) {
            $recipients = array_merge($recipients, $this->bcc_emails);
        }

        return array_unique($recipients);
    }

    /**
     * Get formatted attachment info
     */
    public function getFormattedAttachments(): string
    {
        if (! $this->attachments || empty($this->attachments)) {
            return 'No attachments';
        }

        return implode(', ', array_column($this->attachments, 'name'));
    }

    /**
     * Queue this email for processing
     */
    public function queueForProcessing(): void
    {
        if ($this->status === 'pending' && ! $this->is_processed) {
            ProcessSentEmailJob::dispatch($this);
        }
    }

    /**
     * Scope for pending emails
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending')->where('is_processed', false);
    }

    /**
     * Process all pending emails
     */
    public static function processPendingEmails(int $limit = 50): int
    {
        $pendingEmails = static::pending()->limit($limit)->get();

        foreach ($pendingEmails as $email) {
            $email->queueForProcessing();
        }

        return $pendingEmails->count();
    }
}
