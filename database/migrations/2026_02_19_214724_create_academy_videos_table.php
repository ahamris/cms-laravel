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
        Schema::create('academy_videos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('academy_category_id');
            $table->unsignedBigInteger('academy_chapter_id')->nullable()->index('academy_videos_academy_chapter_id_foreign');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('video_path')->nullable()->comment('Uploaded video file path in storage');
            $table->string('video_url')->nullable()->comment('External video URL (YouTube, Vimeo, etc.)');
            $table->string('thumbnail_path')->nullable();
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['academy_category_id', 'is_active', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academy_videos');
    }
};
