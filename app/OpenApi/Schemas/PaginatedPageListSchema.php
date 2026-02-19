<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PaginatedPageList',
    title: 'Paginated page list',
    description: 'Laravel paginated response for pages',
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/PageListItem')
        ),
        new OA\Property(
            property: 'links',
            type: 'object',
            properties: [
                new OA\Property(property: 'first', type: 'string', format: 'uri'),
                new OA\Property(property: 'last', type: 'string', format: 'uri'),
                new OA\Property(property: 'prev', type: 'string', format: 'uri', nullable: true),
                new OA\Property(property: 'next', type: 'string', format: 'uri', nullable: true),
            ]
        ),
        new OA\Property(
            property: 'meta',
            type: 'object',
            properties: [
                new OA\Property(property: 'current_page', type: 'integer'),
                new OA\Property(property: 'from', type: 'integer', nullable: true),
                new OA\Property(property: 'last_page', type: 'integer'),
                new OA\Property(property: 'path', type: 'string', format: 'uri'),
                new OA\Property(property: 'per_page', type: 'integer'),
                new OA\Property(property: 'to', type: 'integer', nullable: true),
                new OA\Property(property: 'total', type: 'integer'),
            ]
        ),
    ]
)]
class PaginatedPageListSchema
{
}
