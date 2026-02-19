<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        // Decode widget_config if it's a JSON string
        if ($this->has('widget_config')) {
            if (is_string($this->widget_config) && ! empty($this->widget_config)) {
                $decoded = json_decode($this->widget_config, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $this->merge(['widget_config' => $decoded]);
                } else {
                    $this->merge(['widget_config' => null]);
                }
            } elseif (empty($this->widget_config)) {
                $this->merge(['widget_config' => null]);
            }
        }

        // Handle toggle/checkbox boolean fields
        // Hidden input sends '1' or '0' as string
        $booleanFields = ['is_active', 'home_page', 'hide_header', 'hide_footer'];

        foreach ($booleanFields as $field) {
            $value = $this->input($field);

            // Handle array (if both checkbox and hidden input are sent - should not happen now but keep for safety)
            if (is_array($value)) {
                $value = in_array('1', $value) || in_array(1, $value) ? true : false;
            }
            // Handle string '1' or '0'
            elseif ($value === '1' || $value === 1 || $value === true || $value === 'true') {
                $value = true;
            }
            // Handle string '0' or false
            elseif ($value === '0' || $value === 0 || $value === false || $value === 'false' || $value === '') {
                $value = false;
            }
            // Field not present or null
            else {
                // Default based on field
                if ($field === 'is_active') {
                    $value = false;
                } elseif ($field === 'home_page') {
                    $value = false; // home_page must be boolean, not null
                } else {
                    $value = null;
                }
            }

            $this->merge([$field => $value]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $pageType = $this->input('page_type', 'static');
        $isStatic = $pageType === 'static';

        $designType = $this->input('design_type', 'general');
        $isCustom = $designType === 'custom';

        return [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug,'.$this->route('page')?->id,
            'page_type' => 'required|string|in:showcase,static',
            'design_type' => 'nullable|string|in:general,custom',
            'layout_type' => 'nullable|string|in:full-width,container,max-w-2xl,max-w-4xl,max-w-6xl,max-w-7xl',
            'header_block' => $isCustom ? 'nullable|string|max:255' : 'nullable',
            'footer_block' => $isCustom ? 'nullable|string|max:255' : 'nullable',
            'hide_header' => 'nullable|boolean',
            'hide_footer' => 'nullable|boolean',
            'widget_config' => 'nullable|array',
            'short_body' => $isStatic ? 'required|string|min:10' : 'nullable|string',
            'long_body' => $isStatic ? ['required', 'string', function ($attribute, $value, $fail) {
                // Allow empty string or null for long_body
                if ($value === null || $value === '') {
                    return;
                }
                // Strip HTML tags and check minimum length
                $textContent = strip_tags($value);
                $textContent = preg_replace('/\s+/', ' ', trim($textContent));
                if (strlen($textContent) < 10 && strlen($textContent) > 0) {
                    $fail('The long body must be at least 10 characters (excluding HTML tags).');
                }
            }] : 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_body' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
            'home_page' => 'nullable|boolean',

            // Marketing Automation fields (only for static pages)
            'funnel_fase' => 'nullable|string|in:interesseer,overtuig,activeer,inspireer',
            'marketing_persona_id' => 'nullable|exists:marketing_personas,id',
            'content_type_id' => 'nullable|exists:content_types,id',
            'primary_keyword' => 'nullable|string|max:255',
            'secondary_keywords' => 'nullable|array',
            'secondary_keywords.*' => 'string|max:255',
            'ai_briefing' => 'nullable|string',
            'seo_analysis' => 'nullable|array',
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
            'image.mimes' => 'Image must be a file of type: jpeg, png, jpg, gif, svg.',
            'image.max' => 'Image may not be greater than 2048 kilobytes.',
            'icon.max' => 'Icon may not be greater than 255 characters.',
            'is_active.required' => 'Active status is required.',
            'is_active.boolean' => 'Active status must be true or false.',

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
            'page_type' => 'Page type',
            'widget_config' => 'Widget configuration',
            'short_body' => 'Short body',
            'long_body' => 'Long body',
            'meta_title' => 'Meta title',
            'meta_body' => 'Meta body',
            'image' => 'Image',
            'icon' => 'Icon',
            'is_active' => 'Active status',

            // Marketing Automation attributes
            'funnel_fase' => 'Funnel phase',
            'marketing_persona_id' => 'Marketing persona',
            'content_type_id' => 'Content type',
            'primary_keyword' => 'Primary keyword',
            'secondary_keywords' => 'Secondary keywords',
            'ai_briefing' => 'AI briefing',
            'seo_analysis' => 'SEO analysis',
        ];
    }
}
