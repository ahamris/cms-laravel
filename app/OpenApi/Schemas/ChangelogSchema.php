<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ChangelogEntry',
    title: 'Changelog entry',
    properties: [
        new OA\Property(property: 'id', type: 'integer'),
        new OA\Property(property: 'title', type: 'string'),
        new OA\Property(property: 'slug', type: 'string'),
        new OA\Property(property: 'description', type: 'string', nullable: true),
        new OA\Property(property: 'content', type: 'string', nullable: true),
        new OA\Property(property: 'date', type: 'string', format: 'date', nullable: true),
        new OA\Property(property: 'status', type: 'string'),
        new OA\Property(property: 'features', type: 'array', items: new OA\Items(type: 'string'), nullable: true),
        new OA\Property(property: 'steps', type: 'array', items: new OA\Items(type: 'string'), nullable: true),
        new OA\Property(property: 'is_active', type: 'boolean'),
        new OA\Property(property: 'sort_order', type: 'integer'),
    ]
)]
#[OA\Schema(
    schema: 'ChangelogListResponse',
    title: 'Changelog list response',
    properties: [
        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/ChangelogEntry')),
        new OA\Property(property: 'meta', type: 'object', properties: [
            new OA\Property(property: 'current_page', type: 'integer'),
            new OA\Property(property: 'last_page', type: 'integer'),
            new OA\Property(property: 'per_page', type: 'integer'),
            new OA\Property(property: 'total', type: 'integer'),
        ]),
    ]
)]
class ChangelogSchema
{
}
