<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SolutionListResource extends JsonResource
{
    /**
     * Transform the resource into an array (list item).
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return resource_urls_to_paths([
            'id' => $this->id,
            'title' => $this->title,
            'anchor' => $this->anchor,
            'nav_title' => $this->nav_title,
            'subtitle' => $this->subtitle,
            'short_body' => $this->short_body,
            'image' => get_image($this->image, asset('images/solutions-og-image.jpg')),
            'url' => route('api.solutions.show', $this->anchor),
            'sort_order' => $this->sort_order,
            'created_at' => $this->created_at?->toIso8601String(),
        ]);
    }
}
