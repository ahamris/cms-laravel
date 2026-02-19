<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StaticPageRequest extends FormRequest
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
            'body' => 'nullable|string',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'keywords' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'faqs' => 'nullable|array',
            'faqs.*.question' => 'required_with:faqs|string|max:500',
            'faqs.*.answer' => 'required_with:faqs|string|min:10',
            'faqs.*.is_active' => 'nullable|boolean',
        ];

        // For update requests, make title unique except for current record
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['title'] .= '|unique:static_pages,title,' . $this->route('static_page')->id;
        } else {
            $rules['title'] .= '|unique:static_pages,title';
        }

        // FAQ validation rules for individual FAQ operations
        if ($this->has('question') || $this->has('answer')) {
            $rules['question'] = 'required|string|max:500';
            $rules['answer'] = 'required|string|min:10';
            $rules['is_active'] = 'nullable|boolean';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The title field is required.',
            'title.unique' => 'A static page with this title already exists.',
            'meta_title.max' => 'The meta title may not be greater than 255 characters.',
            'meta_description.max' => 'The meta description may not be greater than 500 characters.',
            'keywords.max' => 'The keywords may not be greater than 1000 characters.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, svg.',
            'image.max' => 'The image may not be greater than 2048 kilobytes.',
        ];
    }
}
