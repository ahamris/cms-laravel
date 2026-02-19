<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('content_plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('intent_brief_id');
            $table->enum('status', ['draft', 'pending_approval', 'approved', 'active', 'completed', 'cancelled'])->default('draft');
            $table->enum('autopilot_mode', ['assisted', 'guided', 'full_autopilot'])->default('assisted');
            $table->timestamp('approved_at')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->json('strategy_data')->nullable();
            $table->timestamps();

            $table->index(['intent_brief_id', 'status']);
            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_plans');
    }
};
