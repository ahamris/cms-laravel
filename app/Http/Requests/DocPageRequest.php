<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocPageRequest extends FormRequest
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
        $pageId = null;
        
        if ($this->route('doc_page')) {
            $page = $this->route('doc_page');
            $pageId = is_object($page) ? $page->id : $page;
        }

        return [
            'doc_section_id' => 'required|exists:doc_sections,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
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
            'doc_section_id.required' => 'The section field is required.',
            'doc_section_id.exists' => 'The selected section does not exist.',
            'title.required' => 'The title field is required.',
            'content.required' => 'The content field is required.',
            'sort_order.integer' => 'The sort order must be an integer.',
            'sort_order.min' => 'The sort order must be at least 0.',
        ];
    }
}
