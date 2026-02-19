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
        $columnsToDrop = [
            'flyout_menu_component_id',
            'footer_action_1_text',
            'footer_action_1_icon',
            'footer_action_1_url',
            'footer_action_2_text',
            'footer_action_2_icon',
            'footer_action_2_url',
        ];

        foreach ($columnsToDrop as $column) {
            if (Schema::hasColumn('mega_menu_items', $column)) {
                Schema::table('mega_menu_items', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mega_menu_items', function (Blueprint $table) {
            $table->unsignedBigInteger('flyout_menu_component_id')->nullable()->after('is_mega_menu');
            $table->string('footer_action_1_text')->nullable()->after('flyout_menu_component_id');
            $table->string('footer_action_1_icon')->nullable()->after('footer_action_1_text');
            $table->string('footer_action_1_url')->nullable()->after('footer_action_1_icon');
            $table->string('footer_action_2_text')->nullable()->after('footer_action_1_url');
            $table->string('footer_action_2_icon')->nullable()->after('footer_action_2_text');
            $table->string('footer_action_2_url')->nullable()->after('footer_action_2_icon');
        });
    }
};
