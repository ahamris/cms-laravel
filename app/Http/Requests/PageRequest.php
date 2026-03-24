<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\ElementType;

class PageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $value = $this->input('is_active');
        if (is_array($value)) {
            $value = in_array('1', $value) || in_array(1, $value);
        } elseif ($value === '1' || $value === 1 || $value === true || $value === 'true') {
            $value = true;
        } else {
            $value = false;
        }
        $this->merge(['is_active' => $value]);

        $template = $this->input('template');
        if ($template === null || $template === '') {
            $this->merge(['template' => config('page_templates.default', 'default')]);
        }

        foreach (['faq_element_id', 'cta_element_id'] as $key) {
            $v = $this->input($key);
            if ($v === '' || $v === null) {
                $this->merge([$key => null]);
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug,'.$this->route('page')?->id,
            'short_body' => 'required|string|min:10',
            'long_body' => ['required', 'string', function ($attribute, $value, $fail) {
                if ($value === null || $value === '') {
                    return;
                }
                $textContent = strip_tags($value);
                $textContent = preg_replace('/\s+/', ' ', trim($textContent));
                if (strlen($textContent) < 10 && strlen($textContent) > 0) {
                    $fail('The long body must be at least 10 characters (excluding HTML tags).');
                }
            }],
            'meta_title' => 'nullable|string|max:255',
            'meta_body' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:20480',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
            'template' => 'nullable|string|in:'.implode(',', array_keys(config('page_templates.templates', []))),

            // Marketing Automation fields
            'funnel_fase' => 'nullable|string|in:interesseer,overtuig,activeer,inspireer',
            'marketing_persona_id' => 'nullable|exists:marketing_personas,id',
            'content_type_id' => 'nullable|exists:content_types,id',
            'primary_keyword' => 'nullable|string|max:255',
            'secondary_keywords' => 'nullable|array',
            'secondary_keywords.*' => 'string|max:255',
            'ai_briefing' => 'nullable|string',
            'seo_analysis' => 'nullable|array',

            'faq_element_id' => [
                'nullable',
                'integer',
                Rule::exists('elements', 'id')->where(fn ($q) => $q->where('type', ElementType::Faq->value)),
            ],
            'cta_element_id' => [
                'nullable',
                'integer',
                Rule::exists('elements', 'id')->where(fn ($q) => $q->where('type', ElementType::Cta->value)),
            ],
        ];
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
            'short_body.required' => 'Short body is required.',
            'short_body.min' => 'Short body must be at least 10 characters.',
            'long_body.required' => 'Long body is required.',
            'long_body.min' => 'Long body must be at least 10 characters.',
            'meta_title.max' => 'Meta title may not be greater than 255 characters.',
            'image.image' => 'File must be an image.',
            'image.mimes' => 'Image must be a file of type: jpeg, png, jpg, gif, webp, svg.',
            'image.max' => 'Image may not be greater than 20 MB.',
            'icon.max' => 'Icon may not be greater than 255 characters.',
            'is_active.required' => 'Active status is required.',
            'is_active.boolean' => 'Active status must be true or false.',
            'template.in' => 'The selected template is invalid.',

            // Marketing Automation messages
            'funnel_fase.in' => 'Funnel phase must be one of: interesseer, overtuig, activeer, inspireer.',
            'marketing_persona_id.exists' => 'Selected marketing persona does not exist.',
            'content_type_id.exists' => 'Selected content type does not exist.',
            'primary_keyword.max' => 'Primary keyword may not be greater than 255 characters.',
            'secondary_keywords.array' => 'Secondary keywords must be an array.',
            'secondary_keywords.*.string' => 'Each secondary keyword must be a string.',
            'secondary_keywords.*.max' => 'Each secondary keyword may not be greater than 255 characters.',
            'ai_briefing.string' => 'AI briefing must be a string.',
            'seo_analysis.array' => 'SEO analysis must be an array.',
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
            'short_body' => 'Short body',
            'long_body' => 'Long body',
            'meta_title' => 'Meta title',
            'meta_body' => 'Meta body',
            'image' => 'Image',
            'icon' => 'Icon',
            'is_active' => 'Active status',
            'template' => 'Template',

            // Marketing Automation attributes
            'funnel_fase' => 'Funnel phase',
            'marketing_persona_id' => 'Marketing persona',
            'content_type_id' => 'Content type',
            'primary_keyword' => 'Primary keyword',
            'secondary_keywords' => 'Secondary keywords',
            'ai_briefing' => 'AI briefing',
            'seo_analysis' => 'SEO analysis',
            'faq_element_id' => 'FAQ element',
            'cta_element_id' => 'CTA element',
        ];
    }
}
