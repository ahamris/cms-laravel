<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'BlogListItem',
    title: 'Blog list item',
    description: 'Blog post summary for list views',
    properties: [
        new OA\Property(property: 'title', type: 'string'),
        new OA\Property(property: 'slug', type: 'string'),
        new OA\Property(property: 'url', type: 'string', format: 'uri'),
        new OA\Property(property: 'image', type: 'string', format: 'uri'),
        new OA\Property(property: 'short_body', type: 'string'),
        new OA\Property(property: 'date', type: 'string', example: 'Jan 1, 2025'),
        new OA\Property(property: 'date_attr', type: 'string', format: 'date', example: '2025-01-01'),
        new OA\Property(property: 'category', type: 'string'),
        new OA\Property(property: 'category_slug', type: 'string', nullable: true),
        new OA\Property(property: 'blog_type', ref: '#/components/schemas/BlogType', nullable: true),
        new OA\Property(property: 'author_name', type: 'string'),
        new OA\Property(property: 'author_avatar', type: 'string', format: 'uri'),
    ]
)]
class BlogListItemSchema
{
}
