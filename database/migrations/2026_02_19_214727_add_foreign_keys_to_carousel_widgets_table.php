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
        Schema::table('carousel_widgets', function (Blueprint $table) {
            $table->foreign(['blog_category_id'])->references(['id'])->on('blog_categories')->onUpdate('restrict')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carousel_widgets', function (Blueprint $table) {
            $table->dropForeign('carousel_widgets_blog_category_id_foreign');
        });
    }
};
