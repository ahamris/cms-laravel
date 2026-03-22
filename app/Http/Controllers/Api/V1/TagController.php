<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;

class TagController extends Controller
{
    public function __construct()
    {
        // Public API - no auth required
    }

    public function index(): JsonResponse
    {
        $tags = Tag::withCount('articles')
            ->orderByDesc('articles_count')
            ->get();

        return response()->json([
            'data' => $tags->map(fn (Tag $tag) => [
                'id'             => $tag->id,
                'name'           => $tag->name,
                'slug'           => $tag->slug,
                'type'           => $tag->type,
                'articles_count' => $tag->articles_count,
            ]),
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();

        $articles = $tag->articles()
            ->where('is_active', true)
            ->latest()
            ->paginate(15);

        $pages = $tag->pages()
            ->where('is_active', true)
            ->get(['id', 'title', 'slug']);

        return response()->json([
            'data' => [
                'id'   => $tag->id,
                'name' => $tag->name,
                'slug' => $tag->slug,
                'type' => $tag->type,
            ],
            'articles' => [
                'data' => $articles->items(),
                'meta' => [
                    'current_page' => $articles->currentPage(),
                    'per_page'     => $articles->perPage(),
                    'total'        => $articles->total(),
                    'last_page'    => $articles->lastPage(),
                ],
            ],
            'pages' => $pages,
        ]);
    }
}
