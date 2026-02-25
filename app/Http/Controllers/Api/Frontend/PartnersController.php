<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\PartnerTechItemResource;
use App\Models\PartnerTechItem;
use OpenApi\Attributes as OA;

class PartnersController extends Controller
{
    #[OA\Get(
        path: '/api/partners',
        summary: 'Partners section',
        description: 'Returns the single partners record (type=0). data is an array of link items. When not configured, returns 200 with empty data so the frontend can hide the section.',
        tags: ['Partners'],
        responses: [
            new OA\Response(response: 200, description: 'Partners record or empty when not configured', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'data', type: 'object', nullable: true),
                new OA\Property(property: 'template', type: 'string', example: 'partners-list'),
            ])),
        ]
    )]
    public function index()
    {
        $record = PartnerTechItem::partners()->active()->first();
        if (! $record) {
            return response()->json([
                'data' => null,
                'template' => 'partners-list',
                'message' => 'Partners not configured.',
            ]);
        }

        return (new PartnerTechItemResource($record))->additional([
            'template' => 'partners-list',
        ]);
    }
}
