<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\FooterLink;
use App\Models\MegaMenuItem;
use App\Models\Setting;
use App\Models\StickyMenuItem;
use Illuminate\Http\JsonResponse;

/**
 * Frontend API: header and footer menu structures for headless consumption.
 */
class MenuController extends Controller
{
    /**
     * Header menu (mega menu) structure: nested tree with resolved URLs.
     * Uses cached data; no DB query until menu is created/updated/deleted.
     */
    public function header(): JsonResponse
    {
        $cached = MegaMenuItem::getCached();
        $tree = array_map(fn (array $item) => $this->mapCachedMenuItem($item), $cached);

        $payload = [
            'items' => array_values($tree),
            'settings' => [
                'sticky' => (bool) Setting::getValue('site_header_sticky', false),
                'login_link_enabled' => (bool) Setting::getValue('site_header_login_link_enabled', true),
                'login_link_url' => Setting::getValue('site_header_login_link_url') ?: '#',
            ],
        ];

        return response()->json($payload);
    }

    /**
     * Footer menu structure: links grouped by column.
     */
    public function footer(): JsonResponse
    {
        $cached = FooterLink::getCached();

        $columns = $cached->map(function ($links, $column) {
            return [
                'column' => (int) $column,
                'links' => $links->map(fn ($link) => [
                    'id' => $link->id,
                    'title' => $link->title,
                    'url' => $link->url,
                    'order' => $link->order,
                ])->values()->toArray(),
            ];
        })->values()->toArray();

        $payload = [
            'columns' => $columns,
        ];

        return response()->json($payload);
    }

    /**
     * Combined menus: header + footer in one response.
     * Header uses cached data; no DB query until menu is edited.
     */
    public function index(): JsonResponse
    {
        $headerCached = MegaMenuItem::getCached();
        $footerCached = FooterLink::getCached();

        $payload = [
            'header' => [
                'items' => array_values(array_map(fn (array $item) => $this->mapCachedMenuItem($item), $headerCached)),
                'settings' => [
                    'sticky' => (bool) Setting::getValue('site_header_sticky', false),
                    'login_link_enabled' => (bool) Setting::getValue('site_header_login_link_enabled', true),
                    'login_link_url' => Setting::getValue('site_header_login_link_url') ?: '#',
                ],
            ],
            'footer' => [
                'columns' => $footerCached->map(function ($links, $column) {
                    return [
                        'column' => (int) $column,
                        'links' => $links->map(fn ($link) => [
                            'id' => $link->id,
                            'title' => $link->title,
                            'url' => $link->url,
                            'order' => $link->order,
                        ])->values()->toArray(),
                    ];
                })->values()->toArray(),
            ],
        ];

        return response()->json($payload);
    }

    /**
     * Sticky menu items (e.g. for mobile or secondary nav).
     */
    public function sticky(): JsonResponse
    {
        $items = StickyMenuItem::getActiveItems();

        $payload = [
            'items' => $items->map(fn ($item) => [
                'id' => $item->id,
                'title' => $item->title,
                'icon' => $item->icon,
                'link' => $item->link,
                'link_type' => $item->link_type,
                'is_external' => $item->is_external,
                'sort_order' => $item->sort_order,
            ])->toArray(),
        ];

        return response()->json($payload);
    }

    /**
     * Map a cached menu item array to API response node (data only: no styles or type).
     */
    private function mapCachedMenuItem(array $item): array
    {
        $node = [
            'id' => $item['id'] ?? null,
            'title' => $item['title'] ?? '',
            'subtitle' => $item['subtitle'] ?? null,
            'description' => $item['description'] ?? null,
            'url' => $item['url'] ?? '#',
            'order' => (int) ($item['order'] ?? 0),
        ];

        $children = $item['children'] ?? [];
        $node['children'] = array_values(array_map(fn (array $child) => $this->mapCachedMenuItem($child), $children));

        return $node;
    }
}
