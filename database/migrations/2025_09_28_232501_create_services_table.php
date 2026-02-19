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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            
            // Category relationship
            $table->foreignId('service_category_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();
            
            // Basic content
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('short_body');
            $table->longText('long_body');
            
            // Media
            $table->string('image')->nullable();
            $table->json('gallery')->nullable(); // Multiple images
            
            // SEO & Google Meta fields
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('meta_canonical_url')->nullable();
            $table->string('meta_robots')->default('index,follow');
            $table->string('og_title')->nullable(); // Open Graph
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('og_type')->default('article');
            $table->string('twitter_card')->default('summary_large_image');
            $table->string('twitter_title')->nullable();
            $table->text('twitter_description')->nullable();
            $table->string('twitter_image')->nullable();
            
            // Pricing (optional)
            $table->decimal('price', 10, 2)->nullable();
            $table->string('price_type')->nullable(); // 'fixed', 'hourly', 'monthly', 'custom'
            $table->text('price_description')->nullable();
            
            // Status & visibility
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
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
        Schema::dropIfExists('services');
    }
};
