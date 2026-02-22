<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LiveSessionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return resource_urls_to_paths([
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'content' => $this->content,
            'session_date' => $this->session_date?->toIso8601String(),
            'duration_minutes' => $this->duration_minutes,
            'max_participants' => $this->max_participants,
            'status' => $this->status,
            'type' => $this->type,
            'meeting_url' => $this->meeting_url,
            'recording_url' => $this->recording_url,
            'thumbnail' => $this->thumbnail ? \Illuminate\Support\Facades\Storage::url($this->thumbnail) : null,
            'icon' => $this->icon,
            'color' => $this->color,
            'is_featured' => $this->is_featured,
            'url' => route('academy.live-sessions.show', $this->slug),
            'presenters' => $this->whenLoaded('presenters', fn () => $this->presenters->map(fn ($p) => [
                'id' => $p->id,
                'name' => $p->name ?? $p->title,
                'avatar' => get_image($p->avatar ?? null),
            ])),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ]);
    }
}
