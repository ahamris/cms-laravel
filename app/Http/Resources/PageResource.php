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
        $page = $this->resource;
        $elements = $page->relationLoaded('elements') ? $page->elements : collect();

        return resource_urls_to_paths([
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'short_body' => $this->short_body,
            'long_body' => $this->long_body,
            'meta_title' => $this->meta_title,
            'meta_body' => $this->meta_body,
            'meta_keywords' => $this->meta_keywords,
            'image' => get_image($this->image, asset('front/images/blog.png')),
            'icon' => $this->icon,
            'layout' => $this->template ?? config('page_templates.default', 'default'),
            'template' => resolve_menu_template(api_path('page', $this->slug), $this->slug),
            'url' => route('api.pages.show', ['slug' => $this->slug]),
            'elements' => ElementResource::collection($elements)->resolve($request),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ]);
    }
}
