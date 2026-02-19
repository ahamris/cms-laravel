<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;
use Illuminate\Http\JsonResponse;

class StaticPageController extends Controller
{
    /**
     * Get a single active static page by slug.
     */
    public function show(string $slug): JsonResponse
    {
        $staticPage = StaticPage::where('slug', $slug)->where('is_active', true)->firstOrFail();

        return response()->json([
            'data' => [
                'id' => $staticPage->id,
                'title' => $staticPage->title,
                'slug' => $staticPage->slug,
                'body' => $staticPage->body,
                'meta_title' => $staticPage->meta_title,
                'meta_description' => $staticPage->meta_description,
                'keywords' => $staticPage->keywords,
                'image' => $staticPage->image ? asset($staticPage->image) : null,
                'url' => route('static.show', $staticPage->slug),
                'created_at' => $staticPage->created_at?->toIso8601String(),
                'updated_at' => $staticPage->updated_at?->toIso8601String(),
            ],
        ]);
    }
}
