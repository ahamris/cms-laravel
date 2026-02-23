<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseVideoResource extends JsonResource
{
    /**
     * Transform the resource into an array (single video with category).
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
            'video_source_url' => $this->video_source_url,
            'video_provider' => $this->video_provider,
            'video_id' => $this->video_id,
            'thumbnail_url' => $this->thumbnail_url,
            'duration_seconds' => $this->duration_seconds,
            'duration_formatted' => $this->duration_formatted,
            'sort_order' => $this->sort_order,
            'url' => route('api.course.video.show', $this->slug),
            'category' => $this->whenLoaded('category', fn () => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
                'url' => route('api.course.category.show', $this->category->slug),
            ] : null),
            'chapter' => $this->whenLoaded('course', fn () => $this->course ? [
                'id' => $this->course->id,
                'name' => $this->course->name,
                'sort_order' => $this->course->sort_order,
            ] : null),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ]);
    }
}
