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
        Schema::table('academy_videos', function (Blueprint $table) {
            $table->foreign(['academy_category_id'])->references(['id'])->on('academy_categories')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['academy_chapter_id'])->references(['id'])->on('academy_chapters')->onUpdate('restrict')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('academy_videos', function (Blueprint $table) {
            $table->dropForeign('academy_videos_academy_category_id_foreign');
            $table->dropForeign('academy_videos_academy_chapter_id_foreign');
        });
    }
};
