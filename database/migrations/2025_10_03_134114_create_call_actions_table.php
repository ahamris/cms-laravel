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
        Schema::create('call_actions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('primary_button_text')->nullable();
            $table->string('primary_button_url')->nullable();
            $table->boolean('primary_button_external')->default(false);
            $table->string('secondary_button_text')->nullable();
            $table->string('secondary_button_url')->nullable();
            $table->boolean('secondary_button_external')->default(false);
            $table->string('background_color')->default('#1e40af'); // Default blue
            $table->string('text_color')->default('#ffffff'); // Default white
            $table->string('price_text')->nullable(); // e.g., "Prices from € 37 per month"
            $table->string('price_button_text')->nullable(); // e.g., "Calculate your price now"
            $table->string('price_button_url')->nullable();
            $table->string('stats_number')->nullable(); // e.g., "15,000"
            $table->string('stats_text')->nullable(); // e.g., "clients already work with 'the real deal'."
            $table->string('stats_button_text')->nullable(); // e.g., "See for yourself"
            $table->string('stats_button_url')->nullable();
            $table->string('section_identifier')->unique(); // Unique identifier for the section
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('call_actions');
    }
};
