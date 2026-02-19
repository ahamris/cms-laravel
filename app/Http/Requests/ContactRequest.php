<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            // Core identity
            'organization_name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:contacts,slug,' . $this->route('contact')?->id,
            'alias' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',

            // Registration / tax
            'chamber_of_commerce' => 'nullable|string|max:255',
            'tax_number' => 'nullable|string|max:255',

            // Invoicing
            'invoice_email' => 'nullable|email|max:255',
            'invoice_email_cc' => 'nullable|email|max:255',
            'invoice_email_bcc' => 'nullable|email|max:255',
            'payment_due_days' => 'required|integer|min:0|max:365',
            'currency' => 'required|string|size:3',
            'preferred_language' => 'required|string|max:5',

            // Billing address
            'billing_attention' => 'nullable|string|max:255',
            'billing_street' => 'nullable|string|max:255',
            'billing_house_number' => 'nullable|string|max:255',
            'billing_zipcode' => 'nullable|string|max:32',
            'billing_city' => 'nullable|string|max:255',
            'billing_region' => 'nullable|string|max:255',
            'billing_country' => 'nullable|string|size:2',

            // Shipping address
            'shipping_attention' => 'nullable|string|max:255',
            'shipping_street' => 'nullable|string|max:255',
            'shipping_house_number' => 'nullable|string|max:255',
            'shipping_zipcode' => 'nullable|string|max:32',
            'shipping_city' => 'nullable|string|max:255',
            'shipping_region' => 'nullable|string|max:255',
            'shipping_country' => 'nullable|string|size:2',

            // Banking
            'iban' => 'nullable|string|max:34',
            'bic' => 'nullable|string|max:11',

            // Flags
            'is_customer' => 'boolean',
            'is_supplier' => 'boolean',
            'is_active' => 'boolean',

            // Notes
            'notes' => 'nullable|string',
        ];

        return $rules;
    }

    /**
     * Get the validation messages that apply to the request.
     */
    public function messages(): array
    {
        return [
            'organization_name.required' => 'Organization name is required.',
            'organization_name.max' => 'Organization name may not be greater than 255 characters.',
            'slug.unique' => 'This slug is already taken.',
            'email.email' => 'Email must be a valid email address.',
            'invoice_email.email' => 'Invoice email must be a valid email address.',
            'invoice_email_cc.email' => 'Invoice CC email must be a valid email address.',
            'invoice_email_bcc.email' => 'Invoice BCC email must be a valid email address.',
            'website.url' => 'Website must be a valid URL.',
            'payment_due_days.required' => 'Payment due days is required.',
            'payment_due_days.integer' => 'Payment due days must be a number.',
            'payment_due_days.min' => 'Payment due days must be at least 0.',
            'payment_due_days.max' => 'Payment due days may not be greater than 365.',
            'currency.required' => 'Currency is required.',
            'currency.size' => 'Currency must be exactly 3 characters.',
            'preferred_language.required' => 'Preferred language is required.',
            'billing_country.size' => 'Billing country must be exactly 2 characters.',
            'shipping_country.size' => 'Shipping country must be exactly 2 characters.',
            'iban.max' => 'IBAN may not be greater than 34 characters.',
            'bic.max' => 'BIC may not be greater than 11 characters.',

        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'organization_name' => 'Organization name',
            'slug' => 'Slug',
            'alias' => 'Alias',
            'email' => 'Email',
            'phone' => 'Phone',
            'website' => 'Website',
            'chamber_of_commerce' => 'Chamber of Commerce',
            'tax_number' => 'Tax number',
            'invoice_email' => 'Invoice email',
            'invoice_email_cc' => 'Invoice CC email',
            'invoice_email_bcc' => 'Invoice BCC email',
            'payment_due_days' => 'Payment due days',
            'currency' => 'Currency',
            'preferred_language' => 'Preferred language',
            'billing_attention' => 'Billing attention',
            'billing_street' => 'Billing street',
            'billing_house_number' => 'Billing house number',
            'billing_zipcode' => 'Billing zipcode',
            'billing_city' => 'Billing city',
            'billing_region' => 'Billing region',
            'billing_country' => 'Billing country',
            'shipping_attention' => 'Shipping attention',
            'shipping_street' => 'Shipping street',
            'shipping_house_number' => 'Shipping house number',
            'shipping_zipcode' => 'Shipping zipcode',
            'shipping_city' => 'Shipping city',
            'shipping_region' => 'Shipping region',
            'shipping_country' => 'Shipping country',
            'iban' => 'IBAN',
            'bic' => 'BIC',
            'is_customer' => 'Customer status',
            'is_supplier' => 'Supplier status',
            'is_active' => 'Active status',
            'notes' => 'Notes',
        ];
    }
}
