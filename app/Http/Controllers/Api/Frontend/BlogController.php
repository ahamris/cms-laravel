<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\BlogListResource;
use App\Http\Resources\BlogResource;
use App\Http\Resources\CommentResource;
use App\Models\Blog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mews\Purifier\Facades\Purifier;
use OpenApi\Attributes as OA;

class BlogController extends Controller
{
    #[OA\Get(path: '/api/blog', summary: 'List blog posts', description: 'Paginated list of all active blog posts. Query: page, per_page, search, category.', tags: ['Blog'], parameters: [
        new OA\Parameter(name: 'page', in: 'query', schema: new OA\Schema(type: 'integer', default: 1)),
        new OA\Parameter(name: 'per_page', in: 'query', schema: new OA\Schema(type: 'integer', default: 6)),
        new OA\Parameter(name: 'search', in: 'query', schema: new OA\Schema(type: 'string')),
        new OA\Parameter(name: 'category', in: 'query', schema: new OA\Schema(type: 'string')),
    ], responses: [
        new OA\Response(response: 200, description: 'Blog list with data, has_more, next_page', content: new OA\JsonContent(properties: [
            new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/BlogListItem')),
            new OA\Property(property: 'template', type: 'string', description: 'Frontend template hint (blog-list)'),
            new OA\Property(property: 'has_more', type: 'boolean'),
            new OA\Property(property: 'next_page', type: 'integer', nullable: true),
        ])),
    ])]
    public function index(Request $request): JsonResponse
    {
        $perPage = max(1, min((int) $request->input('per_page', 6), 24));
        $page = max(1, (int) $request->input('page', 1));

        $featuredArticle = Blog::with(['blog_category', 'blog_type', 'author'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->first();
        if (! $featuredArticle) {
            $featuredArticle = Blog::with(['blog_category', 'blog_type', 'author'])
                ->where('is_active', true)
                ->latest()
                ->first();
        }

        $articlesQuery = Blog::with(['blog_category', 'blog_type', 'author'])->where('is_active', true);
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
            'template' => 'blog-list',
            'has_more' => $hasMore,
            'next_page' => $hasMore ? $page + 1 : null,
        ]);
    }

    #[OA\Get(path: '/api/blog/{slug}', summary: 'Get a blog post by slug', tags: ['Blog'], parameters: [
        new OA\Parameter(name: 'slug', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
    ], responses: [
        new OA\Response(response: 200, description: 'Blog post', content: new OA\JsonContent(ref: '#/components/schemas/Blog')),
        new OA\Response(response: 404, description: 'Not found'),
    ])]
    public function apiShow(string $slug)
    {
        $blog = Blog::with([
            'blog_category',
            'blog_type',
            'author',
            'comments' => fn ($q) => $q->approved()->whereNull('parent_id')->with('replies'),
        ])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();
        if (! $blog) {
            return response()->json(['message' => 'Blog post not found.'], 404);
        }

        return new BlogResource($blog);
    }

    #[OA\Post(path: '/api/blog/{slug}/comments', summary: 'Add comment to blog post', description: 'Create a comment on the blog post identified by {slug}. Send as form fields (application/x-www-form-urlencoded or multipart/form-data). Blog is specified in the URL.', tags: ['Blog'], parameters: [
        new OA\Parameter(name: 'slug', in: 'path', required: true, description: 'Blog post slug', schema: new OA\Schema(type: 'string', example: 'my-blog-post')),
        new OA\RequestBody(required: true, content: new OA\MediaType(mediaType: 'application/x-www-form-urlencoded', schema: new OA\Schema(required: ['body'], type: 'object', properties: [
            new OA\Property(property: 'body', type: 'string', maxLength: 2000, description: 'Comment text (required)'),
            new OA\Property(property: 'parent_id', type: 'integer', description: 'ID of parent comment for replies'),
            new OA\Property(property: 'guest_name', type: 'string', maxLength: 255, description: 'Your name (required when not logged in)'),
            new OA\Property(property: 'guest_email', type: 'string', format: 'email', maxLength: 255, description: 'Your email (required when not logged in)'),
            new OA\Property(property: 'hp_phone', type: 'string', description: 'Honeypot — leave empty'),
        ]))),
    ], responses: [
        new OA\Response(response: 201, description: 'Comment stored'),
        new OA\Response(response: 404, description: 'Blog post not found'),
        new OA\Response(response: 422, description: 'Validation error'),
    ])]
    public function storeComment(Request $request, string $slug): JsonResponse
    {
        if ($request->filled('hp_phone')) {
            return response()->json(['success' => true, 'message' => 'Bedankt! Uw reactie is geplaatst en wordt na controle gepubliceerd.']);
        }

        $blog = Blog::where('slug', $slug)->where('is_active', true)->first();
        if (! $blog) {
            return response()->json(['message' => 'Blog post not found.'], 404);
        }

        $validated = $request->validate([
            'body' => 'required|string|max:2000',
            'parent_id' => 'nullable|exists:comments,id',
            'guest_name' => Auth::check() ? 'nullable' : 'required|string|max:255',
            'guest_email' => Auth::check() ? 'nullable' : 'required|email|max:255',
        ]);

        $comment = $blog->comments()->create([
            'user_id' => Auth::id(),
            'guest_name' => Auth::check() ? null : $validated['guest_name'],
            'guest_email' => Auth::check() ? null : $validated['guest_email'],
            'body' => Purifier::clean($validated['body']),
            'parent_id' => $validated['parent_id'] ?? null,
            'is_approved' => 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Bedankt! Uw reactie is geplaatst en wordt na controle gepubliceerd.',
            'data' => new CommentResource($comment),
        ], 201);
    }

    #[OA\Post(path: '/api/blog/{slug}/comments/{comment}/like', summary: 'Like a comment on a blog post', description: 'Record a like for the given comment. The comment must belong to the blog post identified by {slug}.', tags: ['Blog'], parameters: [
        new OA\Parameter(name: 'slug', in: 'path', required: true, description: 'Blog post slug', schema: new OA\Schema(type: 'string')),
        new OA\Parameter(name: 'comment', in: 'path', required: true, description: 'Comment ID', schema: new OA\Schema(type: 'integer')),
    ], responses: [
        new OA\Response(response: 200, description: 'Updated likes/dislikes'),
        new OA\Response(response: 404, description: 'Blog post or comment not found'),
    ])]
    public function likeComment(string $slug, int|string $comment, Request $request): JsonResponse
    {
        return $this->handleCommentVote($slug, (int) $comment, $request, 'like');
    }

    #[OA\Post(path: '/api/blog/{slug}/comments/{comment}/dislike', summary: 'Dislike a comment on a blog post', description: 'Record a dislike for the given comment. The comment must belong to the blog post identified by {slug}.', tags: ['Blog'], parameters: [
        new OA\Parameter(name: 'slug', in: 'path', required: true, description: 'Blog post slug', schema: new OA\Schema(type: 'string')),
        new OA\Parameter(name: 'comment', in: 'path', required: true, description: 'Comment ID', schema: new OA\Schema(type: 'integer')),
    ], responses: [
        new OA\Response(response: 200, description: 'Updated likes/dislikes'),
        new OA\Response(response: 404, description: 'Blog post or comment not found'),
    ])]
    public function dislikeComment(string $slug, int|string $comment, Request $request): JsonResponse
    {
        return $this->handleCommentVote($slug, (int) $comment, $request, 'dislike');
    }

    private function handleCommentVote(string $slug, int $commentId, Request $request, string $type): JsonResponse
    {
        $blog = Blog::where('slug', $slug)->where('is_active', true)->first();
        if (! $blog) {
            return response()->json(['message' => 'Blog post not found.'], 404);
        }

        $comment = $blog->comments()->find($commentId);
        if (! $comment) {
            return response()->json(['message' => 'Comment not found for this blog post.'], 404);
        }

        $ip = client_ip();
        $userId = Auth::id();

        $existingVote = \App\Models\CommentVote::where('comment_id', $comment->id)
            ->where(function ($query) use ($ip, $userId) {
                $query->where('ip_address', $ip);
                if ($userId) {
                    $query->orWhere('user_id', $userId);
                }
            })->first();

        if (! $existingVote) {
            \App\Models\CommentVote::create([
                'comment_id' => $comment->id,
                'user_id' => $userId,
                'ip_address' => $ip,
                'user_agent' => $request->userAgent(),
                'type' => $type,
            ]);
            $comment->increment($type === 'like' ? 'likes' : 'dislikes');
        }

        return response()->json([
            'data' => [
                'id' => $comment->id,
                'likes' => $comment->fresh()->likes,
                'dislikes' => $comment->fresh()->dislikes,
            ],
        ]);
    }
}
