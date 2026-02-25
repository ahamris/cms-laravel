<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\PartnerTechItemResource;
use App\Models\PartnerTechItem;
use OpenApi\Attributes as OA;

class TechStackController extends Controller
{
    #[OA\Get(
        path: '/api/tech-stack',
        summary: 'Tech stack section',
        description: 'Returns the single tech stack record (type=1). data is an array of link items, each with link, link_type, image, sort_order, url (resolved), image_url.',
        tags: ['Tech Stack'],
        responses: [
            new OA\Response(response: 200, description: 'Tech stack record', content: new OA\JsonContent(ref: '#/components/schemas/PartnerTechItem')),
            new OA\Response(response: 404, description: 'Tech stack not configured'),
        ]
    )]
    public function index()
    {
        $record = PartnerTechItem::techStack()->active()->first();
        if (! $record) {
            return response()->json(['message' => 'Tech stack not configured.'], 404);
        }

        return (new PartnerTechItemResource($record))->additional([
            'template' => 'tech-stack-list',
        ]);
    }
}
