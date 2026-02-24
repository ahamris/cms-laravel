<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class FeatureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $anchor = $this->anchor ?? Str::slug($this->title);

        return resource_urls_to_paths([
            'id' => $this->id,
            'title' => $this->title,
            'anchor' => $anchor,
            'description' => $this->description,
            'icon' => get_image($this->icon, asset('images/features-og-image.jpg')),
            'url' => route('features.show', $anchor),
            'template' => resolve_menu_template(api_path('feature', $anchor)),
            'sort_order' => $this->sort_order,
            'modules' => ModuleListResource::collection($this->whenLoaded('modules')),
            'solution' => $this->when($this->relationLoaded('solution'), fn () => $this->solution ? [
                'id' => $this->solution->id,
                'title' => $this->solution->title,
                'anchor' => $this->solution->anchor,
            ] : null),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ]);
    }
}
