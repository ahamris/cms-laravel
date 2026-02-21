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
    /** @var list<class-string> Allowed entity types for comments (must have comments() relation). */
    protected static array $allowedEntityTypes = [
        Blog::class,
    ];

    #[OA\Post(path: '/api/artikelen/reactie', summary: 'Store comment', description: 'Create a comment on an entity (blog etc.). Body: body, entity_type, entity_id, parent_id?, guest_name?, guest_email?', tags: ['Comments'], responses: [
        new OA\Response(response: 201, description: 'Comment stored'),
        new OA\Response(response: 422, description: 'Validation error'),
    ])]
    public function store(Request $request): JsonResponse
    {
        if ($request->filled('hp_phone')) {
            return response()->json(['success' => true, 'message' => 'Bedankt! Uw reactie is geplaatst en wordt na controle gepubliceerd.']);
        }

        $validated = $request->validate([
            'body' => 'required|string|max:2000',
            'entity_type' => 'required|string',
            'entity_id' => 'required|integer',
            'parent_id' => 'nullable|exists:comments,id',
            'guest_name' => Auth::check() ? 'nullable' : 'required|string|max:255',
            'guest_email' => Auth::check() ? 'nullable' : 'required|email|max:255',
        ]);

        $entityClass = $request->entity_type;
        if (! in_array($entityClass, self::$allowedEntityTypes, true)) {
            return response()->json(['success' => false, 'message' => 'Ongeldig itemtype.'], 422);
        }

        $entity = $entityClass::findOrFail($request->entity_id);

        $entity->comments()->create([
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

    #[OA\Post(path: '/api/artikelen/reactie/{comment}/like', summary: 'Like comment', tags: ['Comments'], responses: [
        new OA\Response(response: 200, description: 'Updated likes/dislikes'),
    ])]
    public function like(Comment $comment, Request $request): JsonResponse
    {
        return $this->handleVote($comment, $request, 'like');
    }

    #[OA\Post(path: '/api/artikelen/reactie/{comment}/dislike', summary: 'Dislike comment', tags: ['Comments'], responses: [
        new OA\Response(response: 200, description: 'Updated likes/dislikes'),
    ])]
    public function dislike(Comment $comment, Request $request): JsonResponse
    {
        return $this->handleVote($comment, $request, 'dislike');
    }

    private function handleVote(Comment $comment, Request $request, string $type): JsonResponse
    {
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
