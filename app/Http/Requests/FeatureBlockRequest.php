<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeatureBlockRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Handle toggle: if checkbox is checked, it sends value '1' (overrides hidden '0')
        // If checkbox is unchecked, only hidden input with value '0' is sent
        // When both are sent (checkbox checked), PHP receives them as an array
        $isActive = $this->input('is_active');
        
        if (is_array($isActive)) {
            // Both inputs sent, checkbox is checked - get the checkbox value ('1')
            $isActive = in_array('1', $isActive) ? '1' : '0';
        } else {
            // Only one input sent - use it directly ('0' or '1')
            $isActive = $isActive ?? '0';
        }
        
        $this->merge([
            'is_active' => $isActive,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [
            'identifier' => [
                'string',
                'max:255',
                'alpha_dash',
            ],
            'section_title' => 'nullable|string|max:255',
            'section_subtitle' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.title' => 'required|string|max:255',
            'items.*.content' => 'nullable|string',
            'items.*.image_position' => 'nullable|in:left,right',
            'items.*.link_text' => 'nullable|string|max:255',
            'items.*.link_url' => 'nullable|string|max:500',
            'is_active' => 'nullable|in:0,1',
            'sort_order' => 'nullable|integer',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'identifier.required' => 'The identifier field is required.',
            'identifier.alpha_dash' => 'The identifier may only contain letters, numbers, dashes and underscores.',
            'items.required' => 'At least one item is required.',
            'items.min' => 'At least one item is required.',
            'items.*.title.required' => 'Each item must have a title.',
            'items.*.title.max' => 'Item title may not exceed 255 characters.',
            'items.*.image.max' => 'Image URL may not exceed 500 characters.',
            'items.*.link_url.max' => 'Link URL may not exceed 500 characters.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'identifier' => 'identifier',
            'section_title' => 'section title',
            'section_subtitle' => 'section subtitle',
            'items' => 'items',
            'items.*.title' => 'item title',
            'items.*.content' => 'item content',
            'items.*.image' => 'item image',
            'items.*.link_text' => 'link text',
            'items.*.link_url' => 'link URL',
            'is_active' => 'active status',
            'sort_order' => 'sort order',
        ];
    }
}
