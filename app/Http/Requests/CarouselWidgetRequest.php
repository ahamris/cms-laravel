<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarouselWidgetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $carouselWidgetId = $this->route('carousel_widget') ? $this->route('carousel_widget')->id : null;

        return [
            'name' => 'required|string|max:255',
            'identifier' => 'required|string|max:255|unique:carousel_widgets,identifier,' . $carouselWidgetId,
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'data_source' => 'required|in:blog,custom',
            'blog_category_id' => 'nullable|exists:blog_categories,id',
            'items_per_row' => 'required|integer|min:1|max:12',
            'total_items' => 'required|integer|min:1|max:50',
            'show_arrows' => 'boolean',
            'show_dots' => 'boolean',
            'show_author' => 'boolean',
            'autoplay' => 'boolean',
            'autoplay_speed' => 'required_if:autoplay,true|nullable|integer|min:1000|max:10000',
            'infinite_loop' => 'boolean',
            'show_view_all_button' => 'boolean',
            'view_all_title' => 'nullable|string|max:255',
            'view_all_description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'settings' => 'nullable|array',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'identifier.required' => 'Identifier is required.',
            'identifier.unique' => 'This identifier is already taken.',
            'data_source.required' => 'Data source is required.',
            'data_source.in' => 'Data source must be either blog or custom.',
            'items_per_row.required' => 'Items per row is required.',
            'items_per_row.min' => 'Items per row must be at least 1.',
            'items_per_row.max' => 'Items per row may not be greater than 12.',
            'total_items.required' => 'Total items is required.',
            'total_items.min' => 'Total items must be at least 1.',
            'total_items.max' => 'Total items may not be greater than 50.',
            'autoplay_speed.required_if' => 'Autoplay speed is required when autoplay is enabled.',
            'autoplay_speed.min' => 'Autoplay speed must be at least 1000 milliseconds.',
            'autoplay_speed.max' => 'Autoplay speed may not be greater than 10000 milliseconds.',
            'blog_category_id.exists' => 'Selected blog category does not exist.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'name',
            'identifier' => 'identifier',
            'title' => 'title',
            'description' => 'description',
            'data_source' => 'data source',
            'blog_category_id' => 'blog category',
            'items_per_row' => 'items per row',
            'total_items' => 'total items',
            'show_arrows' => 'show arrows',
            'show_dots' => 'show dots',
            'autoplay' => 'autoplay',
            'autoplay_speed' => 'autoplay speed',
            'infinite_loop' => 'infinite loop',
            'is_active' => 'active status',
            'sort_order' => 'sort order',
        ];
    }
}
