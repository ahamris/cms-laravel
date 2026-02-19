<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocSectionResource extends JsonResource
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
            'slug' => $this->slug,
            'description' => $this->description,
            'sort_order' => $this->sort_order,
            'url' => $this->when($this->relationLoaded('version'), fn () => route('docs.section', [
                'version' => $this->version->version,
                'section' => $this->slug,
            ])),
            'pages' => DocPageListResource::collection($this->whenLoaded('activePages')),
        ];
    }
}
