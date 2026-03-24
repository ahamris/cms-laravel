<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ElementResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $type = $this->type;
        $options = $this->options ?? [];

        if (is_array($options) && array_key_exists('image_path', $options)) {
            $options['image_path'] = get_image($options['image_path'] ?? null);
        }

        return [
            'id' => $this->id,
            'type' => $type instanceof \BackedEnum ? $type->value : $type,
            'title' => $this->title,
            'sub_title' => $this->sub_title,
            'description' => $this->description,
            'options' => $options,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
