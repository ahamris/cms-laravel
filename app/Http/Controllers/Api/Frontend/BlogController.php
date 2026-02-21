<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\BlogListResource;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class BlogController extends Controller
{
    #[OA\Get(path: '/api/blog-posts', summary: 'List latest blog posts', description: 'Returns the latest 3 active blog posts.', tags: ['Blog'], responses: [
        new OA\Response(response: 200, description: 'List of blog posts', content: new OA\JsonContent(properties: [
            new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/BlogListItem')),
        ])),
    ])]
    public function apiPosts()
    {
        $blogs = Blog::with(['blog_category', 'author'])
            ->where('is_active', true)
            ->latest()
            ->take(3)
            ->get();

        return BlogListResource::collection($blogs);
    }

    #[OA\Get(path: '/api/blog/{slug}', summary: 'Get a blog post by slug', tags: ['Blog'], parameters: [
        new OA\Parameter(name: 'slug', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
    ], responses: [
        new OA\Response(response: 200, description: 'Blog post', content: new OA\JsonContent(ref: '#/components/schemas/Blog')),
        new OA\Response(response: 404, description: 'Not found'),
    ])]
    public function apiShow(string $slug)
    {
        $blog = Blog::with(['blog_category', 'author'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();
        if (! $blog) {
            return response()->json(['message' => 'Blog post not found.'], 404);
        }

        return new BlogResource($blog);
    }

    #[OA\Get(path: '/api/artikelen/load-more', summary: 'Load more blog posts (JSON)', description: 'Paginated blog list. Query: page, per_page, search, category.', tags: ['Blog'], parameters: [
        new OA\Parameter(name: 'page', in: 'query', schema: new OA\Schema(type: 'integer', default: 2)),
        new OA\Parameter(name: 'per_page', in: 'query', schema: new OA\Schema(type: 'integer', default: 6)),
        new OA\Parameter(name: 'search', in: 'query', schema: new OA\Schema(type: 'string')),
        new OA\Parameter(name: 'category', in: 'query', schema: new OA\Schema(type: 'string')),
    ], responses: [
        new OA\Response(response: 200, description: 'Blog list with has_more, next_page'),
    ])]
    public function loadMore(Request $request): JsonResponse
    {
        $perPage = max(1, min((int) $request->input('per_page', 6), 24));
        $page = max(1, (int) $request->input('page', 2));

        $featuredArticle = Blog::with(['blog_category', 'author'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->first();
        if (! $featuredArticle) {
            $featuredArticle = Blog::with(['blog_category', 'author'])
                ->where('is_active', true)
                ->latest()
                ->first();
        }

        $articlesQuery = Blog::with(['blog_category', 'author'])->where('is_active', true);
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $articlesQuery->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('meta_keywords', 'like', "%{$searchTerm}%")
                    ->orWhere('short_body', 'like', "%{$searchTerm}%")
                    ->orWhere('long_body', 'like', "%{$searchTerm}%");
            });
        }
        if ($request->filled('category')) {
            $articlesQuery->whereHas('blog_category', fn ($q) => $q->where('slug', $request->category));
        }
        if ($featuredArticle && ! $request->filled('search') && ! $request->filled('category')) {
            $articlesQuery->where('id', '!=', $featuredArticle->id);
        }

        $hasFeaturedMode = ! $request->filled('search') && ! $request->filled('category') && $featuredArticle;
        $offset = $hasFeaturedMode && $page >= 2 ? 5 + ($page - 2) * $perPage : ($page - 1) * $perPage;

        $total = (clone $articlesQuery)->count();
        $items = $articlesQuery->latest()->skip($offset)->take($perPage)->get();
        $hasMore = $total > $offset + $items->count();

        return response()->json([
            'data' => BlogListResource::collection($items),
            'has_more' => $hasMore,
            'next_page' => $hasMore ? $page + 1 : null,
        ]);
    }
}
