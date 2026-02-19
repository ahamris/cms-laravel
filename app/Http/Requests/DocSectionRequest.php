<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocSectionRequest extends FormRequest
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
        $sectionId = null;
        
        if ($this->route('doc_section')) {
            $section = $this->route('doc_section');
            $sectionId = is_object($section) ? $section->id : $section;
        }

        return [
            'doc_version_id' => 'required|exists:doc_versions,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
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
            'doc_version_id.required' => 'The version field is required.',
            'doc_version_id.exists' => 'The selected version does not exist.',
            'title.required' => 'The title field is required.',
            'sort_order.integer' => 'The sort order must be an integer.',
            'sort_order.min' => 'The sort order must be at least 0.',
        ];
    }
}
