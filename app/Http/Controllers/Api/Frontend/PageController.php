<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * List active pages (for React SPA).
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = max(1, min((int) $request->input('per_page', 12), 100));
        $pages = Page::where('is_active', true)
            ->orderBy('title')
            ->paginate($perPage);

        $items = $pages->getCollection()->map(fn (Page $page) => $this->pageToArray($page));
        $pages->setCollection($items);

        return response()->json([
            'data' => $pages->items(),
            'meta' => [
                'current_page' => $pages->currentPage(),
                'last_page' => $pages->lastPage(),
                'per_page' => $pages->perPage(),
                'total' => $pages->total(),
                'from' => $pages->firstItem(),
                'to' => $pages->lastItem(),
            ],
        ]);
    }

    /**
     * Get a single active page by slug.
     */
    public function show(string $slug): JsonResponse
    {
        $page = Page::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $page->load(['marketingPersona', 'contentType']);

        return response()->json([
            'data' => $this->pageToArray($page, true),
        ]);
    }

    private function pageToArray(Page $page, bool $includeLongBody = false): array
    {
        $base = [
            'id' => $page->id,
            'title' => $page->title,
            'slug' => $page->slug,
            'short_body' => $page->short_body,
            'meta_title' => $page->meta_title,
            'meta_body' => $page->meta_body,
            'meta_keywords' => $page->meta_keywords,
            'image' => $page->image ? asset($page->image) : null,
            'icon' => $page->icon,
            'url' => route('page.show', $page->slug),
            'created_at' => $page->created_at?->toIso8601String(),
            'updated_at' => $page->updated_at?->toIso8601String(),
        ];

        if ($includeLongBody) {
            $base['long_body'] = $page->long_body;
        }

        return $base;
    }
}
