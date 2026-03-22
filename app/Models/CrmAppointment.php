<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrmAppointment extends BaseModel
{
    use SoftDeletes;

    protected $table = 'crm_appointments';

    protected $fillable = [
        'contact_id', 'deal_id', 'assigned_to', 'title', 'notes',
        'type', 'starts_at', 'ends_at', 'status', 'location', 'is_online',
    ];

    protected function casts(): array
    {
        return [
            'starts_at'  => 'datetime',
            'ends_at'    => 'datetime',
            'is_online'  => 'boolean',
        ];
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function deal(): BelongsTo
    {
        return $this->belongsTo(CrmDeal::class, 'deal_id');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('starts_at', '>=', now())
                     ->where('status', 'scheduled')
                     ->orderBy('starts_at');
    }

    public function scopeInRange($query, $start, $end)
    {
        return $query->whereBetween('starts_at', [$start, $end]);
    }

    public function complete(): void
    {
        $this->update(['status' => 'completed']);
    }

    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
    }

    public static function typeLabels(): array
    {
        return [
            'demo'        => 'Demo',
            'call'        => 'Call',
            'follow_up'   => 'Follow-up',
            'onboarding'  => 'Onboarding',
            'meeting'     => 'Meeting',
            'other'       => 'Other',
        ];
    }
}
