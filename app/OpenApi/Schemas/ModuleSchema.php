<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ModuleFeatureRef',
    title: 'Module feature reference',
    description: 'Parent feature summary when module is loaded with feature',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'title', type: 'string', example: 'Feature title'),
        new OA\Property(property: 'anchor', type: 'string', example: 'feature-anchor'),
    ]
)]
#[OA\Schema(
    schema: 'ModuleListItem',
    title: 'Module list item',
    description: 'Module summary for list and nested responses (e.g. under a feature)',
    properties: [
        new OA\Property(property: 'id', type: 'integer'),
        new OA\Property(property: 'title', type: 'string'),
        new OA\Property(property: 'slug', type: 'string'),
        new OA\Property(property: 'short_body', type: 'string', nullable: true),
        new OA\Property(property: 'image', type: 'string', format: 'uri', nullable: true),
        new OA\Property(property: 'url', type: 'string', format: 'uri'),
        new OA\Property(property: 'sort_order', type: 'integer'),
        new OA\Property(property: 'feature', ref: '#/components/schemas/ModuleFeatureRef', nullable: true, description: 'Parent feature when loaded'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', nullable: true),
    ]
)]
#[OA\Schema(
    schema: 'Module',
    title: 'Module',
    description: 'Single module with full content; belongs to a feature (solution → feature → module).',
    properties: [
        new OA\Property(property: 'id', type: 'integer'),
        new OA\Property(property: 'title', type: 'string'),
        new OA\Property(property: 'slug', type: 'string'),
        new OA\Property(property: 'short_body', type: 'string', nullable: true),
        new OA\Property(property: 'long_body', type: 'string', nullable: true),
        new OA\Property(property: 'meta_title', type: 'string', nullable: true),
        new OA\Property(property: 'meta_description', type: 'string', nullable: true),
        new OA\Property(property: 'meta_keywords', type: 'string', nullable: true),
        new OA\Property(property: 'image', type: 'string', format: 'uri', nullable: true),
        new OA\Property(property: 'anchor', type: 'string', nullable: true),
        new OA\Property(property: 'nav_title', type: 'string', nullable: true),
        new OA\Property(property: 'subtitle', type: 'string', nullable: true),
        new OA\Property(property: 'list_items', type: 'array', items: new OA\Items(type: 'string'), nullable: true),
        new OA\Property(property: 'link_text', type: 'string', nullable: true),
        new OA\Property(property: 'link_url', type: 'string', nullable: true),
        new OA\Property(property: 'testimonial_quote', type: 'string', nullable: true),
        new OA\Property(property: 'testimonial_author', type: 'string', nullable: true),
        new OA\Property(property: 'testimonial_company', type: 'string', nullable: true),
        new OA\Property(property: 'image_position', type: 'string', nullable: true),
        new OA\Property(property: 'url', type: 'string', format: 'uri'),
        new OA\Property(property: 'template', type: 'string', description: 'Frontend template hint (e.g. module-detail)'),
        new OA\Property(property: 'feature', ref: '#/components/schemas/ModuleFeatureRef', nullable: true, description: 'Parent feature'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', nullable: true),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', nullable: true),
    ]
)]
class ModuleSchema
{
}
