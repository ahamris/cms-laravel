<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SessionRegistrationRequest extends FormRequest
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
        $rules = [
            'live_session_id' => 'required|exists:live_sessions,id',
            'organization' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'marketing_consent' => 'boolean',
            'status' => 'required|in:registered,attended,no_show,cancelled',
            'notes' => 'nullable|string|max:2000',
        ];

        // For updates, check unique constraint excluding current record
        if ($this->route('session_registration')) {
            $rules['email'] = 'required|email|max:255|unique:session_registrations,email,' . 
                             $this->route('session_registration')->id . ',id,live_session_id,' . 
                             $this->input('live_session_id');
        } else {
            $rules['email'] = 'required|email|max:255|unique:session_registrations,email,NULL,id,live_session_id,' . 
                             $this->input('live_session_id');
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'live_session_id.required' => 'De live sessie is verplicht.',
            'live_session_id.exists' => 'De geselecteerde live sessie bestaat niet.',
            'organization.required' => 'De organisatie is verplicht.',
            'organization.max' => 'De organisatie mag maximaal 255 karakters bevatten.',
            'name.required' => 'De naam is verplicht.',
            'name.max' => 'De naam mag maximaal 255 karakters bevatten.',
            'email.required' => 'Het e-mailadres is verplicht.',
            'email.email' => 'Voer een geldig e-mailadres in.',
            'email.max' => 'Het e-mailadres mag maximaal 255 karakters bevatten.',
            'email.unique' => 'Dit e-mailadres is al geregistreerd voor deze sessie.',
            'status.required' => 'De status is verplicht.',
            'status.in' => 'Selecteer een geldige status.',
            'notes.max' => 'De notities mogen maximaal 2000 karakters bevatten.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'live_session_id' => 'live sessie',
            'organization' => 'organisatie',
            'name' => 'naam',
            'email' => 'e-mailadres',
            'marketing_consent' => 'marketing toestemming',
            'status' => 'status',
            'notes' => 'notities',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'marketing_consent' => $this->boolean('marketing_consent'),
        ]);
    }
}
