<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionRequest extends FormRequest
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
            // Contact Information
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'country_code' => 'nullable|string|max:10',
            
            // Demo Request Details
            'product_interest' => 'nullable|string|max:255',
            'message' => 'nullable|string|max:2000',
            'topic' => 'nullable|string|max:2000',
            'preferred_contact_method' => 'nullable|in:email,phone,both,call,query',
            'preferred_demo_date' => 'nullable|date|after:yesterday',
            'preferred_demo_time' => 'nullable|string|max:50',
            'scheduled_date' => 'nullable|date|after:yesterday',
            'scheduled_time' => 'nullable|string|max:50',
            
            // Business Information
            'company_size' => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            
            // Application Status
            'status' => 'nullable|in:new,contacted,demo_scheduled,demo_completed,converted,rejected',
            'admin_notes' => 'nullable|string|max:2000',
            
            // Source tracking
            'source' => 'nullable|string|max:255',
            'utm_source' => 'nullable|string|max:255',
            'utm_medium' => 'nullable|string|max:255',
            'utm_campaign' => 'nullable|string|max:255',
            
            // Flags
            'is_active' => 'nullable|boolean',
            'newsletter_consent' => 'nullable|boolean',
            'marketing_consent' => 'nullable|boolean',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'first_name' => 'first name',
            'last_name' => 'last name',
            'email' => 'email address',
            'phone' => 'phone number',
            'company_name' => 'company name',
            'job_title' => 'job title',
            'country_code' => 'country code',
            'product_interest' => 'product interest',
            'message' => 'message',
            'topic' => 'topic',
            'preferred_contact_method' => 'preferred contact method',
            'preferred_demo_date' => 'preferred demo date',
            'preferred_demo_time' => 'preferred demo time',
            'company_size' => 'company size',
            'industry' => 'industry',
            'website' => 'website',
            'status' => 'status',
            'admin_notes' => 'admin notes',
            'source' => 'source',
            'utm_source' => 'UTM source',
            'utm_medium' => 'UTM medium',
            'utm_campaign' => 'UTM campaign',
            'is_active' => 'active status',
            'newsletter_consent' => 'newsletter consent',
            'marketing_consent' => 'marketing consent',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'The first name field is required.',
            'last_name.required' => 'The last name field is required.',
            'email.required' => 'The email address field is required.',
            'email.email' => 'Please enter a valid email address.',
            'preferred_demo_date.after' => 'The preferred demo date must be a future date.',
            'website.url' => 'Please enter a valid website URL.',
            'preferred_contact_method.in' => 'Please select a valid contact method.',
            'preferred_demo_time.in' => 'Please select a valid demo time preference.',
            'company_size.in' => 'Please select a valid company size.',
            'status.in' => 'Please select a valid status.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Set default values if not provided
        if (!$this->has('preferred_contact_method')) {
            $this->merge(['preferred_contact_method' => 'email']);
        }

        if (!$this->has('source')) {
            $this->merge(['source' => 'website']);
        }

        if (!$this->has('status')) {
            $this->merge(['status' => 'new']);
        }

        // Convert boolean strings to actual booleans
        $this->merge([
            'is_active' => $this->boolean('is_active', true),
            'newsletter_consent' => $this->boolean('newsletter_consent', false),
            'marketing_consent' => $this->boolean('marketing_consent', false),
        ]);
    }
}
