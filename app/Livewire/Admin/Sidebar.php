<?php

namespace App\Livewire\Admin;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

class Sidebar extends Component
{
    #[On('refresh-sidebar')]
    public function refresh(): void
    {
        // No-op: static menu does not use cache.
    }

    public function render(): View
    {
        $menu = (object) ['name' => 'Admin Panel'];
        $filtered = $this->filterMenuByPermission(self::staticMenuDefinition());
        $menuItems = collect(StaticSidebarItem::fromArray($filtered));

        return view('livewire.admin.sidebar', [
            'menu' => $menu,
            'menuItems' => $menuItems,
        ]);
    }

    /**
     * Filter menu tree by current user's permissions (Gate).
     * Items with a 'permission' key are hidden unless the user is allowed.
     * Sections with no visible children are removed.
     *
     * @param  array<int, array<string, mixed>>  $nodes
     * @return array<int, array<string, mixed>>
     */
    private function filterMenuByPermission(array $nodes): array
    {
        $result = [];

        foreach ($nodes as $node) {
            $permission = $node['permission'] ?? null;
            if ($permission !== null && ! Gate::allows($permission)) {
                continue;
            }

            $children = isset($node['children']) && is_array($node['children'])
                ? $this->filterMenuByPermission($node['children'])
                : [];

            $isSection = ($node['item_type'] ?? 'link') === 'section';
            if ($isSection && empty($children)) {
                continue;
            }

            $node['children'] = $children;
            $result[] = $node;
        }

        return $result;
    }

    /**
     * Static sidebar menu definition (typed structure). Public so Search and others can use it.
     * Each item may have a 'permission' key; the item is only shown when the user has that permission (Gate).
     *
     * @return array<int, array<string, mixed>>
     */
    public static function staticMenuDefinition(): array
    {
        return [
            [
                'item_type' => 'link',
                'label' => 'Dashboard',
                'slug' => 'dashboard',
                'route_name' => 'admin.index',
                'icon' => 'home',
            ],
            [
                'item_type' => 'section',
                'label' => 'CRM',
                'slug' => 'crm',
                'icon' => 'graduation-cap',
                'children' => [
                    ['label' => 'Entries', 'route_name' => 'admin.administrator.contact-forms.index', 'icon' => 'envelope', 'permission' => 'contact_form_access'],
                    ['label' => 'Contacts', 'route_name' => 'admin.administrator.contacts.index', 'icon' => 'users', 'permission' => 'contact_access'],
                ],
            ],
            [
                'item_type' => 'section',
                'label' => 'CMS',
                'slug' => 'cms',
                'icon' => 'graduation-cap',
                'children' => [
                    [
                        'label' => 'Articles',
                        'route_name' => 'admin.content.blog.index',
                        'icon' => 'newspaper',
                        'permission' => 'blog_access',
                        'active_pattern' => 'admin.content.blog*,admin.content.blog-category*,admin.content.comment*',
                        'children' => [
                            ['label' => 'Categories', 'route_name' => 'admin.content.blog-category.index', 'icon' => 'folder', 'permission' => 'blog_category_access'],
                            ['label' => 'Article', 'route_name' => 'admin.content.blog.index', 'icon' => 'newspaper', 'permission' => 'blog_access'],
                            ['label' => 'Comments', 'route_name' => 'admin.content.comment.index', 'icon' => 'comments', 'permission' => 'comment_access'],
                        ],
                    ],
                    [
                        'label' => 'Pages',
                        'route_name' => 'admin.content.page.index',
                        'icon' => 'puzzle-piece',
                        'permission' => 'page_access',
                        'active_pattern' => 'admin.content.page*,admin.content.legal*,admin.content.homepage*,admin.content.changelog*,admin.content.event*,admin.settings.hero-backgrounds*',
                        'children' => [
                            ['label' => 'Homepage', 'route_name' => 'admin.content.homepage.edit', 'icon' => 'home', 'permission' => 'page_access'],
                            ['label' => 'Pages', 'route_name' => 'admin.content.page.index', 'icon' => 'file-text', 'permission' => 'page_access'],
                            ['label' => 'Legal Pages', 'route_name' => 'admin.content.legal.index', 'icon' => 'list-alt', 'permission' => 'legal_access'],
                            ['label' => 'Changelog', 'route_name' => 'admin.content.changelog.index', 'icon' => 'list-alt', 'permission' => 'changelog_access'],
                            ['label' => 'Event', 'route_name' => 'admin.content.event.index', 'icon' => 'calendar-alt', 'permission' => 'event_access'],
                            ['label' => 'Header Settings', 'route_name' => 'admin.settings.hero-backgrounds.index', 'icon' => 'heading', 'permission' => 'hero_background_access'],
                        ],
                    ],
                    [
                        'label' => 'Solution & Modules',
                        'route_name' => 'admin.content.solution.index',
                        'icon' => 'puzzle-piece',
                        'permission' => 'solution_access',
                        'active_pattern' => 'admin.content.solution*,admin.content.feature*,admin.content.module*',
                        'children' => [
                            ['label' => 'Solution', 'route_name' => 'admin.content.solution.index', 'icon' => 'lightbulb', 'permission' => 'solution_access'],
                            ['label' => 'Modules', 'route_name' => 'admin.content.module.index', 'icon' => 'cubes', 'permission' => 'module_access'],
                            ['label' => 'Features', 'route_name' => 'admin.content.feature.index', 'icon' => 'puzzle-piece', 'permission' => 'feature_access'],
                        ],
                    ],
                    [
                        'label' => 'SMM',
                        'route_name' => 'admin.social-settings.index',
                        'icon' => 'list-alt',
                        'permission' => 'social_setting_access',
                        'active_pattern' => 'admin.social-settings*,admin.settings.social-media-platforms*',
                        'children' => [
                            ['label' => 'Social Pages', 'route_name' => 'admin.social-settings.index', 'icon' => 'share-alt', 'permission' => 'social_setting_access'],
                            ['label' => 'Auto Post', 'route_name' => 'admin.settings.social-media-platforms.index', 'icon' => 'share-nodes', 'permission' => 'social_media_platform_access'],
                        ],
                    ],
                    // [
                    //     'label' => 'Plan Management',
                    //     'route_name' => 'admin.content.pricing-plan.index',
                    //     'icon' => 'tags',
                    //     'permission' => 'pricing_plan_access',
                    //     'active_pattern' => 'admin.content.pricing-plan*,admin.content.pricing-booster*,admin.content.pricing-feature*',
                    //     'children' => [
                    //         ['label' => 'Pricing Plans', 'route_name' => 'admin.content.pricing-plan.index', 'icon' => 'dollar-sign', 'permission' => 'pricing_plan_access'],
                    //         ['label' => 'Addons', 'route_name' => 'admin.content.pricing-booster.index', 'icon' => 'rocket', 'permission' => 'pricing_booster_access'],
                    //         ['label' => 'Features', 'route_name' => 'admin.content.pricing-feature.index', 'icon' => 'list-check', 'permission' => 'pricing_feature_access'],
                    //     ],
                    // ],
                    [
                        'label' => 'Academy & Live',
                        'route_name' => 'admin.content.live-session.index',
                        'icon' => 'video',
                        'permission' => 'live_session_access',
                        'active_pattern' => 'admin.content.live-session*,admin.content.presenter*,admin.content.session-registration*,admin.content.course-category*,admin.content.course*,admin.content.course-video*',
                        'children' => [
                            ['label' => 'Document Categories', 'route_name' => 'admin.content.course-category.index', 'icon' => 'folder', 'permission' => 'course_category_access'],
                            ['label' => 'Chapters', 'route_name' => 'admin.content.course.index', 'icon' => 'book', 'permission' => 'course_access'],
                            ['label' => 'Document Videos', 'route_name' => 'admin.content.course-video.index', 'icon' => 'video', 'permission' => 'course_video_access'],
                            ['label' => 'Live Sessions', 'route_name' => 'admin.content.live-session.index', 'icon' => 'play-circle', 'permission' => 'live_session_access'],
                            ['label' => 'Presenters', 'route_name' => 'admin.content.presenter.index', 'icon' => 'user-tie', 'permission' => 'presenter_access'],
                            ['label' => 'Registrations', 'route_name' => 'admin.content.session-registration.index', 'icon' => 'user-check', 'permission' => 'session_registration_access'],
                        ],
                    ],
                ],
            ],
            [
                'item_type' => 'section',
                'label' => 'Vacancies',
                'slug' => 'vacancies',
                'icon' => 'briefcase',
                'children' => [
                    [
                        'label' => 'Vacancies',
                        'route_name' => 'admin.vacancies.index',
                        'icon' => 'briefcase',
                        'permission' => 'vacancy_access',
                        'active_pattern' => 'admin.vacancies.*',
                        'children' => [
                            ['label' => 'Vacancies', 'route_name' => 'admin.vacancies.index', 'icon' => 'briefcase', 'permission' => 'vacancy_access'],
                            ['label' => 'Job Applications', 'route_name' => 'admin.job-applications.index', 'icon' => 'envelope-open-text', 'permission' => 'job_application_access'],
                        ],
                    ],
                ],
            ],
            [
                'item_type' => 'section',
                'label' => 'Settings',
                'slug' => 'settings',
                'icon' => 'cog',
                'children' => [
                    [
                        'item_type' => 'link',
                        'label' => 'Media Library',
                        'slug' => 'media-library',
                        'url' => '#',
                        'icon' => 'folder',
                        'permission' => 'media_access',
                    ],
                    [
                        'label' => 'Site Settings',
                        'route_name' => 'admin.settings.general.index',
                        'icon' => 'user-shield',
                        'permission' => 'general_access',
                        'active_pattern' => 'admin.settings.general*,admin.settings.contact*,admin.settings.theme*,admin.settings.login*,admin.settings.cookie*,admin.mail*,admin.translations*,admin.security.two-factor*,admin.content.external-code*,admin.image-optimizer*',
                        'children' => [
                            ['label' => 'General Settings', 'route_name' => 'admin.settings.general.index', 'icon' => 'sliders-h', 'permission' => 'general_access'],
                            ['label' => 'Contact Page', 'route_name' => 'admin.settings.contact.index', 'icon' => 'address-card', 'permission' => 'contact_page_access'],
                            ['label' => 'Theme Settings', 'route_name' => 'admin.settings.theme.index', 'icon' => 'palette', 'permission' => 'theme_access'],
                            ['label' => 'Admin Theme', 'route_name' => 'admin.settings.admintheme', 'icon' => 'palette', 'permission' => 'theme_access'],
                            ['label' => 'Login Settings', 'route_name' => 'admin.settings.login.index', 'icon' => 'sign-in-alt', 'permission' => 'login_access'],
                            ['label' => 'Cookie Settings', 'route_name' => 'admin.settings.cookie.index', 'icon' => 'cookie-bite', 'permission' => 'cookie_access'],
                            ['label' => 'Mail Settings', 'route_name' => 'admin.mail.index', 'icon' => 'envelope', 'permission' => 'mail_access'],
                            ['label' => 'Translation Manager', 'route_name' => 'admin.translations.index', 'icon' => 'language', 'permission' => 'translation_access'],
                            ['label' => 'Two-Factor Auth', 'route_name' => 'admin.security.two-factor.index', 'icon' => 'shield-alt', 'permission' => 'two_factor_access'],
                            ['label' => 'External Codes', 'route_name' => 'admin.content.external-code.index', 'icon' => 'code', 'permission' => 'external_code_access'],
                            ['label' => 'Optimize Images', 'route_name' => 'admin.image-optimizer.index', 'icon' => 'folder', 'permission' => 'image_optimizer_access'],
                        ],
                    ],
                    [
                        'label' => 'AI Settings',
                        'route_name' => 'admin.settings.ai.index',
                        'icon' => 'robot',
                        'active_pattern' => 'admin.settings.ai*',
                    ],
                    [
                        'label' => 'Routes',
                        'route_name' => 'admin.settings.mega-menu.index',
                        'icon' => 'user-shield',
                        'permission' => 'mega_menu_access',
                        'active_pattern' => 'admin.settings.mega-menu*,admin.content.sticky-menu-item*,admin.settings.footer-links*',
                        'children' => [
                            ['label' => 'Mega Menu', 'route_name' => 'admin.settings.mega-menu.index', 'icon' => 'bars', 'permission' => 'mega_menu_access'],
                            ['label' => 'Sticky Menu', 'route_name' => 'admin.content.sticky-menu-item.index', 'icon' => 'bars', 'permission' => 'sticky_menu_item_access'],
                            ['label' => 'Footer Links', 'route_name' => 'admin.settings.footer-links.index', 'icon' => 'link', 'permission' => 'footer_link_access'],
                        ],
                    ],
                    [
                        'label' => 'User Management',
                        'route_name' => 'admin.administrator.users.index',
                        'icon' => 'user-shield',
                        'permission' => 'user_access',
                        'active_pattern' => 'admin.administrator.users*,admin.administrator.permissions*,admin.administrator.roles*',
                        'children' => [
                            ['label' => 'Roles', 'route_name' => 'admin.administrator.roles.index', 'icon' => 'clipboard-list', 'permission' => 'role_access'],
                            ['label' => 'Permissions', 'route_name' => 'admin.administrator.permissions.index', 'icon' => 'key', 'permission' => 'permission_access'],
                            ['label' => 'Users', 'route_name' => 'admin.administrator.users.index', 'icon' => 'user', 'permission' => 'user_access'],
                        ],
                    ],
                ],
            ],
            [
                'item_type' => 'section',
                'label' => 'Report',
                'slug' => 'report',
                'icon' => 'cog',
                'children' => [
                    ['label' => 'Web Statistics', 'route_name' => 'admin.analytics.index', 'icon' => 'chart-line', 'permission' => 'analytics_access'],
                    ['label' => 'Activity Logs', 'route_name' => 'admin.activity-log.index', 'icon' => 'history', 'permission' => 'activity_log_access'],
                    ['label' => 'Email Logs', 'route_name' => 'admin.administrator.email-logs.index', 'icon' => 'envelope-open-text', 'permission' => 'email_log_access'],
                    ['label' => 'Robots.txt', 'route_name' => 'admin.settings.robots-txt.index', 'icon' => 'robot', 'permission' => 'robots_txt_access'],
                ],
            ],
        ];
    }
}
