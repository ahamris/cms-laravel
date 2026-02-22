<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LiveSessionListResource extends JsonResource
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
            'session_date' => $this->session_date?->toIso8601String(),
            'duration_minutes' => $this->duration_minutes,
            'thumbnail' => $this->thumbnail ? \Illuminate\Support\Facades\Storage::url($this->thumbnail) : null,
            'url' => route('academy.live-sessions.show', $this->slug),
            'is_upcoming' => $this->session_date && $this->session_date->isFuture(),
            'created_at' => $this->created_at?->toIso8601String(),
        ]);
    }
}
