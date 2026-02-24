<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ContactSubjectListItem',
    title: 'Contact subject option',
    description: 'Single subject option for contact form Onderwerp dropdown',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'title', type: 'string', example: 'General question'),
        new OA\Property(property: 'sort_order', type: 'integer', example: 0),
    ]
)]
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
        new OA\Property(property: 'subjects', type: 'array', items: new OA\Items(ref: '#/components/schemas/ContactSubjectListItem'), description: 'Subject options for Onderwerp dropdown'),
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
