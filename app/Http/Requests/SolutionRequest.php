<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SolutionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Prepare list_items for validation: ensure each element is a string.
     */
    protected function prepareForValidation(): void
    {
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
            'anchor' => 'required|string|max:255|unique:solutions,anchor,'.$this->route('solution')?->id,
            'nav_title' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:solutions,slug,'.$this->route('solution')?->id,
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
            'is_active' => 'nullable|boolean',

            // SEO
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string',

            // Header configuration
            'button1_text' => 'nullable|string|max:255',
            'button1_url' => 'nullable|string|max:255',
            'button2_text' => 'nullable|string|max:255',
            'button2_url' => 'nullable|string|max:255',
            'show_buttons' => 'nullable|boolean',

            // Module activation
            'show_cta' => 'nullable|boolean',
            'show_news_articles' => 'nullable|boolean',

            // Modules relationship
            'modules' => 'nullable|array',
            'modules.*' => 'integer|exists:modules,id',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'anchor' => 'anchor ID',
            'nav_title' => 'navigation title',
            'title' => 'title',
            'slug' => 'slug',
            'subtitle' => 'subtitle',
            'short_body' => 'short body',
            'long_body' => 'long body',
            'list_items' => 'key features',
            'list_items.*' => 'key feature',
            'link_text' => 'link text',
            'testimonial_quote' => 'testimonial quote',
            'testimonial_author' => 'testimonial author',
            'testimonial_company' => 'testimonial company',
            'image' => 'image',
            'image_position' => 'image position',
            'sort_order' => 'sort order',
            'is_active' => 'active status',
            'meta_title' => 'meta title',
            'meta_description' => 'meta description',
            'meta_keywords' => 'meta keywords',
            'button1_text' => 'button 1 text',
            'button1_url' => 'button 1 URL',
            'button2_text' => 'button 2 text',
            'button2_url' => 'button 2 URL',
            'show_buttons' => 'show buttons',
            'show_cta' => 'show CTA',
            'show_news_articles' => 'show news articles',
            'modules' => 'modules',
            'modules.*' => 'module',
        ];
    }
}