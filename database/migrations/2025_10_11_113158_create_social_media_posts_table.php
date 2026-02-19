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
        Schema::create('social_media_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('social_media_platform_id')->constrained()->onDelete('cascade');
            $table->morphs('postable'); // Polymorphic relation (blog, page, etc.)
            $table->text('content'); // Post content/message
            $table->json('media_urls')->nullable(); // Images/videos attached
            $table->json('hashtags')->nullable(); // Hashtags array
            $table->string('external_post_id')->nullable(); // Platform's post ID
            $table->string('external_post_url')->nullable(); // Direct link to post
            $table->enum('status', ['draft', 'scheduled', 'posted', 'failed'])->default('draft');
            $table->timestamp('scheduled_at')->nullable(); // When to post
            $table->timestamp('posted_at')->nullable(); // When actually posted
            $table->json('response_data')->nullable(); // API response data
            $table->text('error_message')->nullable(); // Error details if failed
            $table->json('engagement_stats')->nullable(); // Likes, shares, comments
            $table->timestamps();

            $table->index(['status', 'scheduled_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_media_posts');
    }
};
