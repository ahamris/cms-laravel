<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Seeders are skipped when running in production.
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
            StickMenuSeeder::class,
            ExternalCodeSeeder::class,
            RobotsTxtSeeder::class,
            MegaMenuSeeder::class,
            PageSeeder::class,
            BlogContentSeeder::class,
            AcademySeeder::class,
            ChangelogSeeder::class,
            VacancySeeder::class,
        ]);
    }
}
