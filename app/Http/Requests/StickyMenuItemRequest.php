<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StickyMenuItemRequest extends FormRequest
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
        return [
            'title' => 'required|string|max:255',
            'icon' => 'required|string|max:255',
            'link' => 'required|string|max:500',
            'is_external' => 'boolean',
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
            'title.required' => 'De titel is verplicht.',
            'title.max' => 'De titel mag maximaal 255 karakters bevatten.',
            'icon.required' => 'Het icoon is verplicht.',
            'icon.max' => 'Het icoon mag maximaal 255 karakters bevatten.',
            'link.required' => 'De link is verplicht.',
            'link.max' => 'De link mag maximaal 500 karakters bevatten.',
            'sort_order.integer' => 'De sorteervolgorde moet een geheel getal zijn.',
            'sort_order.min' => 'De sorteervolgorde moet minimaal 0 zijn.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'title' => 'titel',
            'icon' => 'icoon',
            'link' => 'link',
            'is_external' => 'externe link',
            'is_active' => 'actief',
            'sort_order' => 'sorteervolgorde',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active', true),
            'is_external' => $this->boolean('is_external', false),
            'sort_order' => $this->integer('sort_order', 0),
        ]);
    }

    /**
     * Get the validated data and convert is_external to link_type.
     */
    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated($key, $default);

        // Convert is_external boolean to link_type string for database
        $validated['link_type'] = ($validated['is_external'] ?? false) ? 'external' : 'internal';
        unset($validated['is_external']);

        return $validated;
    }
}
