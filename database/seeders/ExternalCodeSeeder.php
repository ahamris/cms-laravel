<?php

namespace Database\Seeders;

use App\Models\ExternalCode;
use Illuminate\Database\Seeder;

class ExternalCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (app()->environment('production')) {
            return;
        }

        // External code seeder is disabled by default
        // Uncomment and customize the code below to add external scripts/widgets

        // Example: LiveChat Widget
        // ExternalCode::create([
        //     'name' => 'LiveChat Widget',
        //     'content' => '<script>
        //         window.BeChatSettings = {
        //             widgetDomain: "https://your-domain.com",
        //         };
        //     </script>
        //     <script src="https://your-domain.com/livechat-loader.js"></script>',
        //     'before_header' => false,
        //     'before_body' => true,
        //     'is_active' => true,
        //     'sort_order' => 1,
        // ]);

    }
}
