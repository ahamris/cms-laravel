<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LegalRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:legal_pages,slug,' . $this->route('legal')?->id,
            'body' => 'required|string|min:10',
            'is_active' => 'required|boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'keywords' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'selected_call_actions' => 'nullable|array',
            'selected_call_actions.*' => 'integer|exists:call_actions,id',
            'faqs' => 'nullable|array',
            'faqs.*.question' => 'required_with:faqs|string|max:500',
            'faqs.*.answer' => 'required_with:faqs|string|min:10',
            'faqs.*.is_active' => 'nullable|boolean',
        ];

        // FAQ validation rules for individual FAQ operations
        if ($this->has('question') || $this->has('answer')) {
            $rules['question'] = 'required|string|max:500';
            $rules['answer'] = 'required|string|min:10';
            $rules['is_active'] = 'nullable|boolean';
        }

        return $rules;
    }

    /**
     * Get the validation messages that apply to the request.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Title is required.',
            'title.max' => 'Title may not be greater than 255 characters.',
            'slug.required' => 'Slug is required.',
            'slug.unique' => 'This slug is already taken.',
            'body.required' => 'Body content is required.',
            'body.min' => 'Body content must be at least 10 characters.',
            'is_active.required' => 'Active status is required.',
            'is_active.boolean' => 'Active status must be true or false.',
            'meta_title.max' => 'Meta title may not be greater than 255 characters.',
            'meta_description.max' => 'Meta description may not be greater than 500 characters.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'title' => 'Title',
            'slug' => 'Slug',
            'body' => 'Body content',
            'is_active' => 'Active status',
            'meta_title' => 'Meta title',
            'meta_description' => 'Meta description',
        ];
    }
}
