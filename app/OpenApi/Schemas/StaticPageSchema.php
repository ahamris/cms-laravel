<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StaticPage',
    title: 'Static page',
    description: 'Single static content page',
    properties: [
        new OA\Property(property: 'id', type: 'integer'),
        new OA\Property(property: 'title', type: 'string'),
        new OA\Property(property: 'slug', type: 'string'),
        new OA\Property(property: 'body', type: 'string'),
        new OA\Property(property: 'meta_title', type: 'string', nullable: true),
        new OA\Property(property: 'meta_description', type: 'string', nullable: true),
        new OA\Property(property: 'keywords', type: 'string', nullable: true),
        new OA\Property(property: 'image', type: 'string', format: 'uri', nullable: true),
        new OA\Property(property: 'url', type: 'string', format: 'uri'),
        new OA\Property(property: 'template', type: 'string', description: 'Frontend template hint (e.g. static-page)'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', nullable: true),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', nullable: true),
    ]
)]
class StaticPageSchema
{
}
