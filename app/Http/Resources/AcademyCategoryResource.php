<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AcademyCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array (single category with chapters and videos).
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'image_url' => $this->image_url,
            'sort_order' => $this->sort_order,
            'url' => route('api.academy.category.show', $this->slug),
            'chapters' => $this->whenLoaded('chapters', function () {
                $videos = $this->relationLoaded('videos') ? $this->videos : collect();
                return $this->chapters->map(fn ($chapter) => [
                    'id' => $chapter->id,
                    'name' => $chapter->name,
                    'description' => $chapter->description,
                    'sort_order' => $chapter->sort_order,
                    'videos' => AcademyVideoListResource::collection($videos->where('academy_chapter_id', $chapter->id)->values()),
                ]);
            }),
            'videos' => AcademyVideoListResource::collection($this->whenLoaded('videos', $this->videos ?? collect())),
            'videos_count' => $this->when(isset($this->videos_count), fn () => $this->videos_count),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
