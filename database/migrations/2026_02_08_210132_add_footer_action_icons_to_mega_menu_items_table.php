<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('mega_menu_items', function (Blueprint $table) {
            $table->string('footer_action_1_icon')->nullable()->after('footer_action_1_url');
            $table->string('footer_action_2_icon')->nullable()->after('footer_action_2_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mega_menu_items', function (Blueprint $table) {
            $table->dropColumn(['footer_action_1_icon', 'footer_action_2_icon']);
        });
    }
};
