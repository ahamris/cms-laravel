<?php

namespace Database\Seeders;

use App\Models\RobotsTxt;
use Illuminate\Database\Seeder;

class RobotsTxtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Only seed if no robots.txt exists
        if (RobotsTxt::count() > 0) {
            return;
        }

        RobotsTxt::create([
            'content' => RobotsTxt::getDefaultContent(),
            'is_active' => true,
        ]);
    }
}
