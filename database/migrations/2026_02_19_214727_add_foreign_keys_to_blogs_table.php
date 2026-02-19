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
        Schema::table('blogs', function (Blueprint $table) {
            $table->foreign(['author_id'])->references(['id'])->on('users')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['blog_category_id'])->references(['id'])->on('blog_categories')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['content_plan_id'])->references(['id'])->on('content_plans')->onUpdate('restrict')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropForeign('blogs_author_id_foreign');
            $table->dropForeign('blogs_blog_category_id_foreign');
            $table->dropForeign('blogs_content_plan_id_foreign');
        });
    }
};
