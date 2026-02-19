<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends FormRequest
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
            'blog_category_id' => 'required|exists:blog_categories,id',
            'author_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blogs,slug,' . $this->route('blog')?->id,
            'short_body' => 'required|string|min:10|max:150',
            'long_body' => 'required|string|min:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string',
            // Marketing Automation fields
            'funnel_fase' => 'nullable|in:interesseer,overtuig,activeer,inspireer',
            'marketing_persona_id' => 'nullable|exists:marketing_personas,id',
            'content_type_id' => 'nullable|exists:content_types,id',
            'primary_keyword' => 'nullable|string|max:255',
            'secondary_keywords' => 'nullable|array',
            'secondary_keywords.*' => 'string|max:255',
            'ai_briefing' => 'nullable|string',
        ];

        // For update, make image optional
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
        }

        return $rules;
    }

    /**
     * Get the validation messages that apply to the request.
     */
    public function messages(): array
    {
        return [
            'blog_category_id.required' => 'Blog category is required.',
            'blog_category_id.exists' => 'Selected blog category does not exist.',
            'author_id.required' => 'Author is required.',
            'author_id.exists' => 'Selected author does not exist.',
            'title.required' => 'Title is required.',
            'title.max' => 'Title may not be greater than 255 characters.',
            'slug.required' => 'Slug is required.',
            'slug.unique' => 'This slug is already taken.',
            'short_body.required' => 'Short description is required.',
            'short_body.min' => 'Short description must be at least 10 characters.',
            'long_body.required' => 'Content is required.',
            'long_body.min' => 'Content must be at least 20 characters.',
            'image.image' => 'File must be an image.',
            'image.mimes' => 'Image must be a file of type: jpeg, png, jpg, gif, webp.',
            'image.max' => 'Image may not be greater than 2MB.',

        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'blog_category_id' => 'Blog category',
            'author_id' => 'Author',
            'title' => 'Title',
            'slug' => 'Slug',
            'short_body' => 'Short description',
            'long_body' => 'Content',
            'image' => 'Image',
            'is_active' => 'Active status',
            'is_featured' => 'Featured status',
        ];
    }
}