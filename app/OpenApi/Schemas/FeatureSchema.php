<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'FeatureSolutionRef',
    title: 'Feature solution reference',
    description: 'Parent solution summary when feature is loaded with solution',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'title', type: 'string', example: 'Solution title'),
        new OA\Property(property: 'anchor', type: 'string', example: 'solution-anchor'),
    ]
)]
#[OA\Schema(
    schema: 'FeatureListItem',
    title: 'Feature list item',
    description: 'Feature summary for list and nested responses (e.g. under a solution). Contains nested modules.',
    properties: [
        new OA\Property(property: 'id', type: 'integer'),
        new OA\Property(property: 'title', type: 'string'),
        new OA\Property(property: 'anchor', type: 'string'),
        new OA\Property(property: 'description', type: 'string', nullable: true),
        new OA\Property(property: 'icon', type: 'string', format: 'uri', nullable: true),
        new OA\Property(property: 'url', type: 'string', format: 'uri'),
        new OA\Property(property: 'sort_order', type: 'integer'),
        new OA\Property(property: 'modules', type: 'array', items: new OA\Items(ref: '#/components/schemas/ModuleListItem')),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', nullable: true),
    ]
)]
#[OA\Schema(
    schema: 'Feature',
    title: 'Feature',
    description: 'Single feature; belongs to a solution and has many modules (solution → feature → module).',
    properties: [
        new OA\Property(property: 'id', type: 'integer'),
        new OA\Property(property: 'title', type: 'string'),
        new OA\Property(property: 'anchor', type: 'string'),
        new OA\Property(property: 'description', type: 'string', nullable: true),
        new OA\Property(property: 'icon', type: 'string', format: 'uri', nullable: true),
        new OA\Property(property: 'url', type: 'string', format: 'uri'),
        new OA\Property(property: 'sort_order', type: 'integer'),
        new OA\Property(property: 'modules', type: 'array', items: new OA\Items(ref: '#/components/schemas/ModuleListItem')),
        new OA\Property(property: 'solution', ref: '#/components/schemas/FeatureSolutionRef', nullable: true),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', nullable: true),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', nullable: true),
    ]
)]
class FeatureSchema
{
}
