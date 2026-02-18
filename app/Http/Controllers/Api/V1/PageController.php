<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PageResource;
use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class PageController extends Controller
{
    #[OA\Get(
        path: '/v1/pages',
        summary: 'List published pages',
        tags: ['Pages'],
        parameters: [
            new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', default: 15), description: 'Items per page'),
            new OA\Parameter(name: 'X-API-Key', in: 'header', required: false, schema: new OA\Schema(type: 'string'), description: 'Required when CMS_API_KEY is set on the server. Alternatively use Authorization: Bearer <key>.'),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Paginated list of published pages', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/Page')),
                new OA\Property(property: 'links', type: 'object'),
                new OA\Property(property: 'meta', type: 'object'),
            ])),
            new OA\Response(response: 401, description: 'Invalid or missing API key (when CMS_API_KEY is configured)'),
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $pages = Page::query()
            ->published()
            ->orderBy('published_at', 'desc')
            ->paginate($request->integer('per_page', 15));

        return PageResource::collection($pages)->toResponse($request);
    }

    #[OA\Get(
        path: '/v1/pages/{slug}',
        summary: 'Get page by slug',
        tags: ['Pages'],
        parameters: [
            new OA\Parameter(name: 'slug', in: 'path', required: true, schema: new OA\Schema(type: 'string'), description: 'Page slug'),
            new OA\Parameter(name: 'X-API-Key', in: 'header', required: false, schema: new OA\Schema(type: 'string'), description: 'Required when CMS_API_KEY is set on the server. Alternatively use Authorization: Bearer <key>.'),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(ref: '#/components/schemas/Page')),
            new OA\Response(response: 401, description: 'Invalid or missing API key (when CMS_API_KEY is configured)'),
            new OA\Response(response: 404, description: 'Page not found or not published'),
        ]
    )]
    public function show(string $slug): JsonResponse
    {
        $page = Page::query()
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json(new PageResource($page));
    }
}
