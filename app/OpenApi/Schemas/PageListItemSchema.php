<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PageListItem',
    title: 'Page list item',
    description: 'Page summary (no long_body) for list views',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'title', type: 'string', example: 'About us'),
        new OA\Property(property: 'slug', type: 'string', example: 'about-us'),
        new OA\Property(property: 'short_body', type: 'string', nullable: true),
        new OA\Property(property: 'meta_title', type: 'string', nullable: true),
        new OA\Property(property: 'meta_body', type: 'string', nullable: true),
        new OA\Property(property: 'meta_keywords', type: 'string', nullable: true),
        new OA\Property(property: 'image', type: 'string', format: 'uri', nullable: true),
        new OA\Property(property: 'icon', type: 'string', nullable: true),
        new OA\Property(property: 'url', type: 'string', format: 'uri'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', nullable: true),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', nullable: true),
    ]
)]
class PageListItemSchema
{
}
