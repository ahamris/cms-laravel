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
        Schema::create('session_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('live_session_id')->constrained()->onDelete('cascade');
            $table->string('organization');
            $table->string('name');
            $table->string('email');
            $table->boolean('marketing_consent')->default(false);
            $table->enum('status', ['registered', 'attended', 'no_show', 'cancelled'])->default('registered');
            $table->timestamp('registered_at');
            $table->timestamp('attended_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['live_session_id', 'email']);
            $table->index(['live_session_id', 'status']);
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_registrations');
    }
};
