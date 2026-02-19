<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\SeoSetTrait;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    use SeoSetTrait;

    /**
     * Display the blog listing page.
     */
    public function index(Request $request): View
    {
        // Set SEO tags for blog index
        $this->setSeoTags([
            'google_title' => 'Blog & Artikelen - ' . get_setting('site_name'),
            'google_description' => 'Ontdek onze laatste artikelen, tips en nieuws over OpenPublicatie.',
            'google_image' => asset('images/blog-og-image.jpg'),
        ]);

        $perPage = 6;

        // Get featured blog (first active featured blog or latest)
        $featuredArticle = Blog::with(['blog_category', 'author'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->first();

        // If no featured blog, get the latest one
        if (!$featuredArticle) {
            $featuredArticle = Blog::with(['blog_category', 'author'])
                ->where('is_active', true)
                ->latest()
                ->first();
        }

        // Get regular articles (excluding featured)
        $articlesQuery = Blog::with(['blog_category', 'author'])
            ->where('is_active', true);

        // Apply search filter if present
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $articlesQuery->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('meta_keywords', 'like', "%{$searchTerm}%")
                    ->orWhere('short_body', 'like', "%{$searchTerm}%")
                    ->orWhere('long_body', 'like', "%{$searchTerm}%");
            });
        }

        // Apply category filter if present
        if ($request->filled('category')) {
            $categorySlug = $request->category;
            $articlesQuery->whereHas('blog_category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        if ($featuredArticle && !$request->filled('search') && !$request->filled('category')) {
            $articlesQuery->where('id', '!=', $featuredArticle->id);
        }

        // First page: 5 articles when featured (1+5=6 cards for grid-cols-3), else 6
        $hasFeaturedOnFirstPage = $featuredArticle && !$request->filled('search') && !$request->filled('category');
        $firstPageSize = $hasFeaturedOnFirstPage ? 5 : 6;
        $total = (clone $articlesQuery)->count();
        $articles = $articlesQuery->latest()->take($firstPageSize)->get();
        $hasMorePages = $total > $firstPageSize;

        // Get all categories with their blogs for category sections
        $categories = BlogCategory::where('is_active', true)
            ->withCount([
                'blogs' => function ($query) {
                    $query->where('is_active', true);
                }
            ])
            ->having('blogs_count', '>', 0)
            ->orderBy('name')
            ->get();

        // Get blogs grouped by category (3 per category)
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

        // Get recent articles for sidebar
        $recentArticles = Blog::where('is_active', true)
            ->latest()
            ->take(3)
            ->get();

        // Extract unique tags from all active blogs
        $allTags = Blog::where('is_active', true)
            ->whereNotNull('meta_keywords')
            ->pluck('meta_keywords')
            ->flatMap(function ($keywords) {
                return array_map('trim', explode(',', $keywords));
            })
            ->filter()
            ->unique()
            ->values()
            ->take(20) // Limit to 20 tags
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
     * Display a specific blog post.
     */
    public function show(string $slug): View
    {
        $blog = Blog::with(['blog_category', 'author'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Set SEO tags for blog post
        $this->setSeoTags([
            'google_title' => $blog->meta_title ?: $blog->title,
            'google_description' => $blog->meta_body ?: $blog->short_body,
            'google_image' => get_image($blog->image, asset('images/blog-og-image.jpg')),
        ]);

        // Get related articles from same category
        $relatedArticles = Blog::with(['blog_category'])
            ->where('is_active', true)
            ->where('blog_category_id', $blog->blog_category_id)
            ->where('id', '!=', $blog->id)
            ->latest()
            ->take(3)
            ->get();

        // Get approved parent comments with replies
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
     * API endpoint: latest blog posts for dynamic blog section (e.g. page builder).
     */
    public function apiPosts(): JsonResponse
    {
        $blogs = Blog::with(['blog_category', 'author'])
            ->where('is_active', true)
            ->latest()
            ->take(3)
            ->get()
            ->map(fn(Blog $b) => [
                'title' => $b->title,
                'slug' => $b->slug,
                'url' => route('blog.show', $b->slug),
                'image' => get_image($b->image, asset('front/images/blog.png')),
                'short_body' => \Illuminate\Support\Str::limit(strip_tags($b->short_body ?? ''), 160),
                'date' => $b->created_at?->format('M j, Y'),
                'date_attr' => $b->created_at?->format('Y-m-d'),
                'category' => $b->blog_category?->name ?? 'Blog',
                'category_slug' => $b->blog_category?->slug ?? null,
                'author_name' => $b->author ? ($b->author->full_name ?? $b->author->name ?? 'Author') : 'Author',
                'author_avatar' => $b->author ? get_image($b->author->avatar, 'https://ui-avatars.com/api/?name=' . urlencode($b->author->name ?? 'Author') . '&size=80') : 'https://ui-avatars.com/api/?name=Author&size=80',
            ]);

        return response()->json($blogs);
    }

    /**
     * API endpoint: single blog post by slug (for React SPA).
     */
    public function apiShow(string $slug): JsonResponse
    {
        $blog = Blog::with(['blog_category', 'author'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return response()->json([
            'data' => [
                'id' => $blog->id,
                'title' => $blog->title,
                'slug' => $blog->slug,
                'short_body' => $blog->short_body,
                'long_body' => $blog->long_body,
                'image' => get_image($blog->image, asset('front/images/blog.png')),
                'meta_title' => $blog->meta_title,
                'meta_description' => $blog->meta_description,
                'url' => route('blog.show', $blog->slug),
                'date' => $blog->created_at?->format('M j, Y'),
                'date_attr' => $blog->created_at?->format('Y-m-d'),
                'published_at' => $blog->published_at?->toIso8601String(),
                'category' => $blog->blog_category ? ['id' => $blog->blog_category->id, 'name' => $blog->blog_category->name, 'slug' => $blog->blog_category->slug] : null,
                'author' => $blog->author ? [
                    'id' => $blog->author->id,
                    'name' => $blog->author->full_name ?? $blog->author->name ?? 'Author',
                    'avatar' => get_image($blog->author->avatar, 'https://ui-avatars.com/api/?name=' . urlencode($blog->author->name ?? 'Author') . '&size=80'),
                ] : null,
                'created_at' => $blog->created_at?->toIso8601String(),
                'updated_at' => $blog->updated_at?->toIso8601String(),
            ],
        ]);
    }

    /**
     * Load more blog posts (for "Daha fazla blog yazısı" button).
     * Same filters as index (search, category), returns next page of 6 as HTML + hasMore.
     */
    public function loadMore(Request $request): JsonResponse
    {
        $perPage = 6;
        $page = max(1, (int) $request->input('page', 2));

        $featuredArticle = Blog::with(['blog_category', 'author'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->first();
        if (!$featuredArticle) {
            $featuredArticle = Blog::with(['blog_category', 'author'])
                ->where('is_active', true)
                ->latest()
                ->first();
        }

        $articlesQuery = Blog::with(['blog_category', 'author'])
            ->where('is_active', true);

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
            $categorySlug = $request->category;
            $articlesQuery->whereHas('blog_category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }
        if ($featuredArticle && !$request->filled('search') && !$request->filled('category')) {
            $articlesQuery->where('id', '!=', $featuredArticle->id);
        }

        // When no search/category: first page had 5 (featured+5=6), so page 2 = skip 5 take 6, page 3 = skip 11 take 6
        $hasFeaturedMode = !$request->filled('search') && !$request->filled('category') && $featuredArticle;
        $perPage = 6;
        if ($hasFeaturedMode && $page >= 2) {
            $offset = 5 + ($page - 2) * $perPage;
        } else {
            $offset = ($page - 1) * $perPage;
        }

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

        return response()->json([
            'html' => $html,
            'hasMore' => $hasMore,
        ]);
    }
}
