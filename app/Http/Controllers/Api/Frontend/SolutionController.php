<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\SolutionListResource;
use App\Http\Resources\SolutionResource;
use App\Models\Solution;
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

        return SolutionListResource::collection($solutions);
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
