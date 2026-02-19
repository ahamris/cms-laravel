<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocVersionRequest extends FormRequest
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
        $versionId = null;
        
        if ($this->route('doc_version')) {
            $version = $this->route('doc_version');
            $versionId = is_object($version) ? $version->id : $version;
        }

        return [
            'version' => 'required|string|max:50|unique:doc_versions,version,'.$versionId,
            'name' => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
            'is_default' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'version.required' => 'The version field is required.',
            'version.unique' => 'This version already exists.',
            'name.required' => 'The name field is required.',
            'sort_order.integer' => 'The sort order must be an integer.',
            'sort_order.min' => 'The sort order must be at least 0.',
        ];
    }
}
