<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mews\Purifier\Facades\Purifier;

class CommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request)
    {
        // Honeypot check - if filled, it's a bot
        if ($request->filled('hp_phone')) {
            return back()->with('success', 'Bedankt! Uw reactie is geplaatst en wordt na controle gepubliceerd.');
        }

        $request->validate([
            'body' => 'required|string|max:2000',
            'entity_type' => 'required|string',
            'entity_id' => 'required|integer',
            'parent_id' => 'nullable|exists:comments,id',
            'guest_name' => Auth::check() ? 'nullable' : 'required|string|max:255',
            'guest_email' => Auth::check() ? 'nullable' : 'required|email|max:255',
        ]);

        // Verify entity exists
        $entityClass = $request->entity_type;
        if (!class_exists($entityClass)) {
            return back()->with('error', 'Ongeldig itemtype.');
        }

        $entity = $entityClass::findOrFail($request->entity_id);

        // Store comment using relationship to ensure correct entity types (purify body to prevent XSS)
        $entity->comments()->create([
            'user_id' => Auth::id(),
            'guest_name' => Auth::check() ? null : $request->guest_name,
            'guest_email' => Auth::check() ? null : $request->guest_email,
            'body' => Purifier::clean($request->body),
            'parent_id' => $request->parent_id,
            'is_approved' => 0, // Moderation required
        ]);

        return back()->with('success', 'Bedankt! Uw reactie is geplaatst en wordt na controle gepubliceerd.');
    }

    /**
     * Like a comment
     */
    public function like(Comment $comment, Request $request)
    {
        return $this->handleVote($comment, $request, 'like');
    }

    /**
     * Dislike a comment
     */
    public function dislike(Comment $comment, Request $request)
    {
        return $this->handleVote($comment, $request, 'dislike');
    }

    /**
     * Shared logic to handle voting with duplicate protection
     */
    private function handleVote(Comment $comment, Request $request, string $type)
    {
        $ip = client_ip();
        $ua = $request->userAgent();
        $userId = Auth::id();

        // Check if user has already voted for this comment
        $existingVote = \App\Models\CommentVote::where('comment_id', $comment->id)
            ->where(function ($query) use ($ip, $userId) {
                $query->where('ip_address', $ip);
                if ($userId) {
                    $query->orWhere('user_id', $userId);
                }
            })->first();

        if (!$existingVote) {
            // Record the vote
            \App\Models\CommentVote::create([
                'comment_id' => $comment->id,
                'user_id' => $userId,
                'ip_address' => $ip,
                'user_agent' => $ua,
                'type' => $type
            ]);

            // Increment the counter
            $comment->increment($type === 'like' ? 'likes' : 'dislikes');
        }

        return back();
    }
}
