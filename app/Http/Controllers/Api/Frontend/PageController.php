<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
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
            ->orderBy('title')
            ->paginate($perPage);

        return PageListResource::collection($pages);
    }

    #[OA\Get(
        path: '/api/pages/{slug}',
        summary: 'Get a page by slug',
        description: 'Returns a single active page by slug.',
        tags: ['Pages'],
        parameters: [
            new OA\Parameter(name: 'slug', in: 'path', required: true, schema: new OA\Schema(type: 'string'), description: 'Page slug (e.g. about-us)'),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Single page', content: new OA\JsonContent(ref: '#/components/schemas/Page')),
            new OA\Response(response: 404, description: 'Page not found'),
        ]
    )]
    public function show(string $slug)
    {
        $page = Page::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $page->load(['marketingPersona', 'contentType']);

        return new PageResource($page);
    }
}
