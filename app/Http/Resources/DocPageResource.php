<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocPageResource extends JsonResource
{
    /**
     * Transform the resource into an array (full page with content).
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $section = $this->section;
        $version = $section?->version;

        return resource_urls_to_paths([
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'sort_order' => $this->sort_order,
            'section' => $section ? [
                'id' => $section->id,
                'title' => $section->title,
                'slug' => $section->slug,
            ] : null,
            'version' => $version ? [
                'id' => $version->id,
                'version' => $version->version,
                'name' => $version->name,
            ] : null,
            'url' => $version && $section
                ? route('docs.page', ['version' => $version->version, 'section' => $section->slug, 'page' => $this->slug])
                : null,
            'template' => 'doc-page',
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ]);
    }
}
