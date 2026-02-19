<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PresenterRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:2000',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'email' => 'nullable|email|max:255',
            'linkedin_url' => 'nullable|url|max:500',
            'twitter_url' => 'nullable|url|max:500',
            'company' => 'nullable|string|max:255',
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
            'name.required' => 'De naam is verplicht.',
            'name.max' => 'De naam mag maximaal 255 karakters bevatten.',
            'title.max' => 'De functietitel mag maximaal 255 karakters bevatten.',
            'bio.max' => 'De biografie mag maximaal 2000 karakters bevatten.',
            'avatar.image' => 'Het avatar moet een afbeelding zijn.',
            'avatar.mimes' => 'Het avatar moet een jpeg, png, jpg of gif bestand zijn.',
            'avatar.max' => 'Het avatar mag maximaal 2MB groot zijn.',
            'email.email' => 'Voer een geldig e-mailadres in.',
            'email.max' => 'Het e-mailadres mag maximaal 255 karakters bevatten.',
            'linkedin_url.url' => 'Voer een geldige LinkedIn URL in.',
            'linkedin_url.max' => 'De LinkedIn URL mag maximaal 500 karakters bevatten.',
            'twitter_url.url' => 'Voer een geldige Twitter URL in.',
            'twitter_url.max' => 'De Twitter URL mag maximaal 500 karakters bevatten.',
            'company.max' => 'De bedrijfsnaam mag maximaal 255 karakters bevatten.',
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
            'name' => 'naam',
            'title' => 'functietitel',
            'bio' => 'biografie',
            'avatar' => 'avatar',
            'email' => 'e-mailadres',
            'linkedin_url' => 'LinkedIn URL',
            'twitter_url' => 'Twitter URL',
            'company' => 'bedrijf',
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
            'sort_order' => $this->integer('sort_order', 0),
        ]);
    }
}
