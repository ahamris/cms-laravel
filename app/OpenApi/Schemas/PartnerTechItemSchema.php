<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PartnerTechItem',
    title: 'Partners / Tech stack record',
    description: 'Single record per type (partner or tech_stack). data is an array of link items, each with link, link_type, image, sort_order, url (resolved), image_url.',
    properties: [
        new OA\Property(property: 'id', type: 'integer'),
        new OA\Property(property: 'name', type: 'string'),
        new OA\Property(property: 'banner', type: 'string', format: 'uri', nullable: true),
        new OA\Property(property: 'title', type: 'string', nullable: true),
        new OA\Property(property: 'description', type: 'string', nullable: true),
        new OA\Property(property: 'type', type: 'integer', description: '0=partner, 1=tech_stack'),
        new OA\Property(property: 'type_label', type: 'string', enum: ['partner', 'tech_stack']),
        new OA\Property(property: 'data', type: 'array', description: 'Multipliable link items', items: new OA\Items(properties: [
            new OA\Property(property: 'link', type: 'string', nullable: true),
            new OA\Property(property: 'link_type', type: 'string', enum: ['external', 'static']),
            new OA\Property(property: 'image', type: 'string', nullable: true),
            new OA\Property(property: 'sort_order', type: 'integer'),
            new OA\Property(property: 'url', type: 'string', format: 'uri', nullable: true, description: 'Resolved link URL'),
            new OA\Property(property: 'image_url', type: 'string', format: 'uri', nullable: true),
        ])),
        new OA\Property(property: 'sort_order', type: 'integer'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', nullable: true),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', nullable: true),
    ]
)]
class PartnerTechItemSchema
{
}
