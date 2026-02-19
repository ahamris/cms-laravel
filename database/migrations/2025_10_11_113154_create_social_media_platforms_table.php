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
        Schema::create('social_media_platforms', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Platform name (Facebook, Twitter, LinkedIn, etc.)
            $table->string('slug')->unique(); // URL-friendly identifier
            $table->string('icon')->nullable(); // FontAwesome icon class
            $table->string('color', 7)->default('#000000'); // Brand color
            $table->json('api_credentials')->nullable(); // Encrypted API keys/tokens
            $table->json('settings')->nullable(); // Platform-specific settings
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_media_platforms');
    }
};
