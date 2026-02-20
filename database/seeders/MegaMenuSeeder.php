<?php

namespace Database\Seeders;

use App\Models\MegaMenuItem;
use Illuminate\Database\Seeder;

class MegaMenuSeeder extends Seeder
{
    public function run(): void
    {
        if (MegaMenuItem::query()->exists()) {
            return;
        }

        // 1. Home (Simple Link)
        MegaMenuItem::create([
            'parent_id' => null,
            'order' => 0,
            'title' => 'Home',
            'subtitle' => null,
            'description' => null,
            'icon' => 'fas fa-home',
            'icon_bg_color' => '#3B82F6',
            'url' => api_path('home'),
            'is_mega_menu' => false,
            'is_active' => true,
            'open_in_new_tab' => false,
        ]);

        // 2. Solutions (Mega Menu with children) – API endpoints
        $solutions = MegaMenuItem::create([
            'parent_id' => null,
            'order' => 1,
            'title' => 'Solutions',
            'subtitle' => 'Explore our software solutions',
            'description' => null,
            'icon' => 'fas fa-briefcase',
            'icon_bg_color' => '#10B981',
            'url' => api_path('solutions'),
            'is_mega_menu' => true,
            'is_active' => true,
            'open_in_new_tab' => false,
        ]);

        // Solution 1: Business Management – API endpoint
        $solution1 = MegaMenuItem::create([
            'parent_id' => $solutions->id,
            'order' => 1,
            'title' => 'Business Management',
            'subtitle' => 'Core business operations and customer management',
            'icon' => 'fas fa-building',
            'icon_bg_color' => '#3B82F6',
            'url' => api_path('solution', 'crm'),
            'is_active' => true,
        ]);

        // Solution 2: Financial Operations – API endpoint
        $solution2 = MegaMenuItem::create([
            'parent_id' => $solutions->id,
            'order' => 2,
            'title' => 'Financial Operations',
            'subtitle' => 'Accounting, invoicing, and financial management',
            'icon' => 'fas fa-chart-line',
            'icon_bg_color' => '#10B981',
            'url' => api_path('solution', 'financial-operations'),
            'is_active' => true,
        ]);

        // Populate modules under Solution 1 (Business Management)
        try {
            if (class_exists('\App\Models\Module')) {
                $businessModules = \App\Models\Module::where('is_active', true)
                    ->whereIn('anchor', ['crm', 'projects', 'quotations'])
                    ->orderBy('sort_order')
                    ->get();

                foreach ($businessModules as $index => $module) {
                    MegaMenuItem::create([
                        'parent_id' => $solution1->id,
                        'order' => $index + 1,
                        'title' => $module->nav_title ?: $module->title,
                        'subtitle' => $module->subtitle ?: $module->short_body,
                        'icon' => 'fas fa-cube',
                        'icon_bg_color' => '#3B82F6',
                        'url' => api_path('module', $module->slug ?? $module->anchor),
                        'is_active' => true,
                    ]);
                }

                $financialModules = \App\Models\Module::where('is_active', true)
                    ->whereIn('anchor', ['expenses', 'invoices', 'insights'])
                    ->orderBy('sort_order')
                    ->get();

                foreach ($financialModules as $index => $module) {
                    MegaMenuItem::create([
                        'parent_id' => $solution2->id,
                        'order' => $index + 1,
                        'title' => $module->nav_title ?: $module->title,
                        'subtitle' => $module->subtitle ?: $module->short_body,
                        'icon' => 'fas fa-cube',
                        'icon_bg_color' => '#10B981',
                        'url' => api_path('module', $module->slug ?? $module->anchor),
                        'is_active' => true,
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Fallback: Add static modules under Solution 1 – API endpoints
            MegaMenuItem::create([
                'parent_id' => $solution1->id,
                'order' => 1,
                'title' => 'CRM',
                'subtitle' => 'Customer relationship management',
                'icon' => 'fas fa-users',
                'icon_bg_color' => '#3B82F6',
                'url' => api_path('solution', 'crm'),
                'is_active' => true,
            ]);

            MegaMenuItem::create([
                'parent_id' => $solution1->id,
                'order' => 2,
                'title' => 'Project Management',
                'subtitle' => 'Manage projects and tasks',
                'icon' => 'fas fa-tasks',
                'icon_bg_color' => '#3B82F6',
                'url' => api_path('solution', 'projects'),
                'is_active' => true,
            ]);

            MegaMenuItem::create([
                'parent_id' => $solution1->id,
                'order' => 3,
                'title' => 'Quotations',
                'subtitle' => 'Create and manage quotes',
                'icon' => 'fas fa-file-alt',
                'icon_bg_color' => '#3B82F6',
                'url' => api_path('solution', 'quotations'),
                'is_active' => true,
            ]);

            MegaMenuItem::create([
                'parent_id' => $solution1->id,
                'order' => 4,
                'title' => 'Time Tracking',
                'subtitle' => 'Track billable hours',
                'icon' => 'fas fa-clock',
                'icon_bg_color' => '#3B82F6',
                'url' => api_path('solution', 'time-tracking'),
                'is_active' => true,
            ]);

            MegaMenuItem::create([
                'parent_id' => $solution1->id,
                'order' => 5,
                'title' => 'Document Management',
                'subtitle' => 'Organize business documents',
                'icon' => 'fas fa-folder',
                'icon_bg_color' => '#3B82F6',
                'url' => api_path('solution', 'documents'),
                'is_active' => true,
            ]);

            MegaMenuItem::create([
                'parent_id' => $solution1->id,
                'order' => 6,
                'title' => 'Team Collaboration',
                'subtitle' => 'Collaborate with your team',
                'icon' => 'fas fa-users-cog',
                'icon_bg_color' => '#3B82F6',
                'url' => api_path('solution', 'collaboration'),
                'is_active' => true,
            ]);

            // Fallback: Add static modules under Solution 2 – API endpoints
            MegaMenuItem::create([
                'parent_id' => $solution2->id,
                'order' => 1,
                'title' => 'Invoicing',
                'subtitle' => 'Create and send invoices',
                'icon' => 'fas fa-file-invoice',
                'icon_bg_color' => '#10B981',
                'url' => api_path('solution', 'invoices'),
                'is_active' => true,
            ]);

            MegaMenuItem::create([
                'parent_id' => $solution2->id,
                'order' => 2,
                'title' => 'Expense Management',
                'subtitle' => 'Track business expenses',
                'icon' => 'fas fa-receipt',
                'icon_bg_color' => '#10B981',
                'url' => api_path('solution', 'expenses'),
                'is_active' => true,
            ]);

            MegaMenuItem::create([
                'parent_id' => $solution2->id,
                'order' => 3,
                'title' => 'Financial Reports',
                'subtitle' => 'Business insights and analytics',
                'icon' => 'fas fa-chart-bar',
                'icon_bg_color' => '#10B981',
                'url' => api_path('solution', 'insights'),
                'is_active' => true,
            ]);

            MegaMenuItem::create([
                'parent_id' => $solution2->id,
                'order' => 4,
                'title' => 'Tax Management',
                'subtitle' => 'Handle tax calculations',
                'icon' => 'fas fa-calculator',
                'icon_bg_color' => '#10B981',
                'url' => api_path('solution', 'tax'),
                'is_active' => true,
            ]);

            MegaMenuItem::create([
                'parent_id' => $solution2->id,
                'order' => 5,
                'title' => 'Banking Integration',
                'subtitle' => 'Connect with banks',
                'icon' => 'fas fa-university',
                'icon_bg_color' => '#10B981',
                'url' => api_path('solution', 'banking'),
                'is_active' => true,
            ]);

            MegaMenuItem::create([
                'parent_id' => $solution2->id,
                'order' => 6,
                'title' => 'Payment Processing',
                'subtitle' => 'Handle online payments',
                'icon' => 'fas fa-credit-card',
                'icon_bg_color' => '#10B981',
                'url' => api_path('solution', 'payments'),
                'is_active' => true,
            ]);
        }

        // 3. Pricing (Simple Link) – API endpoint
        MegaMenuItem::create([
            'parent_id' => null,
            'order' => 2,
            'title' => 'Pricing',
            'subtitle' => null,
            'description' => null,
            'icon' => 'fas fa-tags',
            'icon_bg_color' => '#F59E0B',
            'url' => api_path('pricing'),
            'is_mega_menu' => false,
            'is_active' => true,
            'open_in_new_tab' => false,
        ]);

        // 4. Changelog (Simple Link) – API endpoint
        MegaMenuItem::create([
            'parent_id' => null,
            'order' => 3,
            'title' => 'Changelog',
            'subtitle' => null,
            'description' => null,
            'icon' => 'fas fa-tags',
            'icon_bg_color' => '#F59E0B',
            'url' => api_path('changelog'),
            'is_mega_menu' => false,
            'is_active' => true,
            'open_in_new_tab' => false,
        ]);

        // 5. Blog (Simple Link) – API endpoint
        MegaMenuItem::create([
            'parent_id' => null,
            'order' => 4,
            'title' => 'Blog',
            'subtitle' => null,
            'description' => null,
            'icon' => 'fas fa-newspaper',
            'icon_bg_color' => '#6366F1',
            'url' => api_path('blog'),
            'is_mega_menu' => false,
            'is_active' => true,
            'open_in_new_tab' => false,
        ]);

        // 6. Contact (Simple Link) – API endpoint
        MegaMenuItem::create([
            'parent_id' => null,
            'order' => 5,
            'title' => 'Contact',
            'subtitle' => null,
            'description' => null,
            'icon' => 'fas fa-envelope',
            'icon_bg_color' => '#EF4444',
            'url' => api_path('contact'),
            'is_mega_menu' => false,
            'is_active' => true,
            'open_in_new_tab' => false,
        ]);
    }
}
