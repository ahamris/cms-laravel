<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WidgetRequest extends FormRequest
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
        // Handle checkbox: if checkbox is checked, it sends value 1 (overrides hidden 0)
        // If checkbox is unchecked, only hidden input with value 0 is sent
        // Get the value - if it's an array (both inputs sent), take the checkbox value (last one)
        $isActive = $this->input('is_active');
        
        if (is_array($isActive)) {
            // Both inputs sent, checkbox is checked - get the last value (checkbox value)
            $isActive = end($isActive);
        }
        
        // Convert to boolean: "1" or 1 = true, "0" or 0 = false
        $isActive = filter_var($isActive, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false;
        
        $this->merge([
            'is_active' => $isActive,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'section_identifier' => 'required|string|max:255',
            'template' => 'required|string|max:255',
            'template_parameter' => 'nullable|string|max:255',
            'template_parameter_id' => 'nullable|integer',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'button_text' => 'nullable|string|max:255',
            'button_url' => 'nullable|string|max:500',
            'button_external' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20480',
            'background_color' => 'nullable|string|max:7',
            'text_color' => 'nullable|string|max:7',
            'is_active' => 'required|boolean',
            'sort_order' => 'required|integer|min:0',
            'meta_data' => 'nullable|array',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     */
    public function messages(): array
    {
        return [
            'section_identifier.required' => 'Section identifier is required.',
            'section_identifier.max' => 'Section identifier may not be greater than 255 characters.',
            'template.required' => 'Template is required.',
            'template.max' => 'Template may not be greater than 255 characters.',
            'title.max' => 'Title may not be greater than 255 characters.',
            'subtitle.max' => 'Subtitle may not be greater than 255 characters.',
            'button_text.max' => 'Button text may not be greater than 255 characters.',
            'button_url.max' => 'Button URL may not be greater than 500 characters.',
            'button_external.boolean' => 'Button external must be true or false.',
            'image.image' => 'File must be an image.',
            'image.mimes' => 'Image must be a file of type: jpeg, png, jpg, gif, svg.',
            'image.max' => 'Image may not be greater than 20MB.',
            'background_color.max' => 'Background color must be a valid hex color code.',
            'text_color.max' => 'Text color must be a valid hex color code.',
            'is_active.required' => 'Active status is required.',
            'is_active.boolean' => 'Active status must be true or false.',
            'sort_order.required' => 'Sort order is required.',
            'sort_order.integer' => 'Sort order must be an integer.',
            'sort_order.min' => 'Sort order must be at least 0.',
            'meta_data.array' => 'Meta data must be an array.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'section_identifier' => 'Section Identifier',
            'template' => 'Template',
            'title' => 'Title',
            'subtitle' => 'Subtitle',
            'content' => 'Content',
            'button_text' => 'Button Text',
            'button_url' => 'Button URL',
            'button_external' => 'External Link',
            'image' => 'Image',
            'background_color' => 'Background Color',
            'text_color' => 'Text Color',
            'is_active' => 'Active Status',
            'sort_order' => 'Sort Order',
            'meta_data' => 'Meta Data',
        ];
    }
}
