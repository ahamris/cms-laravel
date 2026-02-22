<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    /**
     * Transform the resource into an array (single page with long_body).
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
            'long_body' => $this->long_body,
            'meta_title' => $this->meta_title,
            'meta_body' => $this->meta_body,
            'meta_keywords' => $this->meta_keywords,
            'image' => $this->image ? asset($this->image) : null,
            'icon' => $this->icon,
            'template' => $this->template ?? config('page_templates.default', 'default'),
            'url' => route('api.pages.show', ['slug' => $this->slug]),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ]);
    }
}
