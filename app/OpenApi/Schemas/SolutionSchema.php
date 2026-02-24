<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SolutionListItem',
    title: 'Solution list item',
    description: 'Solution summary for list responses. Contains nested features (each with modules).',
    properties: [
        new OA\Property(property: 'id', type: 'integer'),
        new OA\Property(property: 'title', type: 'string'),
        new OA\Property(property: 'anchor', type: 'string'),
        new OA\Property(property: 'nav_title', type: 'string', nullable: true),
        new OA\Property(property: 'subtitle', type: 'string', nullable: true),
        new OA\Property(property: 'short_body', type: 'string', nullable: true),
        new OA\Property(property: 'image', type: 'string', format: 'uri', nullable: true),
        new OA\Property(property: 'url', type: 'string', format: 'uri'),
        new OA\Property(property: 'sort_order', type: 'integer'),
        new OA\Property(property: 'features', type: 'array', items: new OA\Items(ref: '#/components/schemas/FeatureListItem'), description: 'Features belonging to this solution (each with modules)'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', nullable: true),
    ]
)]
#[OA\Schema(
    schema: 'Solution',
    title: 'Solution',
    description: 'Single solution with full content. Solutions have many features; each feature has many modules (solution → feature → module).',
    properties: [
        new OA\Property(property: 'id', type: 'integer'),
        new OA\Property(property: 'title', type: 'string'),
        new OA\Property(property: 'anchor', type: 'string'),
        new OA\Property(property: 'nav_title', type: 'string', nullable: true),
        new OA\Property(property: 'subtitle', type: 'string', nullable: true),
        new OA\Property(property: 'short_body', type: 'string', nullable: true),
        new OA\Property(property: 'long_body', type: 'string', nullable: true),
        new OA\Property(property: 'meta_title', type: 'string', nullable: true),
        new OA\Property(property: 'meta_description', type: 'string', nullable: true),
        new OA\Property(property: 'meta_keywords', type: 'string', nullable: true),
        new OA\Property(property: 'image', type: 'string', format: 'uri', nullable: true),
        new OA\Property(property: 'list_items', type: 'array', items: new OA\Items(type: 'string'), nullable: true),
        new OA\Property(property: 'link_text', type: 'string', nullable: true),
        new OA\Property(property: 'link_url', type: 'string', nullable: true),
        new OA\Property(property: 'testimonial_quote', type: 'string', nullable: true),
        new OA\Property(property: 'testimonial_author', type: 'string', nullable: true),
        new OA\Property(property: 'testimonial_company', type: 'string', nullable: true),
        new OA\Property(property: 'image_position', type: 'string', nullable: true),
        new OA\Property(property: 'url', type: 'string', format: 'uri'),
        new OA\Property(property: 'template', type: 'string', description: 'Frontend template hint (e.g. solution-detail)'),
        new OA\Property(property: 'features', type: 'array', items: new OA\Items(ref: '#/components/schemas/FeatureListItem'), description: 'Features belonging to this solution (each with modules)'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', nullable: true),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', nullable: true),
    ]
)]
class SolutionSchema
{
}
