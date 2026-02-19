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
        Schema::create('hero_media_widgets', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            // Top Header
            $table->string('top_header_icon')->nullable();
            $table->string('top_header_text')->nullable();
            $table->string('top_header_url')->nullable();
            $table->string('top_header_text_color')->nullable();
            $table->string('top_header_bg_color')->nullable();

            // Title & Subtitle
            $table->string('title')->nullable();
            $table->string('title_color')->nullable();
            $table->text('subtitle')->nullable();
            $table->string('subtitle_color')->nullable();

            // Slogan
            $table->string('slogan')->nullable();
            $table->string('slogan_color')->nullable();

            // List Items (JSON)
            $table->json('list_items')->nullable();
            $table->string('list_item_color')->nullable();
            $table->string('list_item_icon')->nullable();

            // Primary Button
            $table->string('primary_button_text')->nullable();
            $table->string('primary_button_url')->nullable();
            $table->string('primary_button_text_color')->nullable();
            $table->string('primary_button_bg_color')->nullable();
            $table->string('primary_button_icon')->nullable();

            // Secondary Button
            $table->string('secondary_button_text')->nullable();
            $table->string('secondary_button_url')->nullable();
            $table->string('secondary_button_text_color')->nullable();
            $table->string('secondary_button_bg_color')->nullable();
            $table->string('secondary_button_border_color')->nullable();
            $table->string('secondary_button_icon')->nullable();

            // Component Settings
            $table->string('component_type')->nullable();
            $table->integer('height')->nullable();
            $table->boolean('full_height')->default(false);

            // Background
            $table->string('background_type')->default('image'); // 'image' or 'video'
            $table->string('video_url')->nullable();
            $table->string('image')->nullable();

            // Ensure only one record exists
            $table->boolean('is_active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hero_media_widgets');
    }
};
