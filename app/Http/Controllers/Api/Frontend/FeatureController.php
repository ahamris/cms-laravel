<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\FeatureListResource;
use App\Http\Resources\FeatureResource;
use App\Models\Feature;
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

        return FeatureListResource::collection($features);
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
