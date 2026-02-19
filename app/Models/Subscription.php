<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @mixin IdeHelperSubscription
 */
class Subscription extends BaseModel
{

    protected $table = 'subscriptions';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'company_name',
        'job_title',
        'product_interest',
        'preferred_demo_date',
        'preferred_demo_time',
        'preferred_contact_method',

        // Business Information
        'company_size',
        'industry',
        'website',
        'topic',
        'message',
        'country_code',

        // Application Status
        'status',
        'admin_notes',
        'contacted_at',
        'demo_scheduled_at',
        'demo_completed_at',

        // Source tracking
        'source',
        'utm_source',
        'utm_medium',
        'utm_campaign',

        // Flags
        'is_active',
        'newsletter_consent',
        'marketing_consent',
    ];

    protected function casts(): array
    {
        return [
            'preferred_demo_date' => 'date',
            'contacted_at' => 'datetime',
            'demo_scheduled_at' => 'datetime',
            'demo_completed_at' => 'datetime',
            'is_active' => 'boolean',
            'newsletter_consent' => 'boolean',
            'marketing_consent' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the full name attribute
     */
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Get status badge color for UI
     */
    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'new' => 'primary',
            'contacted' => 'info',
            'demo_scheduled' => 'warning',
            'demo_completed' => 'success',
            'converted' => 'success',
            'rejected' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Get formatted status for display
     */
    public function getFormattedStatusAttribute(): string
    {
        return match($this->status) {
            'new' => 'New Application',
            'contacted' => 'Contacted',
            'demo_scheduled' => 'Demo Scheduled',
            'demo_completed' => 'Demo Completed',
            'converted' => 'Converted to Customer',
            'rejected' => 'Rejected',
            default => ucfirst($this->status)
        };
    }

    /**
     * Scope for filtering by status
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for active applications
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for recent applications (last 30 days)
     */
    public function scopeRecent($query)
    {
        return $query->where('created_at', '>=', Carbon::now()->subDays(30));
    }

    /**
     * Mark application as contacted
     */
    public function markAsContacted(): void
    {
        $this->update([
            'status' => 'contacted',
            'contacted_at' => now()
        ]);
    }

    /**
     * Schedule demo for this application
     */
    public function scheduleDemo(Carbon $scheduledAt): void
    {
        $this->update([
            'status' => 'demo_scheduled',
            'demo_scheduled_at' => $scheduledAt
        ]);
    }

    /**
     * Mark demo as completed
     */
    public function completeDemo(): void
    {
        $this->update([
            'status' => 'demo_completed',
            'demo_completed_at' => now()
        ]);
    }

    /**
     * Convert application to customer
     */
    public function convertToCustomer(): void
    {
        $this->update(['status' => 'converted']);
    }

    /**
     * Reject application
     */
    public function reject(): void
    {
        $this->update(['status' => 'rejected']);
    }

    public function trial()
    {
        return $this->hasOne(SubscriptionTrial::class);
    }
}
