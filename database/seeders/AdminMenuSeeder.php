<?php

namespace Database\Seeders;

use App\Models\Admin\AdminMenu;
use App\Models\Admin\AdminMenuItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $menu = AdminMenu::updateOrCreate(
                ['slug' => 'admin-main'],
                [
                    'name' => 'Admin Sidebar',
                    'description' => 'Admin panel standard menu',
                    'position' => 0,
                    'is_active' => true,
                ]
            );

            AdminMenuItem::where('admin_menu_id', $menu->id)->delete();

            $position = 0;

            // Dashboard Section
            $dashboard = AdminMenuItem::create([
                'admin_menu_id' => $menu->id,
                'item_type' => 'link',
                'label' => 'Dashboard',
                'slug' => 'dashboard',
                'route_name' => 'admin.index',
                'icon' => 'home',
                'position' => $position++,
                'is_active' => true,
            ]);

            // CRM Section
            $crmSection = AdminMenuItem::create([
                'admin_menu_id' => $menu->id,
                'item_type' => 'section',
                'label' => 'CRM',
                'slug' => Str::slug('CRM'),
                'icon' => 'graduation-cap',
                'position' => $position++,
                'is_active' => true,
            ]);

            $this->createChildren($crmSection, [
                ['label' => 'Organization Names', 'route' => 'admin.content.organization-name.index', 'icon' => 'building'],
                ['label' => 'Entries', 'route' => 'admin.administrator.contact-forms.index', 'icon' => 'envelope'],
                ['label' => 'Contacts', 'route' => 'admin.administrator.contacts.index', 'icon' => 'users'],
                ['label' => 'Subscriptions', 'route' => 'admin.administrator.subscriptions.index', 'icon' => 'clipboard-check'],
            ]);

            // CMS Section
            $cmsSection = AdminMenuItem::create([
                'admin_menu_id' => $menu->id,
                'item_type' => 'section',
                'label' => 'CMS',
                'slug' => Str::slug('CMS'),
                'icon' => 'graduation-cap',
                'position' => $position++,
                'is_active' => true,
            ]);

            // Articles Dropdown
            $articles = AdminMenuItem::create([
                'admin_menu_id' => $menu->id,
                'parent_id' => $cmsSection->id,
                'item_type' => 'link',
                'label' => 'Articles',
                'slug' => 'articles',
                'route_name' => 'admin.content.blog.index',
                'icon' => 'newspaper',
                'active_pattern' => 'admin.content.blog*,admin.content.blog-category*,admin.content.comment*',
                'position' => 0,
                'is_active' => true,
            ]);

            $this->createChildren($articles, [
                ['label' => 'Categories', 'route' => 'admin.content.blog-category.index', 'icon' => 'folder'],
                ['label' => 'Article', 'route' => 'admin.content.blog.index', 'icon' => 'newspaper'],
                ['label' => 'Comments', 'route' => 'admin.content.comment.index', 'icon' => 'comments'],
            ]);

            // Pages Dropdown
            $pages = AdminMenuItem::create([
                'admin_menu_id' => $menu->id,
                'parent_id' => $cmsSection->id,
                'item_type' => 'link',
                'label' => 'Pages',
                'slug' => 'pages',
                'route_name' => 'admin.content.page.index',
                'icon' => 'puzzle-piece',
                'active_pattern' => 'admin.content.page*,admin.content.legal*,admin.content.changelog*,admin.content.about*,admin.content.event*,admin.settings.hero-backgrounds*',
                'position' => 1,
                'is_active' => true,
            ]);

            $this->createChildren($pages, [
                ['label' => 'Pages', 'route' => 'admin.content.page.index', 'icon' => 'file-text'],
                ['label' => 'Legal Pages', 'route' => 'admin.content.legal.index', 'icon' => 'list-alt'],
                ['label' => 'Changelog', 'route' => 'admin.content.changelog.index', 'icon' => 'list-alt'],
                ['label' => 'About', 'route' => 'admin.content.about.index', 'icon' => 'info-circle'],
                ['label' => 'Event', 'route' => 'admin.content.event.index', 'icon' => 'calendar-alt'],
                ['label' => 'Header Settings', 'route' => 'admin.settings.hero-backgrounds.index', 'icon' => 'heading'],
            ]);

            // Solution & Modules Dropdown
            $solutionModules = AdminMenuItem::create([
                'admin_menu_id' => $menu->id,
                'parent_id' => $cmsSection->id,
                'item_type' => 'link',
                'label' => 'Solution & Modules',
                'slug' => 'solution-modules',
                'route_name' => 'admin.content.solution.index',
                'icon' => 'puzzle-piece',
                'active_pattern' => 'admin.content.solution*,admin.content.feature*,admin.content.module*',
                'position' => 2,
                'is_active' => true,
            ]);

            $this->createChildren($solutionModules, [
                ['label' => 'Solution', 'route' => 'admin.content.solution.index', 'icon' => 'lightbulb'],
                ['label' => 'Modules', 'route' => 'admin.content.module.index', 'icon' => 'cubes'],
                ['label' => 'Features', 'route' => 'admin.content.feature.index', 'icon' => 'puzzle-piece'],
            ]);

            // Widgets Dropdown
            $widgets = AdminMenuItem::create([
                'admin_menu_id' => $menu->id,
                'parent_id' => $cmsSection->id,
                'item_type' => 'link',
                'label' => 'Widgets',
                'slug' => 'widgets',
                'route_name' => 'admin.social-settings.index',
                'icon' => 'list-alt',
                'active_pattern' => 'admin.social-settings*,admin.content.form-builder*,admin.content.tailwind-plus*',
                'position' => 3,
                'is_active' => true,
            ]);

            $this->createChildren($widgets, [
                ['label' => 'Social Media', 'route' => 'admin.social-settings.index', 'icon' => 'share-alt'],
                ['label' => 'Form Section', 'route' => 'admin.content.form-builder.index', 'icon' => 'wpforms'],
                ['label' => 'TailwindPlus Components', 'route' => 'admin.content.tailwind-plus.index', 'icon' => 'code'],
            ]);

            // Plan Management Dropdown
            $planManagement = AdminMenuItem::create([
                'admin_menu_id' => $menu->id,
                'parent_id' => $cmsSection->id,
                'item_type' => 'link',
                'label' => 'Plan Management',
                'slug' => 'plan-management',
                'route_name' => 'admin.content.pricing-plan.index',
                'icon' => 'tags',
                'active_pattern' => 'admin.content.pricing-plan*,admin.content.pricing-booster*,admin.content.pricing-feature*',
                'position' => 4,
                'is_active' => true,
            ]);

            $this->createChildren($planManagement, [
                ['label' => 'Pricing Plans', 'route' => 'admin.content.pricing-plan.index', 'icon' => 'dollar-sign'],
                ['label' => 'Addons', 'route' => 'admin.content.pricing-booster.index', 'icon' => 'rocket'],
                ['label' => 'Features', 'route' => 'admin.content.pricing-feature.index', 'icon' => 'list-check'],
            ]);

            // Live Sessions / Academy Dropdown
            $liveSessions = AdminMenuItem::create([
                'admin_menu_id' => $menu->id,
                'parent_id' => $cmsSection->id,
                'item_type' => 'link',
                'label' => 'Academy & Live',
                'slug' => 'live-sessions',
                'route_name' => 'admin.content.live-session.index',
                'icon' => 'video',
                'active_pattern' => 'admin.content.live-session*,admin.content.presenter*,admin.content.session-registration*,admin.content.academy-category*,admin.content.academy-chapter*,admin.content.academy-video*',
                'position' => 5,
                'is_active' => true,
            ]);

            $this->createChildren($liveSessions, [
                ['label' => 'Document Categories', 'route' => 'admin.content.academy-category.index', 'icon' => 'folder'],
                ['label' => 'Chapters', 'route' => 'admin.content.academy-chapter.index', 'icon' => 'book'],
                ['label' => 'Document Videos', 'route' => 'admin.content.academy-video.index', 'icon' => 'video'],
                ['label' => 'Live Sessions', 'route' => 'admin.content.live-session.index', 'icon' => 'play-circle'],
                ['label' => 'Presenters', 'route' => 'admin.content.presenter.index', 'icon' => 'user-tie'],
                ['label' => 'Registrations', 'route' => 'admin.content.session-registration.index', 'icon' => 'user-check'],
            ]);

            // Vacancies Section
            $vacanciesSection = AdminMenuItem::create([
                'admin_menu_id' => $menu->id,
                'item_type' => 'section',
                'label' => 'Vacancies',
                'slug' => Str::slug('Vacancies'),
                'icon' => 'briefcase',
                'position' => $position++,
                'is_active' => true,
            ]);

            $vacancies = AdminMenuItem::create([
                'admin_menu_id' => $menu->id,
                'parent_id' => $vacanciesSection->id,
                'item_type' => 'link',
                'label' => 'Vacancies',
                'slug' => 'vacancies',
                'route_name' => 'admin.vacancies.index',
                'icon' => 'briefcase',
                'active_pattern' => 'admin.vacancies.*',
                'position' => 0,
                'is_active' => true,
            ]);

            $this->createChildren($vacancies, [
                ['label' => 'Vacancies', 'route' => 'admin.vacancies.index', 'icon' => 'briefcase'],
                ['label' => 'Job Applications', 'route' => 'admin.job-applications.index', 'icon' => 'envelope-open-text'],
            ]);

            // Settings Section
            $settingsSection = AdminMenuItem::create([
                'admin_menu_id' => $menu->id,
                'item_type' => 'section',
                'label' => 'Settings',
                'slug' => Str::slug('Settings'),
                'icon' => 'cog',
                'position' => $position++,
                'is_active' => true,
            ]);

            // Media Library
            AdminMenuItem::create([
                'admin_menu_id' => $menu->id,
                'parent_id' => $settingsSection->id,
                'item_type' => 'link',
                'label' => 'Media Library',
                'slug' => 'media-library',
                'url' => '#',
                'icon' => 'folder',
                'position' => 0,
                'is_active' => true,
            ]);

            // Site Settings Dropdown
            $siteSettings = AdminMenuItem::create([
                'admin_menu_id' => $menu->id,
                'parent_id' => $settingsSection->id,
                'item_type' => 'link',
                'label' => 'Site Settings',
                'slug' => 'site-settings',
                'route_name' => 'admin.settings.general.index',
                'icon' => 'user-shield',
                'active_pattern' => 'admin.settings.general*,admin.settings.contact*,admin.settings.theme*,admin.settings.login*,admin.settings.cookie*,admin.mail*,admin.translations*,admin.security.two-factor*,admin.content.external-code*,admin.image-optimizer*',
                'position' => 1,
                'is_active' => true,
            ]);

            $this->createChildren($siteSettings, [
                ['label' => 'General Settings', 'route' => 'admin.settings.general.index', 'icon' => 'sliders-h'],
                ['label' => 'Contact Page', 'route' => 'admin.settings.contact.index', 'icon' => 'address-card'],
                ['label' => 'Theme Settings', 'route' => 'admin.settings.theme.index', 'icon' => 'palette'],
                ['label' => 'Admin Theme', 'route' => 'admin.settings.admintheme', 'icon' => 'palette'],
                ['label' => 'Login Settings', 'route' => 'admin.settings.login.index', 'icon' => 'sign-in-alt'],
                ['label' => 'Cookie Settings', 'route' => 'admin.settings.cookie.index', 'icon' => 'cookie-bite'],
                ['label' => 'Mail Settings', 'route' => 'admin.mail.index', 'icon' => 'envelope'],
                ['label' => 'Translation Manager', 'route' => 'admin.translations.index', 'icon' => 'language'],
                ['label' => 'Two-Factor Auth', 'route' => 'admin.security.two-factor.index', 'icon' => 'shield-alt'],
                ['label' => 'External Codes', 'route' => 'admin.content.external-code.index', 'icon' => 'code'],
                ['label' => 'Optimize Images', 'route' => 'admin.image-optimizer.index', 'icon' => 'folder'],
            ]);

            // Routes Dropdown
            $routes = AdminMenuItem::create([
                'admin_menu_id' => $menu->id,
                'parent_id' => $settingsSection->id,
                'item_type' => 'link',
                'label' => 'Routes',
                'slug' => 'routes',
                'route_name' => 'admin.settings.mega-menu.index',
                'icon' => 'user-shield',
                'active_pattern' => 'admin.settings.mega-menu*,admin.content.sticky-menu-item*,admin.settings.footer-links*',
                'position' => 2,
                'is_active' => true,
            ]);

            $this->createChildren($routes, [
                ['label' => 'Mega Menu', 'route' => 'admin.settings.mega-menu.index', 'icon' => 'bars'],
                ['label' => 'Sticky Menu', 'route' => 'admin.content.sticky-menu-item.index', 'icon' => 'bars'],
                ['label' => 'Footer Links', 'route' => 'admin.settings.footer-links.index', 'icon' => 'link'],
            ]);

            // User Management Dropdown
            $userManagement = AdminMenuItem::create([
                'admin_menu_id' => $menu->id,
                'parent_id' => $settingsSection->id,
                'item_type' => 'link',
                'label' => 'User Management',
                'slug' => 'user-management',
                'route_name' => 'admin.administrator.users.index',
                'icon' => 'user-shield',
                'active_pattern' => 'admin.administrator.users*,admin.administrator.permissions*,admin.administrator.roles*',
                'position' => 3,
                'is_active' => true,
            ]);

            $this->createChildren($userManagement, [
                ['label' => 'Users', 'route' => 'admin.administrator.users.index', 'icon' => 'user'],
                ['label' => 'Permissions', 'route' => 'admin.administrator.permissions.index', 'icon' => 'key'],
                ['label' => 'Roles', 'route' => 'admin.administrator.roles.index', 'icon' => 'clipboard-list'],
            ]);

            // SMM
            AdminMenuItem::create([
                'admin_menu_id' => $menu->id,
                'parent_id' => $settingsSection->id,
                'item_type' => 'link',
                'label' => 'SMM',
                'slug' => 'smm',
                'route_name' => 'admin.settings.social-media-platforms.index',
                'icon' => 'share-nodes',
                'position' => 4,
                'is_active' => true,
            ]);

            // Report Section
            $reportSection = AdminMenuItem::create([
                'admin_menu_id' => $menu->id,
                'item_type' => 'section',
                'label' => 'Report',
                'slug' => Str::slug('Report'),
                'icon' => 'cog',
                'position' => $position++,
                'is_active' => true,
            ]);

            $this->createChildren($reportSection, [
                ['label' => 'Web Statistics', 'route' => 'admin.analytics.index', 'icon' => 'chart-line'],
                ['label' => 'Activity Logs', 'route' => 'admin.activity-log.index', 'icon' => 'history'],
                ['label' => 'Email Logs', 'route' => 'admin.administrator.email-logs.index', 'icon' => 'envelope-open-text'],
                ['label' => 'Robots.txt', 'route' => 'admin.settings.robots-txt.index', 'icon' => 'robot'],
            ]);
        });
    }

    protected function createChildren(AdminMenuItem $parent, array $children): void
    {
        foreach ($children as $index => $child) {
            AdminMenuItem::create([
                'admin_menu_id' => $parent->admin_menu_id,
                'parent_id' => $parent->id,
                'item_type' => 'link',
                'label' => $child['label'],
                'slug' => Str::slug($child['label']),
                'route_name' => $child['route'] ?? null,
                'icon' => $child['icon'] ?? null,
                'active_pattern' => $child['pattern'] ?? null,
                'badge_text' => $child['badge'] ?? null,
                'badge_color' => $child['badge_color'] ?? null,
                'position' => $index,
                'is_active' => true,
            ]);
        }
    }
}
