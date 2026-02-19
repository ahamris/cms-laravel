<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\SolutionListResource;
use App\Http\Resources\SolutionResource;
use App\Models\Solution;

class SolutionController extends Controller
{
    /**
     * List active solutions (with modules + features).
     */
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

    /**
     * Single solution by anchor.
     */
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
