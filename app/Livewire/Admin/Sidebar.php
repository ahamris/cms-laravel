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

            // ── CONTENT ──────────────────────────────────────
            [
                'item_type' => 'section',
                'label' => 'Content',
                'slug' => 'content',
                'icon' => 'layer-group',
                'children' => [
                    [
                        'label' => 'Pages',
                        'route_name' => 'admin.page.index',
                        'icon' => 'file-text',
                        'permission' => 'page_access',
                        'active_pattern' => 'admin.page*,admin.legal*,admin.homepage*,admin.changelog*,admin.event*,admin.settings.hero-backgrounds*,admin.faq-module*,admin.partner-tech-item*,admin.static-page*',
                        'children' => [
                            ['label' => 'Homepage', 'route_name' => 'admin.homepage.edit', 'icon' => 'home', 'permission' => 'page_access'],
                            ['label' => 'Pages', 'route_name' => 'admin.page.index', 'icon' => 'file-text', 'permission' => 'page_access'],
                            ['label' => 'Legal Pages', 'route_name' => 'admin.legal.index', 'icon' => 'list-alt', 'permission' => 'legal_access'],
                            ['label' => 'Static Pages', 'route_name' => 'admin.static-page.index', 'icon' => 'file-alt', 'permission' => 'static_page_access'],
                            ['label' => 'FAQ Modules', 'route_name' => 'admin.faq-module.index', 'icon' => 'question-circle', 'permission' => 'faq_module_access'],
                        ],
                    ],
                    [
                        'label' => 'Articles',
                        'route_name' => 'admin.blog.index',
                        'icon' => 'newspaper',
                        'permission' => 'blog_access',
                        'active_pattern' => 'admin.blog*,admin.blog-category*,admin.blog-type*,admin.article-category*,admin.tag*,admin.comment*',
                        'children' => [
                            ['label' => 'All Articles', 'route_name' => 'admin.blog.index', 'icon' => 'newspaper', 'permission' => 'blog_access'],
                            ['label' => 'Categories', 'route_name' => 'admin.article-category.index', 'icon' => 'folder', 'permission' => 'blog_access'],
                            ['label' => 'Tags', 'route_name' => 'admin.tag.index', 'icon' => 'tags', 'permission' => 'blog_access'],
                            ['label' => 'Comments', 'route_name' => 'admin.comment.index', 'icon' => 'comments', 'permission' => 'comment_access'],
                        ],
                    ],
                    [
                        'item_type' => 'link',
                        'label' => 'Media Library',
                        'slug' => 'media-library',
                        'route_name' => 'admin.media-library.index',
                        'icon' => 'images',
                        'permission' => 'media_access',
                    ],
                    [
                        'label' => 'Forms',
                        'route_name' => 'admin.form.index',
                        'icon' => 'clipboard-list',
                        'active_pattern' => 'admin.form*',
                        'children' => [
                            ['label' => 'All Forms', 'route_name' => 'admin.form.index', 'icon' => 'clipboard-list'],
                        ],
                    ],
                    [
                        'label' => 'Documentation',
                        'route_name' => 'admin.doc-sections.index',
                        'icon' => 'book',
                        'permission' => 'page_access',
                        'active_pattern' => 'admin.doc-sections*,admin.doc-pages*',
                        'children' => [
                            ['label' => 'Sections', 'route_name' => 'admin.doc-sections.index', 'icon' => 'folder', 'permission' => 'page_access'],
                            ['label' => 'Pages', 'route_name' => 'admin.doc-pages.index', 'icon' => 'file-alt', 'permission' => 'page_access'],
                        ],
                    ],
                ],
            ],

            // ── CRM ──────────────────────────────────────────
            [
                'item_type' => 'section',
                'label' => 'CRM',
                'slug' => 'crm',
                'icon' => 'address-book',
                'children' => [
                    ['label' => 'Dashboard', 'route_name' => 'admin.crm.dashboard', 'icon' => 'chart-pie', 'active_pattern' => 'admin.crm.dashboard'],
                    ['label' => 'Contacts', 'route_name' => 'admin.crm.contacts.index', 'icon' => 'users', 'active_pattern' => 'admin.crm.contacts*'],
                    ['label' => 'Deals', 'route_name' => 'admin.crm.deals.index', 'icon' => 'handshake', 'active_pattern' => 'admin.crm.deals*'],
                    ['label' => 'Messages', 'route_name' => 'admin.crm.messages.index', 'icon' => 'envelope', 'active_pattern' => 'admin.crm.messages*'],
                    ['label' => 'Tickets', 'route_name' => 'admin.crm.tickets.index', 'icon' => 'ticket', 'active_pattern' => 'admin.crm.tickets*'],
                    ['label' => 'Appointments', 'route_name' => 'admin.crm.appointments.index', 'icon' => 'calendar-check', 'active_pattern' => 'admin.crm.appointments*'],
                    ['label' => 'Notes', 'route_name' => 'admin.crm.notes.index', 'icon' => 'sticky-note', 'active_pattern' => 'admin.crm.notes*'],
                ],
            ],

            // ── MARKETING ────────────────────────────────────
            [
                'item_type' => 'section',
                'label' => 'Marketing',
                'slug' => 'marketing',
                'icon' => 'bullhorn',
                'children' => [
                    ['label' => 'Marketing Dashboard', 'route_name' => 'admin.marketing.dashboard', 'icon' => 'chart-line', 'permission' => 'blog_access'],
                    ['label' => 'Content Plans', 'route_name' => 'admin.marketing.content-plans.index', 'icon' => 'calendar-alt', 'permission' => 'blog_access'],
                    ['label' => 'Intent Briefs', 'route_name' => 'admin.marketing.intent-briefs.index', 'icon' => 'lightbulb', 'permission' => 'blog_access'],
                    [
                        'label' => 'Social Media',
                        'route_name' => 'admin.social-settings.index',
                        'icon' => 'share-alt',
                        'permission' => 'social_setting_access',
                        'active_pattern' => 'admin.social-settings*,admin.settings.social-media-platforms*',
                        'children' => [
                            ['label' => 'Social Pages', 'route_name' => 'admin.social-settings.index', 'icon' => 'share-alt', 'permission' => 'social_setting_access'],
                            ['label' => 'Auto Post', 'route_name' => 'admin.settings.social-media-platforms.index', 'icon' => 'share-nodes', 'permission' => 'social_media_platform_access'],
                        ],
                    ],
                ],
            ],

            // ── ELEMENTS ─────────────────────────────────────
            [
                'item_type' => 'section',
                'label' => 'Elements',
                'slug' => 'elements',
                'icon' => 'cubes',
                'children' => [
                    [
                        'label' => 'CTA',
                        'route_name' => 'admin.element-cta.index',
                        'icon' => 'bullhorn',
                        'permission' => 'page_access',
                        'active_pattern' => 'admin.element-cta*',
                    ],
                    [
                        'label' => 'FAQ',
                        'route_name' => 'admin.element-faq.index',
                        'icon' => 'circle-question',
                        'permission' => 'page_access',
                        'active_pattern' => 'admin.element-faq*',
                    ],
                    [
                        'label' => 'Related content',
                        'route_name' => 'admin.element-related-content.index',
                        'icon' => 'diagram-project',
                        'permission' => 'page_access',
                        'active_pattern' => 'admin.element-related-content*',
                    ],
                    [
                        'label' => 'Card grid',
                        'route_name' => 'admin.element-card-grid.index',
                        'icon' => 'table-cells',
                        'permission' => 'page_access',
                        'active_pattern' => 'admin.element-card-grid*',
                    ],
                    [
                        'label' => 'Hero video',
                        'route_name' => 'admin.element-hero-video.index',
                        'icon' => 'film',
                        'permission' => 'page_access',
                        'active_pattern' => 'admin.element-hero-video*',
                    ],
                    [
                        'label' => 'Newsletter',
                        'route_name' => 'admin.element-newsletter.index',
                        'icon' => 'envelope-open-text',
                        'permission' => 'page_access',
                        'active_pattern' => 'admin.element-newsletter*',
                    ],
                ],
            ],

            // ── SYSTEM ───────────────────────────────────────
            [
                'item_type' => 'section',
                'label' => 'System',
                'slug' => 'system',
                'icon' => 'cog',
                'children' => [
                    [
                        'label' => 'Menus & Navigation',
                        'route_name' => 'admin.settings.mega-menu.index',
                        'icon' => 'bars',
                        'permission' => 'mega_menu_access',
                        'active_pattern' => 'admin.settings.mega-menu*,admin.sticky-menu-item*,admin.settings.footer-links*',
                        'children' => [
                            ['label' => 'Mega Menu', 'route_name' => 'admin.settings.mega-menu.index', 'icon' => 'bars', 'permission' => 'mega_menu_access'],
                            ['label' => 'Sticky Menu', 'route_name' => 'admin.sticky-menu-item.index', 'icon' => 'bars', 'permission' => 'sticky_menu_item_access'],
                            ['label' => 'Footer Links', 'route_name' => 'admin.settings.footer-links.index', 'icon' => 'link', 'permission' => 'footer_link_access'],
                        ],
                    ],
                    [
                        'label' => 'SEO Settings',
                        'route_name' => 'admin.settings.robots-txt.index',
                        'icon' => 'search',
                        'permission' => 'robots_txt_access',
                        'active_pattern' => 'admin.settings.robots-txt*',
                    ],
                    [
                        'label' => 'AI Settings',
                        'route_name' => 'admin.settings.ai.index',
                        'icon' => 'robot',
                        'active_pattern' => 'admin.settings.ai*',
                    ],
                    [
                        'label' => 'Site Settings',
                        'route_name' => 'admin.settings.general.index',
                        'icon' => 'sliders-h',
                        'permission' => 'general_access',
                        'active_pattern' => 'admin.settings.general*,admin.settings.contact*,admin.settings.theme*,admin.settings.login*,admin.settings.cookie*,admin.mail*,admin.translations*,admin.security.two-factor*,admin.external-code*,admin.image-optimizer*',
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
                            ['label' => 'External Codes', 'route_name' => 'admin.external-code.index', 'icon' => 'code', 'permission' => 'external_code_access'],
                            ['label' => 'Optimize Images', 'route_name' => 'admin.image-optimizer.index', 'icon' => 'folder', 'permission' => 'image_optimizer_access'],
                        ],
                    ],
                    [
                        'label' => 'Users & Roles',
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

            // ── REPORTS ──────────────────────────────────────
            [
                'item_type' => 'section',
                'label' => 'Reports',
                'slug' => 'reports',
                'icon' => 'chart-bar',
                'children' => [
                    ['label' => 'Web Statistics', 'route_name' => 'admin.analytics.index', 'icon' => 'chart-line', 'permission' => 'analytics_access'],
                    ['label' => 'Activity Logs', 'route_name' => 'admin.activity-log.index', 'icon' => 'history', 'permission' => 'activity_log_access'],
                    ['label' => 'Email Logs', 'route_name' => 'admin.administrator.email-logs.index', 'icon' => 'envelope-open-text', 'permission' => 'email_log_access'],
                ],
            ],
        ];
    }
}
