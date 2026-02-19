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
        Schema::create('tailwind_plus', function (Blueprint $table) {
            $table->id();
            $table->string('category')->nullable()->index(); // e.g., "Application Shells"
            $table->string('component_group')->nullable()->index(); // e.g., "Multi-Column Layouts"
            $table->string('component_name')->index(); // e.g., "Constrained three column"
            $table->longText('code'); // The actual code/HTML
            $table->longText('preview')->nullable(); // Preview HTML
            $table->integer('version')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Indexes for faster queries
            $table->index(['category', 'component_group']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tailwind_plus');
    }
};
