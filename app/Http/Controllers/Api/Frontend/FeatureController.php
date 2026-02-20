<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\FeatureListResource;
use App\Http\Resources\FeatureResource;
use App\Models\Feature;
use OpenApi\Attributes as OA;

class FeatureController extends Controller
{
    #[OA\Get(path: '/api/features', summary: 'List features', description: 'Active features with anchor for URL.', tags: ['Features'], responses: [
        new OA\Response(response: 200, description: 'Features collection'),
    ])]
    public function index()
    {
        $features = Feature::with('modules')->active()->ordered()->get();

        return FeatureListResource::collection($features);
    }

    #[OA\Get(path: '/api/features/{anchor}', summary: 'Feature by anchor', tags: ['Features'], parameters: [
        new OA\Parameter(name: 'anchor', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
    ], responses: [
        new OA\Response(response: 200, description: 'Feature'),
        new OA\Response(response: 404, description: 'Not found'),
    ])]
    public function show(string $anchor)
    {
        $feature = Feature::with('modules')
            ->active()
            ->where('anchor', $anchor)
            ->firstOrFail();

        return new FeatureResource($feature);
    }
}
