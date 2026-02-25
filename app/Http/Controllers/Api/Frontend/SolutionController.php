<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\SolutionListResource;
use App\Http\Resources\SolutionResource;
use App\Models\Solution;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class SolutionController extends Controller
{
    #[OA\Get(path: '/api/solutions', summary: 'List solutions', description: 'Active solutions with nested features (each feature includes modules). Hierarchy: solution → feature → module.', tags: ['Solution'], responses: [
        new OA\Response(response: 200, description: 'Solutions collection', content: new OA\JsonContent(properties: [
            new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/SolutionListItem')),
        ])),
    ])]
    public function index()
    {
        $solutions = Solution::active()
            ->ordered()
            ->with(['features' => function ($q) {
                $q->where('is_active', true)->ordered()->with([
                    'modules' => fn ($q) => $q->where('is_active', true)->ordered(),
                ]);
            }])
            ->get();

        $banner = get_setting('hero_background_solutions_index') ? get_image(get_setting('hero_background_solutions_index')) : null;

        return SolutionListResource::collection($solutions)->additional([
            'template' => 'solutions-list',
            'banner' => $banner,
        ]);
    }

    #[OA\Get(path: '/api/solutions/search', summary: 'Search solutions', description: 'Search in title, short_body, long_body. Throttled. Query: q, per_page.', tags: ['Solution'], parameters: [
        new OA\Parameter(name: 'q', in: 'query', required: true, schema: new OA\Schema(type: 'string', minLength: 2)),
        new OA\Parameter(name: 'per_page', in: 'query', schema: new OA\Schema(type: 'integer', default: 20)),
    ], responses: [
        new OA\Response(response: 200, description: 'Search results'),
        new OA\Response(response: 429, description: 'Too many requests'),
    ])]
    public function search(Request $request): JsonResponse
    {
        $query = trim((string) $request->input('q', ''));
        $perPage = max(1, min((int) $request->input('per_page', 20), 50));

        if (strlen($query) < 2) {
            return response()->json([
                'data' => [],
                'template' => 'solutions-search',
                'query' => $query,
                'count' => 0,
                'meta' => ['current_page' => 1, 'last_page' => 1, 'per_page' => $perPage, 'total' => 0],
            ]);
        }

        $like = '%'.$query.'%';
        $items = Solution::active()
            ->ordered()
            ->with(['features' => fn ($q) => $q->where('is_active', true)->ordered()])
            ->whereAny(['title', 'short_body', 'long_body'], 'like', $like)
            ->paginate($perPage);

        $resolved = SolutionListResource::collection($items->items())->resolve();
        return response()->json([
            'data' => $resolved['data'] ?? $resolved,
            'template' => 'solutions-search',
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

    #[OA\Get(path: '/api/solutions/{anchor}', summary: 'Solution by anchor', tags: ['Solution'], parameters: [
        new OA\Parameter(name: 'anchor', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
    ], responses: [
        new OA\Response(response: 200, description: 'Single solution with features and modules', content: new OA\JsonContent(ref: '#/components/schemas/Solution')),
        new OA\Response(response: 404, description: 'Not found'),
    ])]
    public function show(string $anchor)
    {
        $solution = Solution::where('anchor', $anchor)->where('is_active', true)->first();
        if (! $solution) {
            return response()->json(['message' => 'Solution not found.'], 404);
        }

        $solution->load([
            'features' => function ($q) {
                $q->where('is_active', true)->ordered()->with([
                    'modules' => fn ($q) => $q->where('is_active', true)->ordered(),
                ]);
            },
        ]);

        return new SolutionResource($solution);
    }
}
