<?php

namespace App\Http\Resources;

use App\Models\PartnerTechItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PartnerTechItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * Single record per type; data is an array of link items (multipliable).
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $dataItems = $this->data_items_resolved ?? [];

        return resource_urls_to_paths([
            'id' => $this->id,
            'name' => $this->name,
            'banner' => $this->banner ? get_image($this->banner) : null,
            'title' => $this->title,
            'description' => $this->description,
            'type' => (int) $this->type,
            'type_label' => $this->type === PartnerTechItem::TYPE_PARTNER ? 'partner' : 'tech_stack',
            'data' => $dataItems,
            'sort_order' => $this->sort_order,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ]);
    }
}
