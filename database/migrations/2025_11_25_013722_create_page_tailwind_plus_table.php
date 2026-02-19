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
        Schema::create('page_tailwind_plus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->onDelete('cascade');
            $table->foreignId('tailwind_plus_id')->constrained('tailwind_plus')->onDelete('cascade');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('custom_config')->nullable();
            $table->timestamps();
            
            // Indexes for performance
            $table->index('page_id');
            $table->index('tailwind_plus_id');
            $table->index('sort_order');
            
            // Unique constraint to prevent duplicate component assignments
            $table->unique(['page_id', 'tailwind_plus_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_tailwind_plus');
    }
};
