<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Removes footer CTA settings (footer_cta_title, footer_cta_subtitle, etc.).
     */
    public function up(): void
    {
        DB::table('settings')
            ->whereIn('key', [
                'footer_cta_title',
                'footer_cta_subtitle',
                'footer_cta_description',
                'footer_cta_button_text',
                'footer_cta_button_url',
            ])
            ->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $keys = [
            ['key' => 'footer_cta_title', 'value' => 'Get started', 'display_name' => 'Footer CTA Title', 'description' => 'Title for the footer call-to-action section', 'order' => 1],
            ['key' => 'footer_cta_subtitle', 'value' => 'Boost your productivity. Start using our app today.', 'display_name' => 'Footer CTA Subtitle', 'description' => 'Subtitle for the footer call-to-action section', 'order' => 2],
            ['key' => 'footer_cta_description', 'value' => 'Incididunt sint fugiat pariatur cupidatat consectetur sit cillum anim id veniam aliqua proident excepteur commodo do ea.', 'display_name' => 'Footer CTA Description', 'description' => 'Description for the footer call-to-action section', 'order' => 3],
            ['key' => 'footer_cta_button_text', 'value' => 'Get started', 'display_name' => 'Footer CTA Button Text', 'description' => 'Text for the footer call-to-action button', 'order' => 4],
            ['key' => 'footer_cta_button_url', 'value' => '#', 'display_name' => 'Footer CTA Button URL', 'description' => 'URL for the footer call-to-action button', 'order' => 5],
        ];

        $now = now();
        foreach ($keys as $row) {
            if (DB::table('settings')->where('key', $row['key'])->doesntExist()) {
                DB::table('settings')->insert([
                    'key' => $row['key'],
                    'value' => $row['value'],
                    'type' => $row['key'] === 'footer_cta_description' ? 'textarea' : 'text',
                    'group' => 'footer',
                    'display_name' => $row['display_name'],
                    'description' => $row['description'],
                    'order' => $row['order'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
};
