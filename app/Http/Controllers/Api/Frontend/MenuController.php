<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\FooterLink;
use App\Models\MegaMenuItem;
use App\Models\StickyMenuItem;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

/**
 * Frontend API: header and footer menu structures for headless consumption.
 */
class MenuController extends Controller
{
    #[OA\Get(path: '/api/menus/header', summary: 'Header menu', description: 'Mega menu tree with slug and page_type per item (headless: frontend builds routes). Header settings included.', tags: ['Menus'], responses: [
        new OA\Response(response: 200, description: 'Header menu', content: new OA\JsonContent(ref: '#/components/schemas/HeaderMenuResponse')),
    ])]
    public function header(): JsonResponse
    {
        $cached = MegaMenuItem::getCached();
        $tree = array_map(fn (array $item) => $this->mapCachedMenuItem($item), $cached);

        $payload = [
            'items' => array_values($tree),
        ];

        return response()->json($payload);
    }

    #[OA\Get(path: '/api/menus/footer', summary: 'Footer menu', description: 'Footer links grouped by column.', tags: ['Menus'], responses: [
        new OA\Response(response: 200, description: 'Footer menu', content: new OA\JsonContent(ref: '#/components/schemas/FooterMenuResponse')),
    ])]
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

    #[OA\Get(path: '/api/menus', summary: 'All menus', description: 'Header and footer menus in one response.', tags: ['Menus'], responses: [
        new OA\Response(response: 200, description: 'Header and footer menus'),
    ])]
    public function index(): JsonResponse
    {
        $headerCached = MegaMenuItem::getCached();
        $footerCached = FooterLink::getCached();

        $payload = [
            'header' => [
                'items' => array_values(array_map(fn (array $item) => $this->mapCachedMenuItem($item), $headerCached)),
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

    #[OA\Get(path: '/api/menus/sticky', summary: 'Sticky menu', description: 'Sticky menu items for mobile or secondary nav.', tags: ['Menus'], responses: [
        new OA\Response(response: 200, description: 'Sticky items', content: new OA\JsonContent(properties: [new OA\Property(property: 'items', type: 'array', items: new OA\Items(ref: '#/components/schemas/StickyMenuItem'))])),
    ])]
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
     * Map a cached menu item array to API response node.
     * Headless: return slug and page_type only (no url); frontend builds routes.
     */
    private function mapCachedMenuItem(array $item): array
    {
        $node = [
            'id' => $item['id'] ?? null,
            'title' => $item['title'] ?? '',
            'subtitle' => $item['subtitle'] ?? null,
            'description' => $item['description'] ?? null,
            'url' => $item['url'] ?? null,
            'slug' => $item['page']['slug'] ?? null,
            'page_type' => ! empty($item['page_id']) ? 'page' : 'custom',
            'order' => (int) ($item['order'] ?? 0),
            'tags' => $item['tags'] ?? [],
            'align' => (int) ($item['align'] ?? 1),
        ];

        $children = $item['children'] ?? [];
        $node['children'] = array_values(array_map(fn (array $child) => $this->mapCachedMenuItem($child), $children));

        if (! empty($item['sidebar'])) {
            $node['sidebar'] = [
                'title' => $item['sidebar']['title'] ?? '',
                'description' => $item['sidebar']['description'] ?? null,
                'tags' => $item['sidebar']['tags'] ?? [],
            ];
        } else {
            $node['sidebar'] = null;
        }

        return $node;
    }
}
