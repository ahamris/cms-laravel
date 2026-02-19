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
        Schema::create('abouts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('anchor')->unique(); // For #anchor navigation
            $table->string('nav_title'); // Title shown in navigation
            $table->string('title'); // Main heading
            $table->text('subtitle')->nullable(); // Description
            $table->text('short_body')->nullable(); // Short content
            $table->text('long_body')->nullable(); // Long content
            $table->json('list_items')->nullable(); // Bullet points
            $table->string('link_text')->nullable(); // Link text
            $table->text('testimonial_quote')->nullable(); // Testimonial text
            $table->string('testimonial_author')->nullable(); // Author name
            $table->string('testimonial_company')->nullable(); // Company name
            $table->string('image')->nullable(); // Image path
            $table->enum('image_position', ['left', 'right'])->default('right'); // Image position
            $table->integer('sort_order')->default(0); // Display order
            $table->boolean('is_active')->default(true); // Active status
            $table->string('slug')->nullable(); // URL slug
            $table->string('meta_title')->nullable(); // SEO meta title
            $table->text('meta_description')->nullable(); // SEO meta description
            $table->text('meta_keywords')->nullable(); // SEO meta keywords
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abouts');
    }
};
