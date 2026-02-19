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
            $table->foreignId('academy_chapter_id')->nullable()->after('academy_category_id')
                ->constrained('academy_chapters')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('academy_videos', function (Blueprint $table) {
            $table->dropForeign(['academy_chapter_id']);
        });
    }
};
