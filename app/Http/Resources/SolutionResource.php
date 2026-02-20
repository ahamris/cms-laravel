<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SolutionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'anchor' => $this->anchor,
            'nav_title' => $this->nav_title,
            'subtitle' => $this->subtitle,
            'short_body' => $this->short_body,
            'long_body' => $this->long_body,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'meta_keywords' => $this->meta_keywords,
            'image' => get_image($this->image, asset('images/solutions-og-image.jpg')),
            'list_items' => $this->list_items,
            'link_text' => $this->link_text,
            'link_url' => $this->link_url,
            'testimonial_quote' => $this->testimonial_quote,
            'testimonial_author' => $this->testimonial_author,
            'testimonial_company' => $this->testimonial_company,
            'image_position' => $this->image_position,
            'url' => route('api.solutions.show', $this->anchor),
            'modules' => ModuleListResource::collection($this->whenLoaded('modules')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
