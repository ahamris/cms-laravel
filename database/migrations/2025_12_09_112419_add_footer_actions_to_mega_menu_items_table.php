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
            $table->string('footer_action_1_text')->nullable()->after('flyout_menu_component_id');
            $table->string('footer_action_1_url')->nullable()->after('footer_action_1_text');
            $table->string('footer_action_2_text')->nullable()->after('footer_action_1_url');
            $table->string('footer_action_2_url')->nullable()->after('footer_action_2_text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mega_menu_items', function (Blueprint $table) {
            $table->dropColumn([
                'footer_action_1_text',
                'footer_action_1_url',
                'footer_action_2_text',
                'footer_action_2_url',
            ]);
        });
    }
};
