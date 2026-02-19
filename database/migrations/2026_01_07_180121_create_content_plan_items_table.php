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
        Schema::create('content_plan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_plan_id')->constrained()->onDelete('cascade');
            $table->enum('item_type', ['pillar', 'supporting', 'social', 'evergreen'])->default('supporting');
            $table->enum('status', ['planned', 'generating', 'draft', 'scheduled', 'published', 'failed'])->default('planned');
            $table->integer('priority')->default(0); // Higher number = higher priority
            $table->timestamp('scheduled_at')->nullable();
            $table->json('content_data')->nullable(); // Title, keywords, brief, etc.
            $table->unsignedBigInteger('related_content_id')->nullable(); // ID of created blog/post
            $table->string('related_content_type')->nullable(); // Blog, SocialMediaPost, etc.
            $table->timestamps();
            
            $table->index(['content_plan_id', 'status']);
            $table->index(['scheduled_at', 'status']);
            $table->index(['item_type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_plan_items');
    }
};
