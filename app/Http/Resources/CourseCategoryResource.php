<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array (single category with courses/chapters and videos).
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return resource_urls_to_paths([
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'image_url' => $this->image_url,
            'sort_order' => $this->sort_order,
            'url' => route('api.course.category.show', $this->slug),
            'chapters' => $this->whenLoaded('courses', function () {
                $videos = $this->relationLoaded('videos') ? $this->videos : collect();
                return $this->courses->map(fn ($course) => [
                    'id' => $course->id,
                    'name' => $course->name,
                    'description' => $course->description,
                    'sort_order' => $course->sort_order,
                    'videos' => CourseVideoListResource::collection($videos->where('course_id', $course->id)->values()),
                ]);
            }),
            'videos' => CourseVideoListResource::collection($this->whenLoaded('videos', $this->videos ?? collect())),
            'videos_count' => $this->when(isset($this->videos_count), fn () => $this->videos_count),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ]);
    }
}
