<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\PageListResource;
use App\Http\Resources\PageResource;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * List active pages (for React SPA).
     */
    public function index(Request $request)
    {
        $perPage = max(1, min((int) $request->input('per_page', 12), 100));
        $pages = Page::where('is_active', true)
            ->orderBy('title')
            ->paginate($perPage);

        return PageListResource::collection($pages);
    }

    /**
     * Get a single active page by slug.
     */
    public function show(string $slug)
    {
        $page = Page::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $page->load(['marketingPersona', 'contentType']);

        return new PageResource($page);
    }
}
