<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'BlogCategory',
    title: 'Blog category',
    properties: [
        new OA\Property(property: 'id', type: 'integer'),
        new OA\Property(property: 'name', type: 'string'),
        new OA\Property(property: 'slug', type: 'string'),
    ]
)]
#[OA\Schema(
    schema: 'BlogType',
    title: 'Blog type',
    description: 'Optional type for the post (e.g. Article, News)',
    properties: [
        new OA\Property(property: 'id', type: 'integer'),
        new OA\Property(property: 'name', type: 'string'),
        new OA\Property(property: 'description', type: 'string', nullable: true, description: 'Optional description of the blog type'),
    ]
)]
#[OA\Schema(
    schema: 'BlogAuthor',
    title: 'Blog author',
    properties: [
        new OA\Property(property: 'id', type: 'integer'),
        new OA\Property(property: 'name', type: 'string'),
        new OA\Property(property: 'avatar', type: 'string', format: 'uri'),
    ]
)]
#[OA\Schema(
    schema: 'Blog',
    title: 'Blog post',
    description: 'Single blog post with full content',
    properties: [
        new OA\Property(property: 'id', type: 'integer'),
        new OA\Property(property: 'title', type: 'string'),
        new OA\Property(property: 'slug', type: 'string'),
        new OA\Property(property: 'short_body', type: 'string', nullable: true),
        new OA\Property(property: 'long_body', type: 'string', nullable: true),
        new OA\Property(property: 'image', type: 'string', format: 'uri'),
        new OA\Property(property: 'meta_title', type: 'string', nullable: true),
        new OA\Property(property: 'meta_description', type: 'string', nullable: true),
        new OA\Property(property: 'url', type: 'string', format: 'uri'),
        new OA\Property(property: 'template', type: 'string', description: 'Frontend template hint (e.g. blog-detail)'),
        new OA\Property(property: 'date', type: 'string', example: 'Jan 1, 2025'),
        new OA\Property(property: 'date_attr', type: 'string', format: 'date'),
        new OA\Property(property: 'published_at', type: 'string', format: 'date-time', nullable: true),
        new OA\Property(property: 'category', ref: '#/components/schemas/BlogCategory', nullable: true),
        new OA\Property(property: 'blog_type', ref: '#/components/schemas/BlogType', nullable: true),
        new OA\Property(property: 'author', ref: '#/components/schemas/BlogAuthor', nullable: true),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', nullable: true),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', nullable: true),
    ]
)]
class BlogSchema
{
}
