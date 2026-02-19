<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AboutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Basic content
            'anchor' => 'required|string|max:255|unique:abouts,anchor,'.$this->route('about')?->id,
            'nav_title' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:abouts,slug,'.$this->route('about')?->id,
            'subtitle' => 'nullable|string',
            'short_body' => 'nullable|string',
            'long_body' => 'nullable|string',
            'list_items' => 'nullable|array',
            'list_items.*' => 'string',
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
            'is_active' => 'nullable|boolean',

            // SEO
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string',

            // Relationships
            'modules' => 'nullable|array',
            'modules.*' => 'exists:modules,id',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     */
    public function messages(): array
    {
        return [
            'anchor.required' => 'Anchor is required.',
            'anchor.unique' => 'This anchor is already taken.',
            'nav_title.required' => 'Navigation title is required.',
            'title.required' => 'Title is required.',
            'slug.unique' => 'This slug is already taken.',
            'image_position.in' => 'Image position must be either left or right.',
            'sort_order.integer' => 'Sort order must be a number.',
            'sort_order.min' => 'Sort order must be at least 0.',
            'image.image' => 'File must be an image.',
            'image.mimes' => 'Image must be a file of type: jpeg, png, jpg, gif, webp, svg.',
            'image.max' => 'Image may not be greater than 2MB.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'anchor' => 'Anchor',
            'nav_title' => 'Navigation title',
            'title' => 'Title',
            'slug' => 'Slug',
            'subtitle' => 'Subtitle',
            'short_body' => 'Short body',
            'long_body' => 'Long body',
            'list_items' => 'List items',
            'link_text' => 'Link text',
            'testimonial_quote' => 'Testimonial quote',
            'testimonial_author' => 'Testimonial author',
            'testimonial_company' => 'Testimonial company',
            'image' => 'Image',
            'image_position' => 'Image position',
            'sort_order' => 'Sort order',
            'is_active' => 'Active status',
            'meta_title' => 'Meta title',
            'meta_description' => 'Meta description',
            'meta_keywords' => 'Meta keywords',
        ];
    }
}
