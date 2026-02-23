<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mews\Purifier\Facades\Purifier;
use OpenApi\Attributes as OA;

class CommentController extends Controller
{
    #[OA\Post(path: '/api/blog/{slug}/comments', summary: 'Add comment to blog post', description: 'Create a comment on the blog post identified by {slug}. Request body: body (required), parent_id (optional, for replies), guest_name and guest_email (required when not authenticated). No entity_type or entity_id — the blog is specified in the URL.', tags: ['Blog'], parameters: [
        new OA\Parameter(name: 'slug', in: 'path', required: true, description: 'Blog post slug', schema: new OA\Schema(type: 'string', example: 'my-blog-post')),
        new OA\RequestBody(required: true, content: new OA\JsonContent(required: ['body'], properties: [
            new OA\Property(property: 'body', type: 'string', maxLength: 2000, description: 'Comment text'),
            new OA\Property(property: 'parent_id', type: 'integer', nullable: true, description: 'ID of parent comment for replies'),
            new OA\Property(property: 'guest_name', type: 'string', maxLength: 255, description: 'Required when not logged in'),
            new OA\Property(property: 'guest_email', type: 'string', format: 'email', maxLength: 255, description: 'Required when not logged in'),
        ])),
    ], responses: [
        new OA\Response(response: 201, description: 'Comment stored'),
        new OA\Response(response: 404, description: 'Blog post not found'),
        new OA\Response(response: 422, description: 'Validation error'),
    ])]
    public function store(Request $request, string $slug): JsonResponse
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

        $blog->comments()->create([
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
        ], 201);
    }

    #[OA\Post(path: '/api/blog/{slug}/comments/{comment}/like', summary: 'Like a comment on a blog post', description: 'Record a like for the given comment. The comment must belong to the blog post identified by {slug}.', tags: ['Blog'], parameters: [
        new OA\Parameter(name: 'slug', in: 'path', required: true, description: 'Blog post slug', schema: new OA\Schema(type: 'string')),
        new OA\Parameter(name: 'comment', in: 'path', required: true, description: 'Comment ID', schema: new OA\Schema(type: 'integer')),
    ], responses: [
        new OA\Response(response: 200, description: 'Updated likes/dislikes'),
        new OA\Response(response: 404, description: 'Blog post or comment not found'),
    ])]
    public function like(string $slug, Comment $comment, Request $request): JsonResponse
    {
        return $this->handleVote($slug, $comment, $request, 'like');
    }

    #[OA\Post(path: '/api/blog/{slug}/comments/{comment}/dislike', summary: 'Dislike a comment on a blog post', description: 'Record a dislike for the given comment. The comment must belong to the blog post identified by {slug}.', tags: ['Blog'], parameters: [
        new OA\Parameter(name: 'slug', in: 'path', required: true, description: 'Blog post slug', schema: new OA\Schema(type: 'string')),
        new OA\Parameter(name: 'comment', in: 'path', required: true, description: 'Comment ID', schema: new OA\Schema(type: 'integer')),
    ], responses: [
        new OA\Response(response: 200, description: 'Updated likes/dislikes'),
        new OA\Response(response: 404, description: 'Blog post or comment not found'),
    ])]
    public function dislike(string $slug, Comment $comment, Request $request): JsonResponse
    {
        return $this->handleVote($slug, $comment, $request, 'dislike');
    }

    private function handleVote(string $slug, Comment $comment, Request $request, string $type): JsonResponse
    {
        $blog = Blog::where('slug', $slug)->where('is_active', true)->first();
        if (! $blog) {
            return response()->json(['message' => 'Blog post not found.'], 404);
        }
        if ($comment->entity_type !== Blog::class || (string) $comment->entity_id !== (string) $blog->id) {
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
