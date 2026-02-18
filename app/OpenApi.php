<?php

namespace App;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: 'Headless CMS API',
    version: '1.0',
    description: 'API for the Headless CMS. Authenticate via session cookie (same-origin SPA) or Bearer token (cross-origin). Send `token: true` in the login body to receive a Bearer token.'
)]
#[OA\Server(
    url: '/api',
    description: 'API base path'
)]
#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'Sanctum token',
    description: 'Use the token returned from POST /api/v1/login with body `token: true`.'
)]
#[OA\SecurityScheme(
    securityScheme: 'sanctumCookie',
    type: 'apiKey',
    in: 'cookie',
    name: 'laravel_session',
    description: 'Same-origin SPA: use session cookies after POST /api/v1/login without `token`.'
)]
class OpenApi {}
