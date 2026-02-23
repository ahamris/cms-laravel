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
        return resource_urls_to_paths([
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'sort_order' => $this->sort_order,
            'url' => $this->when($this->relationLoaded('version'), function () {
                return route('api.docs.version', ['version' => $this->version->version]);
            }),
            'pages' => DocPageListResource::collection($this->whenLoaded('activePages')),
        ]);
    }
}
