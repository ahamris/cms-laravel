<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PageComponentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled by middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tailwind_plus_id' => 'required|exists:tailwind_plus,id',
            'sort_order' => 'sometimes|integer|min:0',
            'is_active' => 'sometimes|boolean',
            'custom_config' => 'sometimes|array',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'tailwind_plus_id.required' => 'Please select a component.',
            'tailwind_plus_id.exists' => 'The selected component does not exist.',
            'sort_order.integer' => 'Sort order must be a number.',
            'sort_order.min' => 'Sort order must be at least 0.',
            'custom_config.array' => 'Custom configuration must be a valid array.',
        ];
    }
}
