<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array (for API; approved comments only).
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'body' => $this->body,
            'author_name' => $this->user_id
                ? ($this->user?->full_name ?? $this->user?->name ?? 'User')
                : ($this->guest_name ?? 'Guest'),
            'created_at' => $this->created_at?->toIso8601String(),
            'likes' => (int) ($this->likes ?? 0),
            'dislikes' => (int) ($this->dislikes ?? 0),
            // Always return a key for API stability. When `replies` isn't eager-loaded,
            // we still want `replies: []` instead of omitting the property.
            'replies' => $this->relationLoaded('replies')
                ? CommentResource::collection($this->replies)
                : [],
        ];
    }
}
