<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageListResource extends JsonResource
{
    /**
     * Transform the resource into an array (list item: no long_body).
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $page = $this->resource;
        $elements = $page->relationLoaded('elements') ? $page->elements : collect();

        return resource_urls_to_paths([
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'short_body' => $this->short_body,
            'meta_title' => $this->meta_title,
            'meta_body' => $this->meta_body,
            'meta_keywords' => $this->meta_keywords,
            'image' => $this->image ? asset($this->image) : null,
            'icon' => $this->icon,
            'template' => $this->template ?? config('page_templates.default', 'default'),
            'elements' => ElementResource::collection($elements)->resolve($request),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ]);
    }
}
