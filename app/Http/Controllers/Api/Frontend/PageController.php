<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\ElementResource;
use App\Http\Resources\PageListResource;
use App\Http\Resources\PageResource;
use App\Models\Page;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class PageController extends Controller
{
    #[OA\Get(
        path: '/api/pages',
        summary: 'List active pages',
        description: 'Returns a paginated list of active pages (for React SPA).',
        tags: ['Pages'],
        parameters: [
            new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 100, default: 12), description: 'Items per page (1–100)'),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Paginated list of pages', content: new OA\JsonContent(ref: '#/components/schemas/PaginatedPageList')),
        ]
    )]
    public function index(Request $request)
    {
        $perPage = max(1, min((int) $request->input('per_page', 12), 100));
        $pages = Page::where('is_active', true)
            ->with('elements')
            ->orderBy('title')
            ->paginate($perPage);

        return PageListResource::collection($pages)->additional(['template' => 'pages-list']);
    }

    #[OA\Get(
        path: '/api/pages/search',
        summary: 'Search pages',
        description: 'Search in title, short_body, long_body. Throttled. Query: q, per_page.',
        tags: ['Pages'],
        parameters: [
            new OA\Parameter(name: 'q', in: 'query', required: true, schema: new OA\Schema(type: 'string', minLength: 2)),
            new OA\Parameter(name: 'per_page', in: 'query', schema: new OA\Schema(type: 'integer', default: 20)),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Search results'),
            new OA\Response(response: 429, description: 'Too many requests'),
        ]
    )]
    public function search(Request $request)
    {
        $query = trim((string) $request->input('q', ''));
        $perPage = max(1, min((int) $request->input('per_page', 20), 50));

        if (strlen($query) < 2) {
            return response()->json([
                'data' => [],
                'template' => 'pages-search',
                'query' => $query,
                'count' => 0,
                'meta' => ['current_page' => 1, 'last_page' => 1, 'per_page' => $perPage, 'total' => 0],
            ]);
        }

        $like = '%'.$query.'%';
        $pages = Page::where('is_active', true)
            ->with('elements')
            ->whereAny(['title', 'short_body', 'long_body'], 'like', $like)
            ->orderBy('title')
            ->paginate($perPage);

        $resolved = PageListResource::collection($pages->items())->resolve();
        return response()->json([
            'data' => $resolved['data'] ?? $resolved,
            'template' => 'pages-search',
            'query' => $query,
            'count' => $pages->total(),
            'meta' => [
                'current_page' => $pages->currentPage(),
                'last_page' => $pages->lastPage(),
                'per_page' => $pages->perPage(),
                'total' => $pages->total(),
            ],
        ]);
    }

    #[OA\Get(
        path: '/api/pages/{slug}',
        summary: 'Get a page by slug',
        description: 'Returns a single active page by slug.',
        tags: ['Pages'],
        parameters: [
            new OA\Parameter(name: 'slug', in: 'path', required: true, schema: new OA\Schema(type: 'string'), description: 'Page slug or nested path (e.g. about-us or services/web-development)'),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Single page', content: new OA\JsonContent(ref: '#/components/schemas/Page')),
            new OA\Response(response: 404, description: 'Page not found'),
        ]
    )]
    public function show(string $slug)
    {
        $page = $this->resolvePageBySlug($slug);
        if (! $page) {
            return response()->json(['message' => 'Page not found.'], 404);
        }

        $page->load(['marketingPersona', 'contentType', 'blocks', 'children', 'ogImage', 'tags', 'elements']);

        return new PageResource($page);
    }

    public function blocks(string $slug)
    {
        $page = $this->resolvePageBySlug($slug);
        if (! $page) {
            return response()->json(['message' => 'Page not found.'], 404);
        }

        $blocks = $page->blocks()->where('is_visible', true)->orderBy('sort_order')->get();

        return response()->json([
            'data' => $blocks->map(fn ($block) => [
                'type'     => $block->type,
                'content'  => $block->content,
                'settings' => $block->settings ?? (object) [],
            ]),
        ]);
    }

    public function tree(Request $request)
    {
        $pages = Page::where('is_active', true)
            ->roots()
            ->with([
                'elements',
                'children' => fn ($q) => $q->where('is_active', true)
                    ->orderBy('sort_order')
                    ->with('elements'),
            ])
            ->orderBy('sort_order')
            ->get(['id', 'title', 'slug', 'parent_id', 'sort_order', 'template']);

        return response()->json([
            'data' => $pages->map(fn (Page $page) => $this->pageTreeNode($page, $request))->values()->all(),
        ]);
    }

    /**
     * Tree nodes always include `elements` as a JSON array (empty when none) for the SPA.
     *
     * @return array<string, mixed>
     */
    private function pageTreeNode(Page $page, Request $request): array
    {
        $elements = $page->relationLoaded('elements') ? $page->elements : collect();

        $node = array_merge($page->only(['id', 'title', 'slug', 'parent_id', 'sort_order', 'template']), [
            'elements' => ElementResource::collection($elements)->resolve($request),
        ]);

        if ($page->relationLoaded('children')) {
            $node['children'] = $page->children
                ->map(fn (Page $child) => $this->pageTreeNode($child, $request))
                ->values()
                ->all();
        } else {
            $node['children'] = [];
        }

        return $node;
    }

    private function resolvePageBySlug(string $slug): ?Page
    {
        return Page::where('slug', $slug)->where('is_active', true)->first();
    }
}
