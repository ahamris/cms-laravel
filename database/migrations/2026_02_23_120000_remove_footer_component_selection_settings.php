<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Removes flyout/footer component selection settings (no longer used).
     */
    public function up(): void
    {
        DB::table('settings')
            ->whereIn('key', ['site_footer_component_id', 'site_footer_layout_type'])
            ->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optional: re-create keys with empty values if rollback is needed.
        foreach (['site_footer_component_id', 'site_footer_layout_type'] as $key) {
            if (DB::table('settings')->where('key', $key)->doesntExist()) {
                DB::table('settings')->insert([
                    'key' => $key,
                    'value' => null,
                    'type' => 'text',
                    'group' => 'general',
                    'display_name' => ucfirst(str_replace('_', ' ', $key)),
                    'description' => null,
                    'order' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
};
