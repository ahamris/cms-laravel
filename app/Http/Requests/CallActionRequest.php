<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CallActionRequest extends FormRequest
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
        $callActionId = $this->route('call_action')?->id;

        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'primary_button_text' => 'nullable|string|max:255',
            'primary_button_url' => 'nullable|string|max:255',
            'primary_button_external' => 'boolean',
            'secondary_button_text' => 'nullable|string|max:255',
            'secondary_button_url' => 'nullable|string|max:255',
            'secondary_button_external' => 'boolean',
            'background_color' => 'required|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'text_color' => 'required|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'section_identifier' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9_]+$/',
                Rule::unique('call_actions', 'section_identifier')->ignore($callActionId),
            ],
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'title' => 'title',
            'content' => 'content',
            'primary_button_text' => 'primary button text',
            'primary_button_url' => 'primary button URL',
            'primary_button_external' => 'primary button external',
            'secondary_button_text' => 'secondary button text',
            'secondary_button_url' => 'secondary button URL',
            'secondary_button_external' => 'secondary button external',
            'background_color' => 'background color',
            'text_color' => 'text color',
            'section_identifier' => 'section identifier',
            'is_active' => 'active status',
            'sort_order' => 'sort order',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'section_identifier.regex' => 'The section identifier may only contain lowercase letters, numbers, and underscores.',
            'background_color.regex' => 'The background color must be a valid hex color code (e.g., #1e40af).',
            'text_color.regex' => 'The text color must be a valid hex color code (e.g., #ffffff).',
        ];
    }
}
