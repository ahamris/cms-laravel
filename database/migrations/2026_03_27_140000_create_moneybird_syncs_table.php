<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('moneybird_syncs', function (Blueprint $table) {
            $table->id();
            $table->morphs('syncable');
            $table->string('resource', 64)->index();
            $table->string('external_id', 64)->nullable()->index();
            $table->string('direction', 16)->index();
            $table->string('status', 24)->default('pending')->index();
            $table->string('idempotency_key', 128)->nullable()->unique();
            $table->string('payload_hash', 64)->nullable()->index();
            $table->text('last_error')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('moneybird_syncs');
    }
};
