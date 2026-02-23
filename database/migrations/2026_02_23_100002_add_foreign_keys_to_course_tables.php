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
        Schema::table('courses', function (Blueprint $table) {
            $table->foreign(['course_category_id'])->references(['id'])->on('course_categories')->onUpdate('restrict')->onDelete('cascade');
        });

        Schema::table('course_videos', function (Blueprint $table) {
            $table->foreign(['course_category_id'])->references(['id'])->on('course_categories')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['course_id'])->references(['id'])->on('courses')->onUpdate('restrict')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_videos', function (Blueprint $table) {
            $table->dropForeign('course_videos_course_category_id_foreign');
            $table->dropForeign('course_videos_course_id_foreign');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign('courses_course_category_id_foreign');
        });
    }
};
