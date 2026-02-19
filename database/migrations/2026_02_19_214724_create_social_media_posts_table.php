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
            $table->bigIncrements('id');
            $table->unsignedBigInteger('social_media_platform_id')->index('social_media_posts_social_media_platform_id_foreign');
            $table->string('postable_type');
            $table->unsignedBigInteger('postable_id');
            $table->text('content');
            $table->json('media_urls')->nullable();
            $table->json('hashtags')->nullable();
            $table->string('external_post_id')->nullable();
            $table->string('external_post_url')->nullable();
            $table->enum('status', ['draft', 'scheduled', 'posted', 'failed'])->default('draft');
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('posted_at')->nullable();
            $table->json('response_data')->nullable();
            $table->text('error_message')->nullable();
            $table->json('engagement_stats')->nullable();
            $table->timestamps();

            $table->index(['postable_type', 'postable_id']);
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
