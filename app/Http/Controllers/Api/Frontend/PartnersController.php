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
        description: 'Returns the single partners record (type=0). data is an array of link items, each with link, link_type, image, sort_order, url (resolved), image_url.',
        tags: ['Partners'],
        responses: [
            new OA\Response(response: 200, description: 'Partners record', content: new OA\JsonContent(ref: '#/components/schemas/PartnerTechItem')),
            new OA\Response(response: 404, description: 'Partners not configured'),
        ]
    )]
    public function index()
    {
        $record = PartnerTechItem::partners()->active()->first();
        if (! $record) {
            return response()->json(['message' => 'Partners not configured.'], 404);
        }

        return (new PartnerTechItemResource($record))->additional([
            'template' => 'partners-list',
        ]);
    }
}
