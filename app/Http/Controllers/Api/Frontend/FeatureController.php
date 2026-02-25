<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\FeatureListResource;
use App\Http\Resources\FeatureResource;
use App\Models\Feature;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class FeatureController extends Controller
{
    #[OA\Get(path: '/api/features', summary: 'List features', description: 'Active features with solution and modules. Hierarchy: solution → feature → module.', tags: ['Solution'], responses: [
        new OA\Response(response: 200, description: 'Features collection', content: new OA\JsonContent(properties: [
            new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/FeatureListItem')),
        ])),
    ])]
    public function index()
    {
        $features = Feature::with(['solution', 'modules'])->active()->ordered()->get();

        return FeatureListResource::collection($features)->additional(['template' => 'features-list']);
    }

    #[OA\Get(path: '/api/features/search', summary: 'Search features', description: 'Search in title, description. Throttled. Query: q, per_page.', tags: ['Solution'], parameters: [
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
                'template' => 'features-search',
                'query' => $query,
                'count' => 0,
                'meta' => ['current_page' => 1, 'last_page' => 1, 'per_page' => $perPage, 'total' => 0],
            ]);
        }

        $like = '%'.$query.'%';
        $items = Feature::with(['solution', 'modules'])->active()->ordered()
            ->whereAny(['title', 'description'], 'like', $like)
            ->paginate($perPage);

        $resolved = FeatureListResource::collection($items->items())->resolve();
        return response()->json([
            'data' => $resolved['data'] ?? $resolved,
            'template' => 'features-search',
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

    #[OA\Get(path: '/api/features/{anchor}', summary: 'Feature by anchor', tags: ['Solution'], parameters: [
        new OA\Parameter(name: 'anchor', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
    ], responses: [
        new OA\Response(response: 200, description: 'Single feature with solution and modules', content: new OA\JsonContent(ref: '#/components/schemas/Feature')),
        new OA\Response(response: 404, description: 'Not found'),
    ])]
    public function show(string $anchor)
    {
        $feature = Feature::with(['solution', 'modules'])
            ->active()
            ->where('anchor', $anchor)
            ->first();
        if (! $feature) {
            return response()->json(['message' => 'Feature not found.'], 404);
        }

        return new FeatureResource($feature);
    }
}
