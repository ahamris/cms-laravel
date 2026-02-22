<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocVersionResource extends JsonResource
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
            'version' => $this->version,
            'name' => $this->name,
            'is_default' => $this->is_default,
            'sort_order' => $this->sort_order,
            'sections' => DocSectionResource::collection($this->whenLoaded('activeSections')),
        ]);
    }
}
