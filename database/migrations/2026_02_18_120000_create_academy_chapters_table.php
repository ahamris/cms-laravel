<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Consolidated: academy_chapters, add academy_chapter_id to academy_videos, add image_path to academy_categories.
     */
    public function up(): void
    {
        Schema::create('academy_chapters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academy_category_id')->constrained('academy_categories')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['academy_category_id', 'sort_order']);
        });

        Schema::table('academy_videos', function (Blueprint $table) {
            $table->foreignId('academy_chapter_id')->nullable()->after('academy_category_id')
                ->constrained('academy_chapters')->nullOnDelete();
        });

        Schema::table('academy_categories', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('academy_categories', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });

        Schema::table('academy_videos', function (Blueprint $table) {
            $table->dropForeign(['academy_chapter_id']);
        });

        Schema::dropIfExists('academy_chapters');
    }
};
