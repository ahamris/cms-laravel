<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\PathItem(
    path: '/api/analytics/track',
    post: new OA\Post(
        operationId: 'analytics_track_post',
        summary: 'Track page view (AJAX)',
        description: 'Best-effort endpoint that records a page view. Returns 200 even when the server-side tracking fails.',
        tags: ['Analytics'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'url', type: 'string', maxLength: 500, description: 'Tracked URL'),
                    new OA\Property(property: 'page_title', type: 'string', nullable: true, maxLength: 255),
                    new OA\Property(property: 'referrer', type: 'string', nullable: true, maxLength: 500),
                    new OA\Property(property: 'user_agent', type: 'string', nullable: true, maxLength: 1000),
                    new OA\Property(property: 'metadata', type: 'object', nullable: true, description: 'Optional arbitrary metadata'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Always 200 (tracked/skipped/error)',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', enum: ['tracked', 'skipped', 'error']),
                    ]
                )
            ),
        ]
    )
)]
#[OA\PathItem(
    path: '/api/analytics/batch-track',
    post: new OA\Post(
        operationId: 'analytics_batch_track_post',
        summary: 'Batch track page views',
        description: 'Batch endpoint for SPA applications. Accepts up to 10 views per request. Best-effort; returns 200 with status "tracked" or "error".',
        tags: ['Analytics'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'views',
                        type: 'array',
                        maxItems: 10,
                        items: new OA\Items(
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'url', type: 'string', maxLength: 500),
                                new OA\Property(property: 'page_title', type: 'string', nullable: true, maxLength: 255),
                                new OA\Property(property: 'referrer', type: 'string', nullable: true, maxLength: 500),
                                new OA\Property(property: 'metadata', type: 'object', nullable: true),
                            ]
                        )
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Always 200',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', enum: ['tracked', 'error']),
                        new OA\Property(property: 'count', type: 'integer', nullable: true),
                    ]
                )
            ),
        ]
    )
)]
#[OA\PathItem(
    path: '/api/analytics/performance',
    post: new OA\Post(
        operationId: 'analytics_performance_post',
        summary: 'Track performance metrics',
        description: 'Best-effort endpoint that records basic performance metrics. Returns 200 even when the server-side tracking fails.',
        tags: ['Analytics'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'url', type: 'string', maxLength: 500),
                    new OA\Property(property: 'metrics', type: 'object', description: 'Performance metrics payload'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Always 200 (tracked/error)',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', enum: ['tracked', 'error']),
                    ]
                )
            ),
        ]
    )
)]
#[OA\PathItem(
    path: '/api/analytics/stats',
    get: new OA\Get(
        operationId: 'analytics_stats_get',
        summary: 'Analytics stats',
        description: 'Returns cached public analytics stats. On failure returns HTTP 500 with {"status":"error"}.',
        tags: ['Analytics'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Public stats',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'today_views', type: 'integer'),
                        new OA\Property(property: 'status', type: 'string', example: 'active'),
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: 'Server error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'error'),
                    ]
                )
            ),
        ]
    )
)]
class AnalyticsPaths {}
