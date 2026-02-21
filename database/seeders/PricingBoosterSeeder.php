<?php

namespace Database\Seeders;

use App\Models\PricingBooster;
use Illuminate\Database\Seeder;

class PricingBoosterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (app()->environment('production')) {
            return;
        }

        if (PricingBooster::count() > 0) {
            return;
        }

        // Maintenance packages for configurator
        PricingBooster::create([
            'name' => 'Onderhoud',
            'slug' => 'onderhoud',
            'price' => 40000.00,
            'description' => 'Actieve monitoring en basis onderhoud voor een stabiel en veilig platform.',
            'link_text' => 'Meer informatie →',
            'link_url' => '/contact',
            'footnote' => '*Prijs exclusief BTW, facturatie per jaar',
            'sort_order' => 0,
            'is_active' => true,
        ]);

        PricingBooster::create([
            'name' => 'Onderhoud+',
            'slug' => 'onderhoud-plus',
            'price' => 80000.00,
            'description' => 'Inclusief 8-16 uur/maand voor DevOps, koppelingen en procesoptimalisatie.',
            'link_text' => 'Meer informatie →',
            'link_url' => '/contact',
            'footnote' => '*Prijs exclusief BTW, facturatie per jaar',
            'sort_order' => 0,
            'is_active' => true,
        ]);

        // Project management Booster
        PricingBooster::create([
            'name' => 'Project management Booster',
            'slug' => 'project-management-booster',
            'price' => 35.00,
            'description' => 'Flexibly manage projects and deliver in time and on budget.',
            'link_text' => 'Read more →',
            'link_url' => '/trial',
            'footnote' => '*Price excludes VAT per account, invoicing per year',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // Planning Booster
        PricingBooster::create([
            'name' => 'Planning Booster',
            'slug' => 'planning-booster',
            'price' => 35.00,
            'description' => 'Easily gain an insight into your team\'s workload, and see who has the capacity to muck in when those unexpected situations crop up.',
            'link_text' => 'Read more →',
            'link_url' => '/trial',
            'footnote' => '*Price excludes VAT per account, invoicing per year',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // Lead capture Booster
        PricingBooster::create([
            'name' => 'Lead capture Booster',
            'slug' => 'lead-capture-booster',
            'price' => 20.00,
            'description' => 'Use powerful contact forms and meeting scheduling to turn more leads into customers.',
            'link_text' => 'Read more →',
            'link_url' => '/trial',
            'footnote' => '*Price excludes VAT per account, invoicing per year',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        // Work orders Booster
        PricingBooster::create([
            'name' => 'Work orders Booster',
            'slug' => 'work-orders-booster',
            'price' => 20.00,
            'description' => 'Quickly draw up work orders on site and get them signed by your customer there and then.',
            'link_text' => 'Read more →',
            'link_url' => '/trial',
            'footnote' => '*Price excludes VAT per account, invoicing per year',
            'sort_order' => 4,
            'is_active' => true,
        ]);

        // Shared inbox Booster
        PricingBooster::create([
            'name' => 'Shared inbox Booster',
            'slug' => 'shared-inbox-booster',
            'price' => 50.00,
            'description' => 'Stay on top of customer queries and manage everything from one, easy-to-use inbox.',
            'link_text' => 'Read more →',
            'link_url' => '/trial',
            'footnote' => '*Price excludes VAT per account, invoicing per year',
            'sort_order' => 5,
            'is_active' => true,
        ]);

        // Advanced Insights Booster
        PricingBooster::create([
            'name' => 'Advanced Insights Booster',
            'slug' => 'advanced-insights-booster',
            'price' => 25.00,
            'description' => 'Fully tailor your reports to your needs and gain even more clarity.',
            'link_text' => 'Read more →',
            'link_url' => '/trial',
            'footnote' => '*Price excludes VAT per account, invoicing per year',
            'sort_order' => 6,
            'is_active' => true,
        ]);
    }
}
