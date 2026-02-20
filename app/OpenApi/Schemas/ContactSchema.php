<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ContactPageData',
    title: 'Contact page data',
    properties: [
        new OA\Property(property: 'id', type: 'integer', nullable: true),
        new OA\Property(property: 'title', type: 'string'),
        new OA\Property(property: 'short_body', type: 'string', nullable: true),
        new OA\Property(property: 'long_body', type: 'string', nullable: true),
        new OA\Property(property: 'image', type: 'string', nullable: true),
        new OA\Property(property: 'image_url', type: 'string', format: 'uri', nullable: true),
        new OA\Property(property: 'meta_title', type: 'string', nullable: true),
        new OA\Property(property: 'meta_body', type: 'string', nullable: true),
    ]
)]
#[OA\Schema(
    schema: 'ContactDemoSuccess',
    title: 'Contact demo success',
    properties: [
        new OA\Property(property: 'success', type: 'boolean', example: true),
        new OA\Property(property: 'message', type: 'string'),
        new OA\Property(property: 'data', type: 'object', properties: [
            new OA\Property(property: 'id', type: 'integer'),
            new OA\Property(property: 'full_name', type: 'string'),
        ]),
    ]
)]
#[OA\Schema(
    schema: 'ContactSubmitSuccess',
    title: 'Contact form submit success',
    properties: [
        new OA\Property(property: 'success', type: 'boolean', example: true),
        new OA\Property(property: 'message', type: 'string'),
        new OA\Property(property: 'data', type: 'object', properties: [
            new OA\Property(property: 'id', type: 'integer'),
            new OA\Property(property: 'full_name', type: 'string'),
        ]),
    ]
)]
class ContactSchema
{
}
