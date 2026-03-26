<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Drops academy tables (do not modify original academy migrations).
     */
    public function up(): void
    {
        if (Schema::hasTable('academy_videos')) {
            Schema::table('academy_videos', function (Blueprint $table) {
                // SQLite can't drop foreign keys by constraint name. Dropping by column works across drivers.
                $table->dropForeign(['academy_category_id']);
                $table->dropForeign(['academy_chapter_id']);
            });
        }
        if (Schema::hasTable('academy_chapters')) {
            Schema::table('academy_chapters', function (Blueprint $table) {
                $table->dropForeign(['academy_category_id']);
            });
        }

        Schema::dropIfExists('academy_videos');
        Schema::dropIfExists('academy_chapters');
        Schema::dropIfExists('academy_categories');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore is handled by original academy migrations; this migration does not recreate tables.
    }
};
