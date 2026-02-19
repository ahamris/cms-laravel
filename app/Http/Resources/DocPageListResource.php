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
        $versionSlug = $section && $section->relationLoaded('version') ? $section->version->version : '';
        $sectionSlug = $section?->slug ?? '';

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'sort_order' => $this->sort_order,
            'url' => $versionSlug && $sectionSlug ? route('docs.page', [
                'version' => $versionSlug,
                'section' => $sectionSlug,
                'page' => $this->slug,
            ]) : null,
        ];
    }
}
