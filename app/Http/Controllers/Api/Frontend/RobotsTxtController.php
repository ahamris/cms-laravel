<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\RobotsTxt;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

class RobotsTxtController extends Controller
{
    #[OA\Get(
        path: '/api/robots-txt',
        summary: 'Robots.txt content',
        description: 'Returns the robots.txt file content as plain text. For use by the React SPA (e.g. to serve at /robots.txt or display in admin). Content is managed in the admin Robots.txt editor and cached for 24 hours.',
        tags: ['Robots'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Robots.txt content (text/plain)',
                content: new OA\MediaType(
                    mediaType: 'text/plain',
                    schema: new OA\Schema(type: 'string', example: "User-agent: *\nAllow: /\n")
                )
            ),
        ]
    )]
    public function index(): Response
    {
        $content = RobotsTxt::getCachedContent();

        return response($content, 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }
}
