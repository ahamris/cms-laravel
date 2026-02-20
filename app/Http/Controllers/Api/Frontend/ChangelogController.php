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
            'data' => $changelogs->items(),
            'meta' => [
                'current_page' => $changelogs->currentPage(),
                'last_page' => $changelogs->lastPage(),
                'per_page' => $changelogs->perPage(),
                'total' => $changelogs->total(),
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
        $changelog = Changelog::where('slug', $slug)->where('is_active', true)->firstOrFail();

        return response()->json(['data' => $changelog]);
    }
}
