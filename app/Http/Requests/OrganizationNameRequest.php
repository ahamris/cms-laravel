<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrganizationNameRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $organizationNameId = $this->route('organization_name')?->id;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('organization_names', 'name')->ignore($organizationNameId),
            ],
            'abbreviation' => [
                'nullable',
                'string',
                'max:10',
                Rule::unique('organization_names', 'abbreviation')->ignore($organizationNameId),
            ],
            'address' => 'nullable|string|max:1000',
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('organization_names', 'email')->ignore($organizationNameId),
            ],
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Organization name is required.',
            'name.unique' => 'This organization name already exists.',
            'abbreviation.unique' => 'This abbreviation is already in use.',
            'abbreviation.max' => 'Abbreviation cannot be longer than 10 characters.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already in use.',
            'address.max' => 'Address cannot be longer than 1000 characters.',
            'sort_order.min' => 'Sort order must be a positive number.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'organization name',
            'abbreviation' => 'abbreviation',
            'address' => 'address',
            'email' => 'email address',
            'is_active' => 'active status',
            'sort_order' => 'sort order',
        ];
    }
}
