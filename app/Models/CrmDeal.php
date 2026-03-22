<?php

namespace App\Models;

use App\Events\DealStageChanged;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrmDeal extends BaseModel
{
    use SoftDeletes;

    protected $table = 'crm_deals';

    protected $fillable = [
        'contact_id', 'assigned_to', 'title', 'description',
        'stage', 'value', 'currency', 'probability',
        'expected_close_date', 'closed_at', 'lost_reason', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'value'               => 'integer',
            'probability'         => 'integer',
            'expected_close_date' => 'date',
            'closed_at'           => 'datetime',
            'is_active'           => 'boolean',
        ];
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(CrmNote::class, 'deal_id');
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(CrmAppointment::class, 'deal_id');
    }

    public function scopeOpen($query)
    {
        return $query->whereNotIn('stage', ['won', 'lost']);
    }

    public function scopeWon($query)
    {
        return $query->where('stage', 'won');
    }

    public function scopeByStage($query, string $stage)
    {
        return $query->where('stage', $stage);
    }

    public function getFormattedValueAttribute(): string
    {
        return '€' . number_format($this->value / 100, 0, ',', '.');
    }

    public static function stageLabels(): array
    {
        return [
            'lead'        => 'Lead',
            'qualified'   => 'Qualified',
            'proposal'    => 'Proposal',
            'negotiation' => 'Negotiation',
            'won'         => 'Won',
            'lost'        => 'Lost',
        ];
    }

    public function moveToStage(string $stage): void
    {
        $oldStage = $this->stage;
        $this->update(['stage' => $stage]);
        DealStageChanged::dispatch($this, $oldStage, $stage);
    }

    public function markWon(): void
    {
        $oldStage = $this->stage;
        $this->update(['stage' => 'won', 'closed_at' => now()]);

        if ($this->contact) {
            $this->contact->update(['funnel_fase' => 'inspireer', 'lifecycle_stage' => 'customer']);
        }

        DealStageChanged::dispatch($this, $oldStage, 'won');
    }

    public function markLost(?string $reason = null): void
    {
        $oldStage = $this->stage;
        $this->update([
            'stage'       => 'lost',
            'closed_at'   => now(),
            'lost_reason' => $reason,
        ]);
        DealStageChanged::dispatch($this, $oldStage, 'lost');
    }
}
