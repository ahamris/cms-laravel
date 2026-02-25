<?php

namespace App\Http\Requests;

use App\Models\PartnerTechItem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PartnerTechItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'banner' => 'nullable',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:65535',
            'type' => ['required', Rule::in([PartnerTechItem::TYPE_PARTNER, PartnerTechItem::TYPE_TECH_STACK])],
            'data' => 'nullable|array',
            'data.*.link' => 'nullable|string|max:500',
            'data.*.link_type' => ['nullable', Rule::in(['external', 'static'])],
            'data.*.image' => 'nullable',
            'data.*.sort_order' => 'nullable|integer|min:0',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ];
    }

    /**
     * Configure the validator for file uploads.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->hasFile('banner')) {
                $f = $this->file('banner');
                if (! str_starts_with($f->getMimeType(), 'image/')) {
                    $validator->errors()->add('banner', 'Banner must be an image.');
                } elseif ($f->getSize() > 8 * 1024 * 1024) {
                    $validator->errors()->add('banner', 'Banner must be under 8MB.');
                }
            }
        });
    }

    public function attributes(): array
    {
        return [
            'name' => 'name',
            'data.*.link' => 'link',
            'data.*.link_type' => 'link type',
            'data.*.image' => 'image',
        ];
    }

    /**
     * Get validated data with normalized data array (one record per type).
     */
    public function safeForModel(): array
    {
        $validated = $this->validated();
        $raw = $validated['data'] ?? [];
        $data = [];
        foreach ($raw as $index => $item) {
            if (! is_array($item)) {
                continue;
            }
            $link = trim((string) ($item['link'] ?? ''));
            $linkType = isset($item['link_type']) && in_array($item['link_type'], ['external', 'static'], true)
                ? $item['link_type'] : 'external';
            $image = isset($item['image']) && is_string($item['image']) ? trim($item['image']) : null;
            $sortOrder = isset($item['sort_order']) ? (int) $item['sort_order'] : $index;
            $data[] = [
                'link' => $link ?: null,
                'link_type' => $linkType,
                'image' => $image ?: null,
                'sort_order' => $sortOrder,
            ];
        }
        usort($data, fn ($a, $b) => $a['sort_order'] <=> $b['sort_order']);
        $validated['data'] = $data;
        if ($this->has('sort_order')) {
            $validated['sort_order'] = (int) $this->input('sort_order');
        }
        if ($this->has('is_active')) {
            $validated['is_active'] = $this->boolean('is_active');
        }

        return $validated;
    }
}
