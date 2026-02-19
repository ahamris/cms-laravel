<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\OpenApi(
    info: new OA\Info(
        title: 'Headless CMS – Frontend API',
        version: '1.0.0',
        description: 'Content API for the React SPA: pages, blog, legal, static, docs, live sessions, modules, features, solutions, vacancies, settings, sitemap. No authentication required.'
    ),
    servers: [
        new OA\Server(url: '/', description: 'API server'),
    ]
)]
class BaseOpenApi
{
}
