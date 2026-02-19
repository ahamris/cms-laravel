<?php

namespace Database\Seeders;

use App\Models\PricingFeature;
use Illuminate\Database\Seeder;

class PricingFeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (PricingFeature::count() > 0) {
            return;
        }

        $features = [
            // All Features Category
            [
                'category' => 'All Features',
                'name' => 'Mobile app',
                'description' => 'Quickly plan your journey to your next in-person meeting with clickable addresses. Scan customer business cards, Create quotations onsite and get them signed there and then.',
                'available_in_plans' => ['flow'],
                'badge' => null,
                'sort_order' => 1,
            ],
            [
                'category' => 'All Features',
                'name' => 'Reporting',
                'description' => 'Benefit from in-depth customer insights and make informed and strategic decisions so your business grows.',
                'available_in_plans' => ['flow'],
                'badge' => null,
                'sort_order' => 2,
            ],
            [
                'category' => 'All Features',
                'name' => 'User access',
                'description' => 'Only permit employee access to the companies, contacts, deals or calendars they\'re responsible for.',
                'available_in_plans' => ['flow'],
                'badge' => null,
                'sort_order' => 3,
            ],
            [
                'category' => 'All Features',
                'name' => 'Templates',
                'description' => 'Create an email invitation once and save it as a template. Use the template as your next starting point time and time again.',
                'available_in_plans' => ['flow'],
                'badge' => null,
                'sort_order' => 4,
            ],
            [
                'category' => 'All Features',
                'name' => 'Single Sign-On',
                'description' => 'Log in faster and more securely using Single Sign-On (SSO). Available for Google, Apple and Microsoft.',
                'available_in_plans' => ['flow'],
                'badge' => null,
                'sort_order' => 5,
            ],
            [
                'category' => 'All Features',
                'name' => 'Automatic follow-ups',
                'description' => 'Add a follow-up to every stage of your opportunity, and never forget to schedule that important task, meeting or phone call again!',
                'available_in_plans' => ['flow'],
                'badge' => null,
                'sort_order' => 6,
            ],
            [
                'category' => 'All Features',
                'name' => 'Multiple businesses in one account',
                'description' => 'Manage multiple businesses from a single account and then enter additional business details for each business.',
                'available_in_plans' => ['flow'],
                'badge' => null,
                'sort_order' => 7,
            ],

            // Invoices Category
            [
                'category' => 'Invoices',
                'name' => 'Invoices',
                'description' => 'Create accurate invoices featuring your own logo, font and colour palette. Start from scratch or use a quotation, project or time tracking.',
                'available_in_plans' => ['flow'],
                'badge' => null,
                'sort_order' => 1,
            ],
            [
                'category' => 'Invoices',
                'name' => 'Incoming e-invoices',
                'description' => 'Automatically receive e-invoices through the secure Peppol network, neatly organised so you never lose track of an invoice again.',
                'available_in_plans' => ['flow'],
                'badge' => 'Coming soon',
                'sort_order' => 2,
            ],
            [
                'category' => 'Invoices',
                'name' => 'Outgoing e-invoices',
                'description' => 'Create accurate e-invoices featuring your own logo, font and colour palette. Send invoices via the secure Peppol network.',
                'available_in_plans' => ['flow'],
                'badge' => 'Coming soon',
                'sort_order' => 3,
            ],
            [
                'category' => 'Invoices',
                'name' => 'Pay via QR code',
                'description' => 'Make sure customers can pay quickly and easily without using online payment software. Automatically add a QR code to your invoice.',
                'available_in_plans' => ['flow'],
                'badge' => null,
                'sort_order' => 4,
            ],
            [
                'category' => 'Invoices',
                'name' => 'Credit notes',
                'description' => 'Quickly amend invoice mistakes with credit notes or negative invoices. No need for customers to pay and it\'ll ease the burden on you, too.',
                'available_in_plans' => ['flow'],
                'badge' => null,
                'sort_order' => 5,
            ],
            [
                'category' => 'Invoices',
                'name' => 'Customer invoice summary',
                'description' => 'Give customers a clear summary of their invoices every time you send a new one over.',
                'available_in_plans' => ['flow'],
                'badge' => null,
                'sort_order' => 6,
            ],
            [
                'category' => 'Invoices',
                'name' => 'Advance invoicing',
                'description' => 'Send clients an invoice before a job is complete – perfect when the terms are partial/full payment in advance.',
                'available_in_plans' => ['flow'],
                'badge' => null,
                'sort_order' => 7,
            ],
            [
                'category' => 'Invoices',
                'name' => 'Pro forma invoices',
                'description' => 'Provide the estimated price of goods or services with a pro forma invoice (often identical to a quotation).',
                'available_in_plans' => ['flow'],
                'badge' => null,
                'sort_order' => 8,
            ],
            [
                'category' => 'Invoices',
                'name' => 'Multiple currencies',
                'description' => 'Sell to international customers and allow them to pay in their own currency (pound, dollar, etc.).',
                'available_in_plans' => ['flow'],
                'badge' => null,
                'sort_order' => 9,
            ],
            [
                'category' => 'Invoices',
                'name' => 'Product management',
                'description' => 'Create products or services once, fill out their sales and purchase price to determine the margin and save them in your price list.',
                'available_in_plans' => ['flow'],
                'badge' => null,
                'sort_order' => 10,
            ],
            [
                'category' => 'Invoices',
                'name' => 'Stock management',
                'description' => 'Know exactly how much you still have in stock as you invoice, and get automatic reminders when you need to replenish stock.',
                'available_in_plans' => ['flow'],
                'badge' => null,
                'sort_order' => 11,
            ],
            [
                'category' => 'Invoices',
                'name' => 'Automatic payment reminders',
                'description' => 'Send automatic payment reminders when payments are overdue. Modify shipping details, add surcharges and change your template.',
                'available_in_plans' => ['flow'],
                'badge' => null,
                'sort_order' => 12,
            ],
            [
                'category' => 'Invoices',
                'name' => 'Invoices for time and/or materials',
                'description' => 'Easily create invoices based on your hourly rate and/or the price of materials you delivered.',
                'available_in_plans' => ['flow'],
                'badge' => null,
                'sort_order' => 13,
            ],
            [
                'category' => 'Invoices',
                'name' => 'Periodic or interim invoices',
                'description' => 'Send periodic or interim invoices every time you complete part of a project.',
                'available_in_plans' => ['flow'],
                'badge' => null,
                'sort_order' => 14,
            ],
            [
                'category' => 'Invoices',
                'name' => 'Recurring invoices or subscriptions',
                'description' => 'Set up automatic recurring invoices for customers on subscription or who you work with on an ongoing basis.',
                'available_in_plans' => ['flow'],
                'badge' => null,
                'sort_order' => 15,
            ],
            [
                'category' => 'Invoices',
                'name' => 'Financial forecasting',
                'description' => 'Accurately forecast your income based on invoice due dates. Gain visibility for the next twelve months.',
                'available_in_plans' => ['flow'],
                'badge' => null,
                'sort_order' => 16,
            ],

            // Expenses Category
            [
                'category' => 'Expenses',
                'name' => 'Expenses',
                'description' => 'Track all business expenses and receipts in one place for better financial control.',
                'available_in_plans' => ['grow', 'flow'],
                'badge' => null,
                'sort_order' => 1,
            ],

            // Quotations Category
            [
                'category' => 'Quotations',
                'name' => 'Quotations',
                'description' => 'Create professional quotations and convert them to invoices with one click.',
                'available_in_plans' => ['grow', 'flow'],
                'badge' => null,
                'sort_order' => 1,
            ],

            // Reporting Category
            [
                'category' => 'Reporting',
                'name' => 'Advanced Analytics',
                'description' => 'Get detailed insights into your business performance with advanced reporting tools.',
                'available_in_plans' => ['flow'],
                'badge' => null,
                'sort_order' => 1,
            ],

            // Integrations Category
            [
                'category' => 'Integrations',
                'name' => 'API Access',
                'description' => 'Connect OpenPublicatie with your existing tools via our powerful API.',
                'available_in_plans' => ['flow'],
                'badge' => null,
                'sort_order' => 1,
            ],

            // Support Category
            [
                'category' => 'Support',
                'name' => 'Priority Support',
                'description' => 'Get faster response times and dedicated support from our team.',
                'available_in_plans' => ['flow'],
                'badge' => null,
                'sort_order' => 1,
            ],
        ];

        foreach ($features as $feature) {
            PricingFeature::create([
                'category' => $feature['category'],
                'name' => $feature['name'],
                'description' => $feature['description'],
                'available_in_plans' => $feature['available_in_plans'],
                'badge' => $feature['badge'],
                'sort_order' => $feature['sort_order'],
                'is_active' => true,
            ]);
        }
    }
}
