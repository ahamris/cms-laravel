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
        return resource_urls_to_paths([
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'api_path' => route('api.vacancies.show', $this->slug),
            'location' => $this->location,
            'type' => $this->type,
            'department' => $this->department,
            'category' => $this->category,
            'closing_date' => $this->closing_date?->toDateString(),
            'created_at' => $this->created_at?->toIso8601String(),
        ]);
    }
}
