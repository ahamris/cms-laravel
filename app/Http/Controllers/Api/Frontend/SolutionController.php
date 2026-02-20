<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\SolutionListResource;
use App\Http\Resources\SolutionResource;
use App\Models\Solution;
use OpenApi\Attributes as OA;

class SolutionController extends Controller
{
    #[OA\Get(path: '/api/solutions', summary: 'List solutions', description: 'Active solutions with modules and features.', tags: ['Solutions'], responses: [
        new OA\Response(response: 200, description: 'Solutions collection'),
    ])]
    public function index()
    {
        $solutions = Solution::active()
            ->ordered()
            ->with(['modules' => function ($q) {
                $q->where('is_active', true)->ordered()->with([
                    'features' => fn ($q) => $q->where('is_active', true)->ordered(),
                ]);
            }])
            ->get();

        return SolutionListResource::collection($solutions);
    }

    #[OA\Get(path: '/api/solutions/{anchor}', summary: 'Solution by anchor', tags: ['Solutions'], parameters: [
        new OA\Parameter(name: 'anchor', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
    ], responses: [
        new OA\Response(response: 200, description: 'Solution'),
        new OA\Response(response: 404, description: 'Not found'),
    ])]
    public function show(string $anchor)
    {
        $solution = Solution::where('anchor', $anchor)->where('is_active', true)->firstOrFail();

        $solution->load([
            'modules' => function ($q) {
                $q->where('is_active', true)->ordered()->with([
                    'features' => fn ($q) => $q->where('is_active', true)->ordered(),
                ]);
            },
        ]);

        return new SolutionResource($solution);
    }
}
