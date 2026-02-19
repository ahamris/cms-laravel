<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogCategoryRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_categories,slug,' . $this->route('blog_category')?->id,
            'description' => 'required|string|min:10',
            'color' => 'required|string|max:16|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'name.max' => 'Name may not be greater than 255 characters.',
            'slug.required' => 'Slug is required.',
            'slug.unique' => 'This slug is already taken.',
            'description.required' => 'Description is required.',
            'description.min' => 'Description must be at least 10 characters.',
            'color.required' => 'Color is required.',
            'color.regex' => 'Color must be a valid hex color (e.g., #FF0000).',
            'is_active.boolean' => 'Active status must be true or false.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'Name',
            'slug' => 'Slug',
            'description' => 'Description',
            'color' => 'Color',
            'is_active' => 'Active status',
        ];
    }
}