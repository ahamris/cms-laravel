<?php

namespace App\Services\Moneybird;

use App\Models\MoneybirdSync;
use Illuminate\Database\Eloquent\Model;

/**
 * Persists sync attempts for reconciliation, webhooks, and idempotent jobs.
 */
final class MoneybirdSyncLedger
{
    public function startOutbound(
        Model $syncable,
        string $resource,
        ?string $idempotencyKey = null,
        ?string $payloadHash = null,
        ?array $meta = null,
    ): MoneybirdSync {
        return MoneybirdSync::query()->create([
            'syncable_type' => $syncable->getMorphClass(),
            'syncable_id' => $syncable->getKey(),
            'resource' => $resource,
            'direction' => MoneybirdSync::DIRECTION_OUTBOUND,
            'status' => MoneybirdSync::STATUS_PENDING,
            'idempotency_key' => $idempotencyKey,
            'payload_hash' => $payloadHash,
            'meta' => $meta,
        ]);
    }

    public function markSynced(MoneybirdSync $row, string $externalId, ?array $meta = null): void
    {
        $row->update([
            'status' => MoneybirdSync::STATUS_SYNCED,
            'external_id' => $externalId,
            'synced_at' => now(),
            'last_error' => null,
            'meta' => $meta !== null ? array_merge($row->meta ?? [], $meta) : $row->meta,
        ]);
    }

    public function markFailed(MoneybirdSync $row, string $message): void
    {
        $row->update([
            'status' => MoneybirdSync::STATUS_FAILED,
            'last_error' => $message,
        ]);
    }

    public function findByIdempotencyKey(string $key): ?MoneybirdSync
    {
        return MoneybirdSync::query()->where('idempotency_key', $key)->first();
    }
}
