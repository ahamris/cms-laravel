<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormBuilderRequest extends FormRequest
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
        $formBuilderId = $this->route('form_builder') ? $this->route('form_builder')->id : null;

        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'identifier' => 'required|string|max:255|unique:form_builders,identifier,' . $formBuilderId,
            'fields' => 'nullable|json',
            'success_message' => 'nullable|string',
            'redirect_url' => 'nullable|url|max:255',
            'send_email_notification' => 'boolean',
            'notification_emails' => 'nullable|string',
            'submit_button_text' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'is_api_form' => 'boolean',
            'api_url' => 'nullable|required_if:is_api_form,true|url|max:500',
            'api_token' => 'nullable|required_if:is_api_form,true|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'title' => 'form title',
            'description' => 'description',
            'identifier' => 'identifier',
            'fields' => 'form fields',
            'success_message' => 'success message',
            'redirect_url' => 'redirect URL',
            'send_email_notification' => 'email notification',
            'notification_emails' => 'notification emails',
            'submit_button_text' => 'submit button text',
            'is_active' => 'active status',
            'is_api_form' => 'API form',
            'api_url' => 'API URL',
            'api_token' => 'API token',
            'sort_order' => 'sort order',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The form title is required.',
            'identifier.required' => 'The identifier is required.',
            'identifier.unique' => 'This identifier is already in use.',
            'redirect_url.url' => 'Please enter a valid URL.',
        ];
    }
}
