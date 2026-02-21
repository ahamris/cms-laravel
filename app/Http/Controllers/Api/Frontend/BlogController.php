<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\SeoSetTrait;
use App\Http\Resources\BlogListResource;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use OpenApi\Attributes as OA;

class BlogController extends Controller
{
    use SeoSetTrait;

    /**
     * Blog listing page (Blade, for web).
     */
    public function index(Request $request): View
    {
        $this->setSeoTags([
            'google_title' => 'Blog & Artikelen - '.get_setting('site_name'),
            'google_description' => 'Ontdek onze laatste artikelen, tips en nieuws over OpenPublicatie.',
            'google_image' => asset('images/blog-og-image.jpg'),
        ]);

        $perPage = 6;
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

        $hasFeaturedOnFirstPage = $featuredArticle && ! $request->filled('search') && ! $request->filled('category');
        $firstPageSize = $hasFeaturedOnFirstPage ? 5 : 6;
        $total = (clone $articlesQuery)->count();
        $articles = $articlesQuery->latest()->take($firstPageSize)->get();
        $hasMorePages = $total > $firstPageSize;

        $categories = BlogCategory::where('is_active', true)
            ->withCount(['blogs' => fn ($query) => $query->where('is_active', true)])
            ->having('blogs_count', '>', 0)
            ->orderBy('name')
            ->get();

        $categorySections = [];
        foreach ($categories->take(4) as $category) {
            $categoryBlogs = Blog::with(['blog_category'])
                ->where('is_active', true)
                ->where('blog_category_id', $category->id)
                ->latest()
                ->take(3)
                ->get();
            if ($categoryBlogs->count() > 0) {
                $categorySections[$category->name] = $categoryBlogs;
            }
        }

        $recentArticles = Blog::where('is_active', true)->latest()->take(3)->get();
        $allTags = Blog::where('is_active', true)
            ->whereNotNull('meta_keywords')
            ->pluck('meta_keywords')
            ->flatMap(fn ($keywords) => array_map('trim', explode(',', $keywords)))
            ->filter()
            ->unique()
            ->values()
            ->take(20)
            ->toArray();

        return view('front.blog.index', compact(
            'featuredArticle',
            'articles',
            'hasMorePages',
            'categories',
            'categorySections',
            'recentArticles',
            'allTags'
        ));
    }

    /**
     * Single blog post page (Blade, for web).
     */
    public function show(string $slug): View
    {
        $blog = Blog::with(['blog_category', 'author'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();
        if (! $blog) {
            return response()->json(['message' => 'Blog post not found.'], 404);
        }

        $this->setSeoTags([
            'google_title' => $blog->meta_title ?: $blog->title,
            'google_description' => $blog->meta_body ?: $blog->short_body,
            'google_image' => get_image($blog->image, asset('images/blog-og-image.jpg')),
        ]);

        $relatedArticles = Blog::with(['blog_category'])
            ->where('is_active', true)
            ->where('blog_category_id', $blog->blog_category_id)
            ->where('id', '!=', $blog->id)
            ->latest()
            ->take(3)
            ->get();

        $comments = $blog->comments()
            ->parents()
            ->approved()
            ->with(['user', 'replies.user'])
            ->latest()
            ->get();
        $totalReviews = $blog->comments()->approved()->count();

        return view('front.blog.show', compact('blog', 'relatedArticles', 'comments', 'totalReviews'));
    }

    /**
     * Load more blog posts (web: returns HTML for Blade; used by blog index).
     */
    public function loadMoreHtml(Request $request): JsonResponse
    {
        $perPage = 6;
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

        $html = '';
        foreach ($items as $article) {
            $html .= view('front.blog.partials.article-card', [
                'article' => $article,
                'showFeaturedBadge' => false,
            ])->render();
        }

        return response()->json(['html' => $html, 'hasMore' => $hasMore]);
    }

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
