<?php

use App\Models\MoneybirdSync;
use App\Models\User;
use App\Services\Moneybird\MoneybirdSyncLedger;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('records outbound sync and marks synced', function (): void {
    $user = User::factory()->create();
    $ledger = new MoneybirdSyncLedger;
    $row = $ledger->startOutbound($user, 'contact', 'idem-test-1');

    expect($row)->toBeInstanceOf(MoneybirdSync::class);
    expect($row->status)->toBe(MoneybirdSync::STATUS_PENDING);
    expect($row->direction)->toBe(MoneybirdSync::DIRECTION_OUTBOUND);

    $ledger->markSynced($row, 'mb-999');
    $row->refresh();

    expect($row->status)->toBe(MoneybirdSync::STATUS_SYNCED);
    expect($row->external_id)->toBe('mb-999');
    expect($row->synced_at)->not->toBeNull();
});

it('finds row by idempotency key', function (): void {
    $user = User::factory()->create();
    $ledger = new MoneybirdSyncLedger;
    $ledger->startOutbound($user, 'contact', 'unique-key-xyz');

    $found = $ledger->findByIdempotencyKey('unique-key-xyz');

    expect($found)->not->toBeNull();
    expect($found->idempotency_key)->toBe('unique-key-xyz');
});
