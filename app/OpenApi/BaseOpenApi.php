<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\OpenApi(
    info: new OA\Info(
        title: 'Headless CMS – Frontend feeds',
        version: '1.0.0',
        description: 'Public content API for pages, blog posts, legal and static pages. No authentication required.'
    ),
    servers: [
        new OA\Server(url: '/', description: 'API server'),
    ]
)]
class BaseOpenApi
{
}
