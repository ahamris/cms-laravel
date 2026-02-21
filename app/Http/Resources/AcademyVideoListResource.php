<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AcademyVideoListResource extends JsonResource
{
    /**
     * Transform the resource into an array (video list item).
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'thumbnail_url' => $this->thumbnail_url,
            'duration_seconds' => $this->duration_seconds,
            'duration_formatted' => $this->duration_formatted,
            'video_provider' => $this->video_provider,
            'sort_order' => $this->sort_order,
            'url' => route('api.academy.video.show', $this->slug),
            'category' => $this->whenLoaded('category', fn () => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
                'url' => route('api.academy.category.show', $this->category->slug),
            ] : null),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
