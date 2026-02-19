<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('solutions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            
            // Basic content
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('short_description');
            $table->longText('long_description');
            $table->text('features')->nullable(); // JSON array of features
            
            // Media
            $table->string('icon')->nullable(); // FontAwesome icon or image
            $table->string('image')->nullable(); // Main image
            $table->json('gallery')->nullable(); // Multiple images
            $table->string('video_url')->nullable(); // YouTube/Vimeo URL
            
            // Business details
            $table->string('category')->nullable(); // CRM, Invoicing, Project Management, etc.
            $table->json('benefits')->nullable(); // Array of key benefits
            $table->json('use_cases')->nullable(); // Array of use cases
            $table->text('target_audience')->nullable();
            
            // Pricing & CTA
            $table->decimal('price', 10, 2)->nullable();
            $table->string('price_type')->nullable(); // 'free', 'monthly', 'yearly', 'one-time', 'custom'
            $table->string('cta_text')->default('Get started');
            $table->string('cta_url')->nullable();
            $table->boolean('has_free_trial')->default(false);
            $table->integer('trial_days')->nullable();
            
            // SEO & Meta fields
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('meta_canonical_url')->nullable();
            $table->string('meta_robots')->default('index,follow');
            
            // Open Graph
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('og_type')->default('article');
            
            // Twitter Card
            $table->string('twitter_card')->default('summary_large_image');
            $table->string('twitter_title')->nullable();
            $table->text('twitter_description')->nullable();
            $table->string('twitter_image')->nullable();
            
            // Status & visibility
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_popular')->default(false);
            $table->integer('sort_order')->default(0);
            
            // Timestamps for scheduling
            $table->timestamp('published_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solutions');
    }
};
