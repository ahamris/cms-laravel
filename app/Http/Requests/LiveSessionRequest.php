<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LiveSessionRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'content' => 'nullable|string',
            'slug' => 'nullable|string|max:255|unique:live_sessions,slug',
            'session_date' => 'required|date|after:now',
            'duration_minutes' => 'required|integer|min:15|max:480',
            'max_participants' => 'required|integer|min:1|max:1000',
            'status' => 'required|in:upcoming,live,completed,cancelled',
            'type' => 'required|in:introduction,webinar,workshop,qa',
            'meeting_url' => 'nullable|url|max:500',
            'recording_url' => 'nullable|url|max:500',
            'icon' => 'nullable|string|max:100',
            'color' => 'required|string|max:50',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'presenters' => 'nullable|array',
            'presenters.*' => 'exists:presenters,id',
        ];

        // For updates, ignore current record in slug uniqueness
        if ($this->route('live_session')) {
            $rules['slug'] = 'nullable|string|max:255|unique:live_sessions,slug,' . $this->route('live_session')->id;
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'De titel is verplicht.',
            'title.max' => 'De titel mag maximaal 255 karakters bevatten.',
            'description.max' => 'De beschrijving mag maximaal 1000 karakters bevatten.',
            'slug.unique' => 'Deze slug is al in gebruik.',
            'session_date.required' => 'De sessiedatum is verplicht.',
            'session_date.date' => 'Voer een geldige datum in.',
            'session_date.after' => 'De sessiedatum moet in de toekomst liggen.',
            'duration_minutes.required' => 'De duur is verplicht.',
            'duration_minutes.integer' => 'De duur moet een geheel getal zijn.',
            'duration_minutes.min' => 'De duur moet minimaal 15 minuten zijn.',
            'duration_minutes.max' => 'De duur mag maximaal 480 minuten (8 uur) zijn.',
            'max_participants.required' => 'Het maximum aantal deelnemers is verplicht.',
            'max_participants.integer' => 'Het maximum aantal deelnemers moet een geheel getal zijn.',
            'max_participants.min' => 'Er moet minimaal 1 deelnemer mogelijk zijn.',
            'max_participants.max' => 'Het maximum aantal deelnemers mag niet meer dan 1000 zijn.',
            'status.required' => 'De status is verplicht.',
            'status.in' => 'Selecteer een geldige status.',
            'type.required' => 'Het type sessie is verplicht.',
            'type.in' => 'Selecteer een geldig sessietype.',
            'meeting_url.url' => 'Voer een geldige meeting URL in.',
            'recording_url.url' => 'Voer een geldige opname URL in.',
            'color.required' => 'De kleur is verplicht.',
            'presenters.*.exists' => 'Een of meer geselecteerde presenters bestaan niet.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'title' => 'titel',
            'description' => 'beschrijving',
            'content' => 'inhoud',
            'slug' => 'slug',
            'session_date' => 'sessiedatum',
            'duration_minutes' => 'duur',
            'max_participants' => 'maximum deelnemers',
            'status' => 'status',
            'type' => 'type',
            'meeting_url' => 'meeting URL',
            'recording_url' => 'opname URL',
            'icon' => 'icoon',
            'color' => 'kleur',
            'is_featured' => 'uitgelicht',
            'is_active' => 'actief',
            'sort_order' => 'sorteervolgorde',
            'presenters' => 'presenters',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_featured' => $this->boolean('is_featured'),
            'is_active' => $this->boolean('is_active', true),
            'sort_order' => $this->integer('sort_order', 0),
        ]);
    }
}
