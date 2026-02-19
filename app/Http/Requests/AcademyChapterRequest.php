<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AcademyChapterRequest extends FormRequest
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
            'academy_category_id' => 'required|exists:academy_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'sort_order' => 'nullable|integer|min:0',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'academy_category_id' => 'Category',
            'name' => 'Name',
            'description' => 'Description',
            'sort_order' => 'Sort order',
        ];
    }
}
