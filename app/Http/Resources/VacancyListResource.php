<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VacancyListResource extends JsonResource
{
    /**
     * Transform the resource into an array (list item).
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'location' => $this->location,
            'type' => $this->type,
            'department' => $this->department,
            'category' => $this->category,
            'closing_date' => $this->closing_date?->toDateString(),
            'url' => route('career.detail', $this->slug),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
