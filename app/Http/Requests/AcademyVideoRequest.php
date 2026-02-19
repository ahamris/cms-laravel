<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AcademyVideoRequest extends FormRequest
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
        $video = $this->route('academy_video');
        $slugUnique = 'unique:academy_videos,slug,' . ($video?->id ?? 'NULL');

        $rules = [
            'academy_category_id' => 'required|exists:academy_categories,id',
            'academy_chapter_id' => 'nullable|exists:academy_chapters,id',
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|' . $slugUnique,
            'description' => 'nullable|string|max:5000',
            'video_url' => 'nullable|url|max:500',
            'duration_seconds' => 'nullable|integer|min:0',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'required|boolean',
        ];

        // Video: optional on both create and update (allows "plain documentation" with no video)
        $rules['video'] = 'nullable|file|mimes:mp4,mov,webm,ogg|max:512000'; // max 512MB
        $rules['thumbnail'] = 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120'; // max 5MB

        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'academy_category_id' => 'Category',
            'academy_chapter_id' => 'Chapter',
            'title' => 'Title',
            'slug' => 'Slug',
            'description' => 'Description',
            'video_path' => 'Video file',
            'video_url' => 'Video URL',
            'thumbnail_path' => 'Thumbnail',
            'duration_seconds' => 'Duration (seconds)',
            'sort_order' => 'Sort order',
            'is_active' => 'Active status',
        ];
    }
}
