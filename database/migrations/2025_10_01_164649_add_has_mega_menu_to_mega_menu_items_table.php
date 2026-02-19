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
            $table->boolean('has_mega_menu')->default(false)->after('is_section_header');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mega_menu_items', function (Blueprint $table) {
            $table->dropColumn('has_mega_menu');
        });
    }
};
