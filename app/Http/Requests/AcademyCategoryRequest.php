<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AcademyCategoryRequest extends FormRequest
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
        $category = $this->route('academy_category');

        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:academy_categories,slug,' . ($category?->id ?? 'NULL'),
            'description' => 'nullable|string|max:2000',
            'image' => 'nullable|image|max:8192', // 8MB Max
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'required|boolean',
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
            'sort_order' => 'Sort order',
            'is_active' => 'Active status',
        ];
    }
}
