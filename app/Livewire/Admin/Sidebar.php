<?php

namespace App\Livewire\Admin;

use Illuminate\Contracts\View\View;
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
        $menuItems = collect(StaticSidebarItem::fromArray(self::staticMenuDefinition()));

        return view('livewire.admin.sidebar', [
            'menu' => $menu,
            'menuItems' => $menuItems,
        ]);
    }

    /**
     * Static sidebar menu definition (typed structure). Public so Search and others can use it.
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
                    ['label' => 'Organization Names', 'route_name' => 'admin.content.organization-name.index', 'icon' => 'building'],
                    ['label' => 'Entries', 'route_name' => 'admin.administrator.contact-forms.index', 'icon' => 'envelope'],
                    ['label' => 'Contacts', 'route_name' => 'admin.administrator.contacts.index', 'icon' => 'users'],
                    ['label' => 'Subscriptions', 'route_name' => 'admin.administrator.subscriptions.index', 'icon' => 'clipboard-check'],
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
                        'active_pattern' => 'admin.content.blog*,admin.content.blog-category*,admin.content.comment*',
                        'children' => [
                            ['label' => 'Categories', 'route_name' => 'admin.content.blog-category.index', 'icon' => 'folder'],
                            ['label' => 'Article', 'route_name' => 'admin.content.blog.index', 'icon' => 'newspaper'],
                            ['label' => 'Comments', 'route_name' => 'admin.content.comment.index', 'icon' => 'comments'],
                        ],
                    ],
                    [
                        'label' => 'Pages',
                        'route_name' => 'admin.content.page.index',
                        'icon' => 'puzzle-piece',
                        'active_pattern' => 'admin.content.page*,admin.content.legal*,admin.content.changelog*,admin.content.about*,admin.content.event*,admin.settings.hero-backgrounds*',
                        'children' => [
                            ['label' => 'Pages', 'route_name' => 'admin.content.page.index', 'icon' => 'file-text'],
                            ['label' => 'Legal Pages', 'route_name' => 'admin.content.legal.index', 'icon' => 'list-alt'],
                            ['label' => 'Changelog', 'route_name' => 'admin.content.changelog.index', 'icon' => 'list-alt'],
                            ['label' => 'About', 'route_name' => 'admin.content.about.index', 'icon' => 'info-circle'],
                            ['label' => 'Event', 'route_name' => 'admin.content.event.index', 'icon' => 'calendar-alt'],
                            ['label' => 'Header Settings', 'route_name' => 'admin.settings.hero-backgrounds.index', 'icon' => 'heading'],
                        ],
                    ],
                    [
                        'label' => 'Solution & Modules',
                        'route_name' => 'admin.content.solution.index',
                        'icon' => 'puzzle-piece',
                        'active_pattern' => 'admin.content.solution*,admin.content.feature*,admin.content.module*',
                        'children' => [
                            ['label' => 'Solution', 'route_name' => 'admin.content.solution.index', 'icon' => 'lightbulb'],
                            ['label' => 'Modules', 'route_name' => 'admin.content.module.index', 'icon' => 'cubes'],
                            ['label' => 'Features', 'route_name' => 'admin.content.feature.index', 'icon' => 'puzzle-piece'],
                        ],
                    ],
                    [
                        'label' => 'Widgets',
                        'route_name' => 'admin.social-settings.index',
                        'icon' => 'list-alt',
                        'active_pattern' => 'admin.social-settings*',
                        'children' => [
                            ['label' => 'Social Media', 'route_name' => 'admin.social-settings.index', 'icon' => 'share-alt'],
                        ],
                    ],
                    [
                        'label' => 'Plan Management',
                        'route_name' => 'admin.content.pricing-plan.index',
                        'icon' => 'tags',
                        'active_pattern' => 'admin.content.pricing-plan*,admin.content.pricing-booster*,admin.content.pricing-feature*',
                        'children' => [
                            ['label' => 'Pricing Plans', 'route_name' => 'admin.content.pricing-plan.index', 'icon' => 'dollar-sign'],
                            ['label' => 'Addons', 'route_name' => 'admin.content.pricing-booster.index', 'icon' => 'rocket'],
                            ['label' => 'Features', 'route_name' => 'admin.content.pricing-feature.index', 'icon' => 'list-check'],
                        ],
                    ],
                    [
                        'label' => 'Academy & Live',
                        'route_name' => 'admin.content.live-session.index',
                        'icon' => 'video',
                        'active_pattern' => 'admin.content.live-session*,admin.content.presenter*,admin.content.session-registration*,admin.content.academy-category*,admin.content.academy-chapter*,admin.content.academy-video*',
                        'children' => [
                            ['label' => 'Document Categories', 'route_name' => 'admin.content.academy-category.index', 'icon' => 'folder'],
                            ['label' => 'Chapters', 'route_name' => 'admin.content.academy-chapter.index', 'icon' => 'book'],
                            ['label' => 'Document Videos', 'route_name' => 'admin.content.academy-video.index', 'icon' => 'video'],
                            ['label' => 'Live Sessions', 'route_name' => 'admin.content.live-session.index', 'icon' => 'play-circle'],
                            ['label' => 'Presenters', 'route_name' => 'admin.content.presenter.index', 'icon' => 'user-tie'],
                            ['label' => 'Registrations', 'route_name' => 'admin.content.session-registration.index', 'icon' => 'user-check'],
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
                        'active_pattern' => 'admin.vacancies.*',
                        'children' => [
                            ['label' => 'Vacancies', 'route_name' => 'admin.vacancies.index', 'icon' => 'briefcase'],
                            ['label' => 'Job Applications', 'route_name' => 'admin.job-applications.index', 'icon' => 'envelope-open-text'],
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
                    ],
                    [
                        'label' => 'Site Settings',
                        'route_name' => 'admin.settings.general.index',
                        'icon' => 'user-shield',
                        'active_pattern' => 'admin.settings.general*,admin.settings.contact*,admin.settings.theme*,admin.settings.login*,admin.settings.cookie*,admin.mail*,admin.translations*,admin.security.two-factor*,admin.content.external-code*,admin.image-optimizer*',
                        'children' => [
                            ['label' => 'General Settings', 'route_name' => 'admin.settings.general.index', 'icon' => 'sliders-h'],
                            ['label' => 'Contact Page', 'route_name' => 'admin.settings.contact.index', 'icon' => 'address-card'],
                            ['label' => 'Theme Settings', 'route_name' => 'admin.settings.theme.index', 'icon' => 'palette'],
                            ['label' => 'Admin Theme', 'route_name' => 'admin.settings.admintheme', 'icon' => 'palette'],
                            ['label' => 'Login Settings', 'route_name' => 'admin.settings.login.index', 'icon' => 'sign-in-alt'],
                            ['label' => 'Cookie Settings', 'route_name' => 'admin.settings.cookie.index', 'icon' => 'cookie-bite'],
                            ['label' => 'Mail Settings', 'route_name' => 'admin.mail.index', 'icon' => 'envelope'],
                            ['label' => 'Translation Manager', 'route_name' => 'admin.translations.index', 'icon' => 'language'],
                            ['label' => 'Two-Factor Auth', 'route_name' => 'admin.security.two-factor.index', 'icon' => 'shield-alt'],
                            ['label' => 'External Codes', 'route_name' => 'admin.content.external-code.index', 'icon' => 'code'],
                            ['label' => 'Optimize Images', 'route_name' => 'admin.image-optimizer.index', 'icon' => 'folder'],
                        ],
                    ],
                    [
                        'label' => 'Routes',
                        'route_name' => 'admin.settings.mega-menu.index',
                        'icon' => 'user-shield',
                        'active_pattern' => 'admin.settings.mega-menu*,admin.content.sticky-menu-item*,admin.settings.footer-links*',
                        'children' => [
                            ['label' => 'Mega Menu', 'route_name' => 'admin.settings.mega-menu.index', 'icon' => 'bars'],
                            ['label' => 'Sticky Menu', 'route_name' => 'admin.content.sticky-menu-item.index', 'icon' => 'bars'],
                            ['label' => 'Footer Links', 'route_name' => 'admin.settings.footer-links.index', 'icon' => 'link'],
                        ],
                    ],
                    [
                        'label' => 'User Management',
                        'route_name' => 'admin.administrator.users.index',
                        'icon' => 'user-shield',
                        'active_pattern' => 'admin.administrator.users*,admin.administrator.permissions*,admin.administrator.roles*',
                        'children' => [
                            ['label' => 'Users', 'route_name' => 'admin.administrator.users.index', 'icon' => 'user'],
                            ['label' => 'Permissions', 'route_name' => 'admin.administrator.permissions.index', 'icon' => 'key'],
                            ['label' => 'Roles', 'route_name' => 'admin.administrator.roles.index', 'icon' => 'clipboard-list'],
                        ],
                    ],
                    [
                        'label' => 'SMM',
                        'route_name' => 'admin.settings.social-media-platforms.index',
                        'icon' => 'share-nodes',
                    ],
                ],
            ],
            [
                'item_type' => 'section',
                'label' => 'Report',
                'slug' => 'report',
                'icon' => 'cog',
                'children' => [
                    ['label' => 'Web Statistics', 'route_name' => 'admin.analytics.index', 'icon' => 'chart-line'],
                    ['label' => 'Activity Logs', 'route_name' => 'admin.activity-log.index', 'icon' => 'history'],
                    ['label' => 'Email Logs', 'route_name' => 'admin.administrator.email-logs.index', 'icon' => 'envelope-open-text'],
                    ['label' => 'Robots.txt', 'route_name' => 'admin.settings.robots-txt.index', 'icon' => 'robot'],
                ],
            ],
        ];
    }
}
