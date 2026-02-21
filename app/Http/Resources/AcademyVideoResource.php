<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AcademyVideoResource extends JsonResource
{
    /**
     * Transform the resource into an array (single video with category).
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
            'video_source_url' => $this->video_source_url,
            'video_provider' => $this->video_provider,
            'video_id' => $this->video_id,
            'thumbnail_url' => $this->thumbnail_url,
            'duration_seconds' => $this->duration_seconds,
            'duration_formatted' => $this->duration_formatted,
            'sort_order' => $this->sort_order,
            'url' => route('api.academy.video.show', $this->slug),
            'category' => $this->whenLoaded('category', fn () => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
                'url' => route('api.academy.category.show', $this->category->slug),
            ] : null),
            'chapter' => $this->whenLoaded('chapter', fn () => $this->chapter ? [
                'id' => $this->chapter->id,
                'name' => $this->chapter->name,
                'sort_order' => $this->chapter->sort_order,
            ] : null),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
