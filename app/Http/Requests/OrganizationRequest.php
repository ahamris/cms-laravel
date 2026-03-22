<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrganizationRequest extends FormRequest
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
        $rules = [
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,gif,webp,svg|max:20480',
        ];
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['remove_logo'] = 'nullable|in:1';
        }
        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'Name',
            'logo' => 'Logo',
        ];
    }
}
