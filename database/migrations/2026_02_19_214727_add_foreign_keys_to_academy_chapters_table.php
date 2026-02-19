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
        Schema::table('academy_chapters', function (Blueprint $table) {
            $table->foreign(['academy_category_id'])->references(['id'])->on('academy_categories')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('academy_chapters', function (Blueprint $table) {
            $table->dropForeign('academy_chapters_academy_category_id_foreign');
        });
    }
};
