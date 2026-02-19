<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\StaticPageResource;
use App\Models\StaticPage;
use OpenApi\Attributes as OA;

class StaticPageController extends Controller
{
    #[OA\Get(
        path: '/api/static/{slug}',
        summary: 'Get a static page by slug',
        description: 'Returns a single active static content page by slug.',
        tags: ['Static pages'],
        parameters: [
            new OA\Parameter(name: 'slug', in: 'path', required: true, schema: new OA\Schema(type: 'string'), description: 'Static page slug'),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Static page', content: new OA\JsonContent(ref: '#/components/schemas/StaticPage')),
            new OA\Response(response: 404, description: 'Static page not found'),
        ]
    )]
    public function show(string $slug)
    {
        $staticPage = StaticPage::where('slug', $slug)->where('is_active', true)->firstOrFail();

        return new StaticPageResource($staticPage);
    }
}
