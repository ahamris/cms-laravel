<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HomepageFaqRequest extends FormRequest
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
        return [
            'identifier' => 'required|string|max:255|alpha_dash',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.question' => 'required|string|max:255',
            'items.*.answer' => 'required|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'identifier.required' => 'The identifier field is required.',
            'identifier.alpha_dash' => 'The identifier may only contain letters, numbers, dashes and underscores.',
            'identifier.max' => 'The identifier may not be greater than 255 characters.',
            'items.required' => 'At least one FAQ item is required.',
            'items.min' => 'At least one FAQ item is required.',
            'items.*.question.required' => 'Each FAQ must have a question.',
            'items.*.question.max' => 'FAQ question may not exceed 255 characters.',
            'items.*.answer.required' => 'Each FAQ must have an answer.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'identifier' => 'identifier',
            'title' => 'title',
            'subtitle' => 'subtitle',
            'items' => 'FAQ items',
            'items.*.question' => 'question',
            'items.*.answer' => 'answer',
        ];
    }
}
