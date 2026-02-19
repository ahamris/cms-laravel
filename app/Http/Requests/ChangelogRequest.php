<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangelogRequest extends FormRequest
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
        $changelogId = null;
        
        // Get the changelog ID for unique validation
        if ($this->route('changelog')) {
            $changelog = $this->route('changelog');
            $changelogId = is_object($changelog) ? $changelog->id : $changelog;
        }

        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'content' => 'nullable|string',
            'video_url' => 'nullable|url|max:500',
            'date' => 'required|date',
            'status' => 'required|in:new,improved,fixed,api',
            'slug' => 'nullable|string|max:255|unique:changelogs,slug,'.$changelogId,
            'features' => 'nullable|array',
            'features.*' => 'nullable|string|max:500',
            'steps' => 'nullable|array',
            'steps.*' => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The title field is required.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'description.required' => 'The description field is required.',
            'description.max' => 'The description may not be greater than 1000 characters.',
            'date.required' => 'The date field is required.',
            'date.date' => 'The date must be a valid date.',
            'status.required' => 'The status field is required.',
            'status.in' => 'The selected status is invalid.',
            'slug.unique' => 'This slug is already taken.',
            'features.*.string' => 'Each feature must be a string.',
            'features.*.max' => 'Each feature may not be greater than 500 characters.',
            'steps.*.string' => 'Each step must be a string.',
            'steps.*.max' => 'Each step may not be greater than 500 characters.',
            'sort_order.integer' => 'The sort order must be an integer.',
            'sort_order.min' => 'The sort order must be at least 0.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'title' => 'title',
            'description' => 'description',
            'content' => 'content',
            'date' => 'date',
            'status' => 'status',
            'slug' => 'slug',
            'features' => 'features',
            'steps' => 'steps',
            'is_active' => 'active status',
            'sort_order' => 'sort order',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert features and steps from comma-separated strings to arrays if needed
        if ($this->has('features') && is_string($this->features)) {
            $this->merge([
                'features' => array_filter(array_map('trim', explode(',', $this->features))),
            ]);
        }

        if ($this->has('steps') && is_string($this->steps)) {
            $this->merge([
                'steps' => array_filter(array_map('trim', explode(',', $this->steps))),
            ]);
        }

        // Handle empty arrays for features and steps
        if ($this->has('features') && is_array($this->features)) {
            $this->merge([
                'features' => array_filter($this->features, function($value) {
                    return !empty(trim($value));
                }),
            ]);
        }

        if ($this->has('steps') && is_array($this->steps)) {
            $this->merge([
                'steps' => array_filter($this->steps, function($value) {
                    return !empty(trim($value));
                }),
            ]);
        }

        // Ensure is_active is boolean
        if ($this->has('is_active')) {
            $this->merge([
                'is_active' => (bool) $this->is_active,
            ]);
        }
    }
}
