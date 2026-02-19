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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            // Who performed the action
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_name'); // Name for display (Admin/Customer name)
            $table->string('user_type')->default('user'); // admin, user, customer, system, department

            // Simple description of what happened
            $table->text('description'); // "Admin assigned ticket to XXX admin"

            // What was acted upon (optional reference)
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->string('subject_type')->nullable(); // WooRequest, Ticket, Publication, etc.

            // When it happened
            $table->timestamp('performed_at');
            $table->timestamps();

            // Simple indexes
            $table->index('user_id');
            $table->index('user_type');
            $table->index(['subject_id', 'subject_type']);
            $table->index('performed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
