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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            
            // Images
            $table->string('cover_image')->nullable();
            $table->string('image')->nullable();
            
            // Basic Information
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('short_body')->nullable();
            $table->longText('long_body')->nullable();
            $table->text('description')->nullable();
            
            // Event Scheduling
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            
            // Location Information
            $table->string('location')->nullable();
            $table->text('address')->nullable();
            
            // Registration & Pricing
            $table->decimal('price', 10, 2)->nullable();
            $table->string('registration_url')->nullable();
            
            // Status & User
            $table->boolean('is_active')->default(true);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Timestamps & Soft Deletes
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['is_active', 'start_date']);
            $table->index('start_date');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
