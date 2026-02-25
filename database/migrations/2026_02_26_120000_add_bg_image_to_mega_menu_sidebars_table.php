<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds optional background image for the right sidebar block of a parent mega menu item.
     */
    public function up(): void
    {
        Schema::table('mega_menu_sidebars', function (Blueprint $table) {
            $table->string('bg_image')->nullable()->after('tags');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mega_menu_sidebars', function (Blueprint $table) {
            $table->dropColumn('bg_image');
        });
    }
};
