<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Changelog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ChangelogController extends Controller
{
    #[OA\Get(path: '/api/changelog', summary: 'List changelog', description: 'Paginated changelog entries. Optional query: per_page, status=api|all.', tags: ['Changelog'], parameters: [
        new OA\Parameter(name: 'per_page', in: 'query', schema: new OA\Schema(type: 'integer', default: 10), description: 'Items per page'),
        new OA\Parameter(name: 'status', in: 'query', schema: new OA\Schema(type: 'string', enum: ['api', 'all']), description: 'Filter by status'),
    ], responses: [
        new OA\Response(response: 200, description: 'Changelog list', content: new OA\JsonContent(ref: '#/components/schemas/ChangelogListResponse')),
    ])]
    public function index(Request $request): JsonResponse
    {
        $perPage = max(1, min((int) $request->input('per_page', 10), 50));
        $status = $request->input('status', 'api'); // 'api' for API changelog, or omit for all

        $query = Changelog::active()->ordered();

        if ($status === 'api') {
            $query->byStatus('api');
        } elseif ($status !== 'all') {
            $query->whereNotIn('status', ['api']);
        }

        $changelogs = $query->paginate($perPage);

        return response()->json([
            'template' => 'changelog',
            'data' => $changelogs->items(),
            'meta' => [
                'current_page' => $changelogs->currentPage(),
                'last_page' => $changelogs->lastPage(),
                'per_page' => $changelogs->perPage(),
                'total' => $changelogs->total(),
            ],
        ]);
    }

    #[OA\Get(path: '/api/changelog/search', summary: 'Search changelog', description: 'Search in title, description, content. Throttled. Query: q, per_page.', tags: ['Changelog'], parameters: [
        new OA\Parameter(name: 'q', in: 'query', required: true, schema: new OA\Schema(type: 'string', minLength: 2)),
        new OA\Parameter(name: 'per_page', in: 'query', schema: new OA\Schema(type: 'integer', default: 20)),
    ], responses: [
        new OA\Response(response: 200, description: 'Search results', content: new OA\JsonContent(properties: [
            new OA\Property(property: 'data', type: 'array'),
            new OA\Property(property: 'template', type: 'string', example: 'changelog-search'),
            new OA\Property(property: 'query', type: 'string'),
            new OA\Property(property: 'count', type: 'integer'),
            new OA\Property(property: 'meta', type: 'object', nullable: true),
        ])),
        new OA\Response(response: 429, description: 'Too many requests'),
    ])]
    public function search(Request $request): JsonResponse
    {
        $query = trim((string) $request->input('q', ''));
        $perPage = max(1, min((int) $request->input('per_page', 20), 50));

        if (strlen($query) < 2) {
            return response()->json([
                'data' => [],
                'template' => 'changelog-search',
                'query' => $query,
                'count' => 0,
                'meta' => ['current_page' => 1, 'last_page' => 1, 'per_page' => $perPage, 'total' => 0],
            ]);
        }

        $like = '%'.$query.'%';
        $items = Changelog::active()
            ->ordered()
            ->whereAny(['title', 'description', 'content'], 'like', $like)
            ->paginate($perPage);

        return response()->json([
            'data' => $items->items(),
            'template' => 'changelog-search',
            'query' => $query,
            'count' => $items->total(),
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'per_page' => $items->perPage(),
                'total' => $items->total(),
            ],
        ]);
    }

    #[OA\Get(path: '/api/changelog/{slug}', summary: 'Changelog entry by slug', tags: ['Changelog'], parameters: [
        new OA\Parameter(name: 'slug', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
    ], responses: [
        new OA\Response(response: 200, description: 'Changelog entry', content: new OA\JsonContent(properties: [new OA\Property(property: 'data', ref: '#/components/schemas/ChangelogEntry')])),
        new OA\Response(response: 404, description: 'Not found'),
    ])]
    public function show(string $slug): JsonResponse
    {
        $changelog = Changelog::where('slug', $slug)->where('is_active', true)->first();
        if (! $changelog) {
            return response()->json(['message' => 'Changelog entry not found.'], 404);
        }

        return response()->json(['template' => 'changelog-detail', 'data' => $changelog]);
    }
}
