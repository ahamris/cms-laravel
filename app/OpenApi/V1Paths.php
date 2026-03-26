<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\PathItem(
    path: '/api/v1/categories',
    get: new OA\Get(
        operationId: 'v1_categories_index_get',
        summary: 'V1 categories (tree)',
        description: 'Returns a public category tree for articles.',
        tags: ['V1'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Category tree',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                type: 'object',
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer'),
                                    new OA\Property(property: 'name', type: 'string'),
                                    new OA\Property(property: 'slug', type: 'string'),
                                    new OA\Property(property: 'description', type: 'string', nullable: true),
                                    new OA\Property(property: 'color', type: 'string', nullable: true),
                                    new OA\Property(property: 'icon', type: 'string', nullable: true),
                                    new OA\Property(property: 'articles_count', type: 'integer'),
                                    new OA\Property(property: 'children', type: 'array', nullable: true, items: new OA\Items(type: 'object')),
                                ]
                            )
                        ),
                    ]
                )
            ),
        ]
    )
)]
#[OA\PathItem(
    path: '/api/v1/categories/{slug}',
    get: new OA\Get(
        operationId: 'v1_categories_show_get',
        summary: 'V1 category details + articles',
        description: 'Returns a single category and its active articles (paginated).',
        tags: ['V1'],
        parameters: [
            new OA\Parameter(name: 'slug', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1), description: 'Pagination page'),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Category with articles',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer'),
                                new OA\Property(property: 'name', type: 'string'),
                                new OA\Property(property: 'slug', type: 'string'),
                                new OA\Property(property: 'description', type: 'string', nullable: true),
                                new OA\Property(property: 'color', type: 'string', nullable: true),
                                new OA\Property(property: 'icon', type: 'string', nullable: true),
                                new OA\Property(property: 'articles_count', type: 'integer'),
                            ]
                        ),
                        new OA\Property(
                            property: 'articles',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
                                new OA\Property(
                                    property: 'meta',
                                    type: 'object',
                                    properties: [
                                        new OA\Property(property: 'current_page', type: 'integer'),
                                        new OA\Property(property: 'per_page', type: 'integer'),
                                        new OA\Property(property: 'total', type: 'integer'),
                                        new OA\Property(property: 'last_page', type: 'integer'),
                                    ]
                                ),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )
)]
#[OA\PathItem(
    path: '/api/v1/tags',
    get: new OA\Get(
        operationId: 'v1_tags_index_get',
        summary: 'V1 tags',
        description: 'Returns a list of tags with article counts.',
        tags: ['V1'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Tag list',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                type: 'object',
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer'),
                                    new OA\Property(property: 'name', type: 'string'),
                                    new OA\Property(property: 'slug', type: 'string'),
                                    new OA\Property(property: 'type', type: 'integer'),
                                    new OA\Property(property: 'articles_count', type: 'integer'),
                                ]
                            )
                        ),
                    ]
                )
            ),
        ]
    )
)]
#[OA\PathItem(
    path: '/api/v1/tags/{slug}',
    get: new OA\Get(
        operationId: 'v1_tags_show_get',
        summary: 'V1 tag details + articles + pages',
        description: 'Returns a tag and its active articles (paginated) and related pages.',
        tags: ['V1'],
        parameters: [
            new OA\Parameter(name: 'slug', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1), description: 'Pagination page'),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Tag details',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer'),
                                new OA\Property(property: 'name', type: 'string'),
                                new OA\Property(property: 'slug', type: 'string'),
                                new OA\Property(property: 'type', type: 'integer'),
                            ]
                        ),
                        new OA\Property(
                            property: 'articles',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
                                new OA\Property(
                                    property: 'meta',
                                    type: 'object',
                                    properties: [
                                        new OA\Property(property: 'current_page', type: 'integer'),
                                        new OA\Property(property: 'per_page', type: 'integer'),
                                        new OA\Property(property: 'total', type: 'integer'),
                                        new OA\Property(property: 'last_page', type: 'integer'),
                                    ]
                                ),
                            ]
                        ),
                        new OA\Property(
                            property: 'pages',
                            type: 'array',
                            items: new OA\Items(
                                type: 'object',
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer'),
                                    new OA\Property(property: 'title', type: 'string'),
                                    new OA\Property(property: 'slug', type: 'string'),
                                ]
                            )
                        ),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )
)]
#[OA\PathItem(
    path: '/api/v1/media',
    get: new OA\Get(
        operationId: 'v1_media_index_get',
        summary: 'V1 media list',
        description: 'Returns media files (paginated). Optional filters: folder, mime_type, search.',
        tags: ['V1'],
        parameters: [
            new OA\Parameter(name: 'folder', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'mime_type', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'search', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 100)),
            new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1), description: 'Pagination page'),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Media list',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                type: 'object',
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer'),
                                    new OA\Property(property: 'filename', type: 'string'),
                                    new OA\Property(property: 'original_filename', type: 'string', nullable: true),
                                    new OA\Property(property: 'url', type: 'string', format: 'uri', nullable: true),
                                    new OA\Property(property: 'mime_type', type: 'string', nullable: true),
                                    new OA\Property(property: 'size', type: 'integer', nullable: true),
                                    new OA\Property(property: 'width', type: 'integer', nullable: true),
                                    new OA\Property(property: 'height', type: 'integer', nullable: true),
                                    new OA\Property(property: 'alt_text', type: 'string', nullable: true),
                                    new OA\Property(property: 'title', type: 'string', nullable: true),
                                    new OA\Property(property: 'folder', type: 'string', nullable: true),
                                ]
                            )
                        ),
                        new OA\Property(
                            property: 'meta',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'current_page', type: 'integer'),
                                new OA\Property(property: 'per_page', type: 'integer'),
                                new OA\Property(property: 'total', type: 'integer'),
                                new OA\Property(property: 'last_page', type: 'integer'),
                            ]
                        ),
                    ]
                )
            ),
        ]
    )
)]
#[OA\PathItem(
    path: '/api/v1/media/{id}',
    get: new OA\Get(
        operationId: 'v1_media_show_get',
        summary: 'V1 media details',
        description: 'Returns a single media record by id.',
        tags: ['V1'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Media details',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer'),
                                new OA\Property(property: 'filename', type: 'string'),
                                new OA\Property(property: 'original_filename', type: 'string', nullable: true),
                                new OA\Property(property: 'url', type: 'string', format: 'uri', nullable: true),
                                new OA\Property(property: 'mime_type', type: 'string', nullable: true),
                                new OA\Property(property: 'size', type: 'integer', nullable: true),
                                new OA\Property(property: 'width', type: 'integer', nullable: true),
                                new OA\Property(property: 'height', type: 'integer', nullable: true),
                                new OA\Property(property: 'alt_text', type: 'string', nullable: true),
                                new OA\Property(property: 'title', type: 'string', nullable: true),
                                new OA\Property(property: 'folder', type: 'string', nullable: true),
                                new OA\Property(property: 'created_at', type: 'string', format: 'date-time', nullable: true),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )
)]
#[OA\PathItem(
    path: '/api/v1/forms/{slug}',
    get: new OA\Get(
        operationId: 'v1_forms_show_get',
        summary: 'V1 form schema',
        description: 'Returns an active form by slug, including fields configuration.',
        tags: ['V1'],
        parameters: [
            new OA\Parameter(name: 'slug', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Form',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer'),
                                new OA\Property(property: 'name', type: 'string'),
                                new OA\Property(property: 'slug', type: 'string'),
                                new OA\Property(property: 'description', type: 'string', nullable: true),
                                new OA\Property(property: 'type', type: 'string', nullable: true),
                                new OA\Property(property: 'success_message', type: 'string', nullable: true),
                                new OA\Property(property: 'redirect_url', type: 'string', format: 'uri', nullable: true),
                                new OA\Property(property: 'honeypot_field', type: 'string', nullable: true),
                                new OA\Property(property: 'styling', type: 'string', nullable: true),
                                new OA\Property(
                                    property: 'fields',
                                    type: 'array',
                                    items: new OA\Items(
                                        type: 'object',
                                        properties: [
                                            new OA\Property(property: 'name', type: 'string'),
                                            new OA\Property(property: 'label', type: 'string', nullable: true),
                                            new OA\Property(property: 'type', type: 'string', nullable: true),
                                            new OA\Property(property: 'placeholder', type: 'string', nullable: true),
                                            new OA\Property(property: 'help_text', type: 'string', nullable: true),
                                            new OA\Property(property: 'is_required', type: 'boolean', nullable: true),
                                            new OA\Property(property: 'options', type: 'object', nullable: true),
                                            new OA\Property(property: 'default_value', type: 'object', nullable: true),
                                            new OA\Property(property: 'width', type: 'integer', nullable: true),
                                            new OA\Property(property: 'conditional_on', type: 'object', nullable: true),
                                        ]
                                    )
                                ),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(response: 422, description: 'Form not accepting submissions'),
        ]
    )
)]
#[OA\PathItem(
    path: '/api/v1/forms/{slug}/submit',
    post: new OA\Post(
        operationId: 'v1_forms_submit_post',
        summary: 'V1 form submit',
        description: 'Submit a public form by slug. Validation rules depend on the configured form fields.',
        tags: ['V1'],
        parameters: [
            new OA\Parameter(name: 'slug', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                description: 'The payload includes a "fields" object. Keys inside "fields" are dynamic based on form configuration.',
                properties: [
                    new OA\Property(
                        property: 'fields',
                        type: 'object',
                        additionalProperties: true
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Submission created',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(property: 'redirect_url', type: 'string', format: 'uri', nullable: true),
                        new OA\Property(property: 'submission_id', type: 'integer', nullable: true),
                    ]
                )
            ),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )
)]
class V1Paths {}
