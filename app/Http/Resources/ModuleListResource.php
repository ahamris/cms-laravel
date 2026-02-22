<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ModuleListResource extends JsonResource
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
            'slug' => $this->slug,
            'short_body' => $this->short_body,
            'image' => get_image($this->image, asset('images/modules-og-image.jpg')),
            'url' => route('module.show', $this->slug),
            'sort_order' => $this->sort_order,
            'created_at' => $this->created_at?->toIso8601String(),
        ]);
    }
}
