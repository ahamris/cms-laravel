<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AcademyCategoryListResource extends JsonResource
{
    /**
     * Transform the resource into an array (category list item, optional nested videos).
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
            'url' => route('api.academy.category.show', $this->slug),
            'videos_count' => $this->when(isset($this->videos_count), fn () => $this->videos_count),
            'videos_duration_seconds' => $this->when(isset($this->videos_sum_duration_seconds), fn () => (int) $this->videos_sum_duration_seconds),
            'videos' => AcademyVideoListResource::collection($this->whenLoaded('videos', $this->videos ?? collect())),
            'created_at' => $this->created_at?->toIso8601String(),
        ]);
    }
}
