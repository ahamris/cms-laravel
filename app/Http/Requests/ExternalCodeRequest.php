<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExternalCodeRequest extends FormRequest
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
        $externalCodeId = $this->route('external_code');
        $externalCodeId = $externalCodeId ? $externalCodeId->id : null;
        
        return [
            'name' => 'required|string|max:255|unique:external_codes,name,' . $externalCodeId,
            'content' => 'required|string',
            'injection_location' => 'nullable|string|in:header,body',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'name.unique' => 'This name is already taken.',
            'content.required' => 'Content is required.',
            'sort_order.integer' => 'Sort order must be a number.',
            'sort_order.min' => 'Sort order must be at least 0.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'Name',
            'content' => 'Content',
            'injection_location' => 'Injection Location',
            'sort_order' => 'Sort order',
            'is_active' => 'Active status',
        ];
    }
}
