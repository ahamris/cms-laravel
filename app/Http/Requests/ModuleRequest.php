<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModuleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Handle toggle: if checkbox is checked, it sends value '1' (overrides hidden '0')
        $isActive = $this->input('is_active');
        if (is_array($isActive)) {
            $isActive = in_array('1', $isActive) ? '1' : '0';
        } else {
            $isActive = $isActive ?? '0';
        }
        $this->merge(['is_active' => $isActive]);

        // Normalize list_items so each element is a string (avoids "must be a string" validation errors)
        $listItems = $this->input('list_items');
        if (is_array($listItems)) {
            $this->merge([
                'list_items' => array_values(array_map(function ($item) {
                    if (is_array($item) || is_object($item)) {
                        return '';
                    }
                    return $item === null ? '' : (string) $item;
                }, $listItems)),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Basic content
            'anchor' => 'required|string|max:255|unique:modules,anchor,'.$this->route('module')?->id,
            'nav_title' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:modules,slug,'.$this->route('module')?->id,
            'subtitle' => 'nullable|string',
            'short_body' => 'nullable|string',
            'long_body' => 'nullable|string',
            'list_items' => 'nullable|array',
            'list_items.*' => 'nullable|string',
            'link_text' => 'nullable|string|max:255',

            // Testimonial
            'testimonial_quote' => 'nullable|string',
            'testimonial_author' => 'nullable|string|max:255',
            'testimonial_company' => 'nullable|string|max:255',

            // Media
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
            'image_position' => 'nullable|string|in:left,right',

            // Status & Order
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|in:0,1',

            // SEO Meta Fields
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string',

            // Relationships
            'features' => 'nullable|array',
            'features.*' => 'exists:features,id',
            'solutions' => 'nullable|array',
            'solutions.*' => 'exists:solutions,id',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Title is required.',
            'slug.unique' => 'This slug is already taken.',
            'sort_order.integer' => 'Sort order must be a number.',
            'sort_order.min' => 'Sort order must be at least 0.',
            'meta_title.max' => 'Meta title may not be greater than 60 characters.',
            'meta_description.max' => 'Meta description may not be greater than 160 characters.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'title' => 'Title',
            'slug' => 'Slug',
            'short_body' => 'Short body',
            'long_body' => 'Long body',
            'sort_order' => 'Sort order',
            'is_active' => 'Active status',
            'meta_title' => 'Meta title',
            'meta_description' => 'Meta description',
            'meta_keywords' => 'Meta keywords',
        ];
    }
}
