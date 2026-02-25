<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocPageListResource extends JsonResource
{
    /**
     * Transform the resource into an array (list item, no full content).
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $section = $this->whenLoaded('section') ? $this->section : null;
        $sectionSlug = $section?->slug ?? '';

        return resource_urls_to_paths([
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'sort_order' => $this->sort_order,
            'url' => $sectionSlug ? route('api.docs.page', [
                'section' => $sectionSlug,
                'page' => $this->slug,
            ]) : null,
        ]);
    }
}
