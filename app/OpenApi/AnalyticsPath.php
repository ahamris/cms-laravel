<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

/**
 * Analytics API paths. Controllers live in Api\ (not Api\Frontend), so we document here
 * to keep a single annotation scan and avoid "Unable to merge" when generating Swagger.
 */
#[OA\PathItem(
    path: '/api/analytics/guest-activity',
    post: new OA\Post(
        summary: 'Guest activity ping',
        description: 'Record guest activity for web stats. Best-effort: returns 200 with status "tracked", "skipped", or "error". No auth. Throttled. Call from frontend (e.g. React) on page load or periodically.',
        tags: ['Analytics'],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'timestamp', type: 'string', format: 'date-time', example: '2026-02-26T12:00:00.000Z', description: 'Optional client timestamp'),
                    new OA\Property(property: 'timezone', type: 'string', example: 'Europe/Amsterdam', description: 'Optional client timezone'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Always 200',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', enum: ['tracked', 'skipped', 'error']),
                    ]
                )
            ),
        ]
    )
)]
class AnalyticsPath
{
}
