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
        Schema::create('live_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->string('slug')->unique();
            $table->dateTime('session_date');
            $table->integer('duration_minutes');
            $table->integer('max_participants')->default(50);
            $table->enum('status', ['upcoming', 'live', 'completed', 'cancelled'])->default('upcoming');
            $table->enum('type', ['introduction', 'webinar', 'workshop', 'qa'])->default('webinar');
            $table->string('meeting_url')->nullable();
            $table->string('recording_url')->nullable();
            $table->string('icon')->nullable();
            $table->string('color')->default('primary');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index(['status', 'session_date']);
            $table->index(['is_active', 'is_featured']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('live_sessions');
    }
};
