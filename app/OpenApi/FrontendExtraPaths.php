<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\PathItem(
    path: '/api/pages/tree',
    get: new OA\Get(
        operationId: 'pages_tree_get',
        summary: 'Pages tree',
        description: 'Returns the active pages root-to-leaf tree (optimized for the SPA).',
        tags: ['Pages'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Page tree',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                type: 'object',
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer', nullable: true),
                                    new OA\Property(property: 'title', type: 'string', nullable: true),
                                    new OA\Property(property: 'slug', type: 'string', nullable: true),
                                    new OA\Property(property: 'parent_id', type: 'integer', nullable: true),
                                    new OA\Property(property: 'sort_order', type: 'integer', nullable: true),
                                    new OA\Property(property: 'template', type: 'string', nullable: true),
                                    new OA\Property(
                                        property: 'elements',
                                        type: 'array',
                                        items: new OA\Items(type: 'object')
                                    ),
                                    // Recursive structure is flattened in the spec.
                                    new OA\Property(
                                        property: 'children',
                                        type: 'array',
                                        items: new OA\Items(type: 'object')
                                    ),
                                ]
                            )
                        ),
                    ]
                )
            ),
            new OA\Response(response: 500, description: 'Server error'),
        ]
    )
)]
#[OA\PathItem(
    path: '/api/pages/{slug}/blocks',
    get: new OA\Get(
        operationId: 'pages_blocks_get',
        summary: 'Page blocks',
        description: 'Returns visible blocks for a given page slug.',
        tags: ['Pages'],
        parameters: [
            new OA\Parameter(
                name: 'slug',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string'),
                description: 'Page slug'
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Blocks',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                type: 'object',
                                properties: [
                                    new OA\Property(property: 'type', type: 'string', nullable: true),
                                    new OA\Property(property: 'content', type: 'object', nullable: true),
                                    new OA\Property(property: 'settings', type: 'object', nullable: true),
                                ]
                            )
                        ),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Page not found'),
        ]
    )
)]
#[OA\PathItem(
    path: '/api/sitemap.xml',
    get: new OA\Get(
        operationId: 'sitemap_xml_get',
        summary: 'XML sitemap',
        description: 'Sitemap in XML format (application/xml).',
        tags: ['Sitemap'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'XML sitemap',
                content: new OA\MediaType(
                    mediaType: 'application/xml',
                    schema: new OA\Schema(type: 'string')
                )
            ),
        ]
    )
)]
#[OA\PathItem(
    path: '/api/media',
    get: new OA\Get(
        operationId: 'media_index_get',
        summary: 'Media (placeholder)',
        description: 'Returns an empty media list for now (for future media integration).',
        tags: ['Media'],
        parameters: [
            new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer'), description: 'Items per page'),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Empty media list',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(type: 'object')
                        ),
                        new OA\Property(
                            property: 'meta',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'current_page', type: 'integer', nullable: true),
                                new OA\Property(property: 'last_page', type: 'integer', nullable: true),
                                new OA\Property(property: 'per_page', type: 'integer', nullable: true),
                                new OA\Property(property: 'total', type: 'integer', nullable: true),
                                new OA\Property(property: 'from', type: 'integer', nullable: true),
                                new OA\Property(property: 'to', type: 'integer', nullable: true),
                            ]
                        ),
                    ]
                )
            ),
        ]
    )
)]
#[OA\PathItem(
    path: '/api/newsletter/subscribe',
    post: new OA\Post(
        operationId: 'newsletter_subscribe_post',
        summary: 'Newsletter subscribe',
        description: 'Subscribe an email to the newsletter.',
        tags: ['Newsletter'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'email',
                        type: 'string',
                        format: 'email',
                        description: 'Email address',
                        maxLength: 255
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Subscription created',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean'),
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer'),
                                new OA\Property(property: 'email', type: 'string', format: 'email'),
                                new OA\Property(property: 'is_active', type: 'boolean'),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(response: 422, description: 'Validation error (e.g. invalid or duplicate email)'),
        ]
    )
)]
class FrontendExtraPaths {}
