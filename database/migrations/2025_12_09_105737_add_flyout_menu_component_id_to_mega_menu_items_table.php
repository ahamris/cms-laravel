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
            $table->unsignedBigInteger('flyout_menu_component_id')->nullable()->after('is_mega_menu');
            $table->foreign('flyout_menu_component_id')->references('id')->on('tailwind_plus')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mega_menu_items', function (Blueprint $table) {
            $table->dropForeign(['flyout_menu_component_id']);
            $table->dropColumn('flyout_menu_component_id');
        });
    }
};
