<?php

namespace Database\Seeders;

use App\Models\PricingPlan;
use Illuminate\Database\Seeder;

class PricingPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (PricingPlan::count() > 0) {
            return;
        }

        PricingPlan::truncate();

        // SMART Plan
        PricingPlan::firstOrCreate([
            'name' => 'Aktief',
            'slug' => 'aktief',
            'price' => 50.00,
            'discounted_price' => 37.50,
            'discount_percentage' => 25,
            'description' => 'For those who want to get up and running with CRM, sales and time tracking.',
            'features' => [
                'CRM and contact management',
                'Sales pipeline tracking',
                'Time tracking',
                'Basic reporting',
                'Mobile app access',
            ],
            'button_text' => 'Start today →',
            'button_url' => '/trial',
            'footnote' => '*Prices exclude VAT',
            'sort_order' => 1,
            'is_active' => true,
            'is_popular' => false,
        ]);

        // GROW Plan
        PricingPlan::firstOrCreate([
            'name' => 'Passief',
            'slug' => 'passif',
            'price' => 66.00,
            'discounted_price' => 49.50,
            'discount_percentage' => 25,
            'description' => 'For those who are looking to simplify and automate invoicing.',
            'features' => [
                'Everything in SMART subscription',
                'Automated invoicing',
                'Expense tracking',
                'Quotation management',
                'Advanced reporting',
                'Payment integrations',
            ],
            'button_text' => 'Start today →',
            'button_url' => '/trial',
            'footnote' => 'SMART subscription +',
            'sort_order' => 2,
            'is_active' => true,
            'is_popular' => true,
        ]);

        // FLOW Plan
        PricingPlan::firstOrCreate([
            'name' => 'Context',
            'slug' => 'context',
            'price' => 90.00,
            'discounted_price' => 67.50,
            'discount_percentage' => 25,
            'description' => 'For those who need to manage multiple teams and businesses.',
            'features' => [
                'Everything in GROW subscription',
                'Multi-business management',
                'Team collaboration tools',
                'Advanced user permissions',
                'Priority support',
                'API access',
                'Custom integrations',
            ],
            'button_text' => 'Start today →',
            'button_url' => '/trial',
            'footnote' => 'GROW subscription + | *€ 11/month for 10,000 extra contacts & businesses.',
            'sort_order' => 3,
            'is_active' => true,
            'is_popular' => false,
        ]);



        // AI Plan
        PricingPlan::firstOrCreate([
            'name' => 'AI',
            'slug' => 'ai',
            'price' => 66.00,
            'discounted_price' => 49.50,
            'discount_percentage' => 25,
            'description' => 'For those who are looking to simplify and automate invoicing.',
            'features' => [
                'Everything in SMART subscription',
                'Automated invoicing',
                'Expense tracking',
                'Quotation management',
                'Advanced reporting',
                'Payment integrations',
            ],
            'button_text' => 'Start today →',
            'button_url' => '/trial',
            'footnote' => 'SMART subscription +',
            'sort_order' => 4,
            'is_active' => true,
            'is_popular' => true,
        ]);
    }
}
