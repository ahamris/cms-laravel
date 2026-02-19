<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class FeatureListResource extends JsonResource
{
    /**
     * Transform the resource into an array (list item).
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $anchor = $this->anchor ?? Str::slug($this->title);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'anchor' => $anchor,
            'description' => $this->description,
            'icon' => get_image($this->icon, asset('images/features-og-image.jpg')),
            'url' => route('features.show', $anchor),
            'sort_order' => $this->sort_order,
        ];
    }
}
