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
        Schema::table('mega_menu_items', function (Blueprint $table) {
            $table->foreign(['page_id'])->references(['id'])->on('pages')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['parent_id'])->references(['id'])->on('mega_menu_items')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mega_menu_items', function (Blueprint $table) {
            $table->dropForeign('mega_menu_items_page_id_foreign');
            $table->dropForeign('mega_menu_items_parent_id_foreign');
        });
    }
};
