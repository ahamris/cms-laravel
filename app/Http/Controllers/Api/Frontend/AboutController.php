<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class AboutController extends Controller
{
    #[OA\Get(path: '/api/over-ons', summary: 'About page data', description: 'About us content for headless frontend.', tags: ['About'], responses: [
        new OA\Response(response: 200, description: 'About page data'),
        new OA\Response(response: 404, description: 'About page not found'),
    ])]
    public function __invoke(): JsonResponse
    {
        $about = About::where('is_active', true)->first();

        if (! $about) {
            return response()->json(['message' => 'About page not found.'], 404);
        }

        $data = [
            'id' => $about->id,
            'anchor' => $about->anchor,
            'nav_title' => $about->nav_title,
            'title' => $about->title,
            'subtitle' => $about->subtitle,
            'short_body' => $about->short_body,
            'long_body' => $about->long_body,
            'list_items' => $about->list_items,
            'link_text' => $about->link_text,
            'testimonial_quote' => $about->testimonial_quote,
            'testimonial_author' => $about->testimonial_author,
            'testimonial_company' => $about->testimonial_company,
            'image' => $about->image,
            'image_url' => $about->image ? get_image($about->image, null) : null,
            'image_position' => $about->image_position,
            'meta_title' => $about->meta_title,
            'meta_description' => $about->meta_description,
        ];

        return response()->json(['data' => $data]);
    }
}
