<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PageBlockPresetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Decode blocks if it's a JSON string
        if ($this->has('blocks')) {
            if (is_string($this->blocks) && ! empty($this->blocks)) {
                $decoded = json_decode($this->blocks, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $this->merge(['blocks' => $decoded]);
                } else {
                    $this->merge(['blocks' => []]);
                }
            } elseif (empty($this->blocks)) {
                $this->merge(['blocks' => []]);
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|in:header,body',
            'blocks' => 'required|array',
            'is_active' => 'nullable|boolean',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'Name',
            'description' => 'Description',
            'type' => 'Type',
            'blocks' => 'Blocks',
            'is_active' => 'Active status',
        ];
    }
}
