<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            SettingSeeder::class,
            AdminSeeder::class,
            MailSettingSeeder::class,
            FooterLinkSeeder::class,
            PricingPlanSeeder::class,
            PricingBoosterSeeder::class,
            PricingFeatureSeeder::class,
            AboutSeeder::class,
            StickMenuSeeder::class,
            ExternalCodeSeeder::class,
            RobotsTxtSeeder::class,
            MegaMenuSeeder::class,
            FormBuilderSeeder::class,
            PageSeeder::class,
            TailwindPlusSeeder::class,
        ]);
    }
}
