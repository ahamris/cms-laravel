<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\LegalResource;
use App\Models\Legal;
use OpenApi\Attributes as OA;

class LegalController extends Controller
{
    #[OA\Get(
        path: '/api/legal/{slug}',
        summary: 'Get a legal page by slug',
        description: 'Returns a single active legal document (e.g. privacy, terms) by slug.',
        tags: ['Legal'],
        parameters: [
            new OA\Parameter(name: 'slug', in: 'path', required: true, schema: new OA\Schema(type: 'string'), description: 'Legal page slug'),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Legal page', content: new OA\JsonContent(ref: '#/components/schemas/Legal')),
            new OA\Response(response: 404, description: 'Legal page not found'),
        ]
    )]
    public function show(string $slug)
    {
        $legal = Legal::where('slug', $slug)->where('is_active', true)->first();
        if (! $legal) {
            return response()->json(['message' => 'Legal page not found.'], 404);
        }

        return new LegalResource($legal);
    }
}
