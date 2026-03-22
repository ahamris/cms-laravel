<?php
// ══════════════════════════════════════════════════════
// FILE: app/Models/CrmDeal.php
// ══════════════════════════════════════════════════════

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * CRM Deal Model
 *
 * Tracks pipeline deals across stages:
 * lead → qualified → proposal → negotiation → won / lost
 *
 * Linked to Contact model.
 * Maps to the Close (activeer) phase of the inbound funnel.
 */
class CrmDeal extends BaseModel
{
    protected $table = 'crm_deals';

    protected $fillable = [
        'contact_id',
        'title',
        'description',
        'stage',          // lead | qualified | proposal | negotiation | won | lost
        'value',          // monthly recurring value in cents
        'currency',       // EUR, USD
        'probability',    // 0-100 close probability
        'expected_close_date',
        'closed_at',
        'lost_reason',
        'funnel_fase',    // mirrors contact funnel stage: activeer (close phase)
        'assigned_to',    // user_id
        'is_active',
    ];

    protected $casts = [
        'value'               => 'integer',
        'probability'         => 'integer',
        'expected_close_date' => 'date',
        'closed_at'           => 'datetime',
        'is_active'           => 'boolean',
    ];

    // ── Relationships ──────────────────────────────────────────

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

    // ── Scopes ────────────────────────────────────────────────

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

    // ── Helpers ───────────────────────────────────────────────

    public function getFormattedValueAttribute(): string
    {
        return '€' . number_format($this->value / 100, 0, ',', '.');
    }

    public function getStagesAttribute(): array
    {
        return ['lead', 'qualified', 'proposal', 'negotiation', 'won', 'lost'];
    }

    public function markWon(): void
    {
        $this->update([
            'stage'     => 'won',
            'closed_at' => now(),
        ]);

        // Move linked contact to delight (inspireer) stage
        if ($this->contact) {
            $this->contact->update(['funnel_fase' => 'inspireer']);
        }
    }

    public function markLost(string $reason = null): void
    {
        $this->update([
            'stage'       => 'lost',
            'closed_at'   => now(),
            'lost_reason' => $reason,
        ]);
    }
}
