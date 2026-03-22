<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ArticleCategory;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function __construct()
    {
        // Public API - no auth required
    }

    public function index(): JsonResponse
    {
        $categories = ArticleCategory::active()
            ->roots()
            ->ordered()
            ->withCount('articles')
            ->with(['children' => function ($q) {
                $q->active()->ordered()->withCount('articles');
            }])
            ->get();

        return response()->json([
            'data' => $categories->map(fn ($cat) => $this->formatCategory($cat)),
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $category = ArticleCategory::where('slug', $slug)
            ->active()
            ->withCount('articles')
            ->firstOrFail();

        $articles = $category->articles()
            ->where('is_active', true)
            ->latest()
            ->paginate(15);

        return response()->json([
            'data' => $this->formatCategory($category),
            'articles' => [
                'data' => $articles->items(),
                'meta' => [
                    'current_page' => $articles->currentPage(),
                    'per_page'     => $articles->perPage(),
                    'total'        => $articles->total(),
                    'last_page'    => $articles->lastPage(),
                ],
            ],
        ]);
    }

    private function formatCategory(ArticleCategory $cat): array
    {
        $data = [
            'id'             => $cat->id,
            'name'           => $cat->name,
            'slug'           => $cat->slug,
            'description'    => $cat->description,
            'color'          => $cat->color,
            'icon'           => $cat->icon,
            'articles_count' => $cat->articles_count ?? 0,
        ];

        if ($cat->relationLoaded('children')) {
            $data['children'] = $cat->children->map(fn ($child) => $this->formatCategory($child));
        }

        return $data;
    }
}
