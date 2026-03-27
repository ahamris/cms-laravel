<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Ledger row for outbound/inbound Moneybird sync attempts (idempotency, status, errors).
 *
 * @mixin IdeHelperMoneybirdSync
 */
class MoneybirdSync extends BaseModel
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_SYNCED = 'synced';

    public const STATUS_FAILED = 'failed';

    public const STATUS_CONFLICT = 'conflict';

    public const DIRECTION_OUTBOUND = 'outbound';

    public const DIRECTION_INBOUND = 'inbound';

    protected $table = 'moneybird_syncs';

    protected $fillable = [
        'syncable_type',
        'syncable_id',
        'resource',
        'external_id',
        'direction',
        'status',
        'idempotency_key',
        'payload_hash',
        'last_error',
        'synced_at',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'synced_at' => 'datetime',
            'meta' => 'array',
        ];
    }

    public function syncable(): MorphTo
    {
        return $this->morphTo();
    }
}
