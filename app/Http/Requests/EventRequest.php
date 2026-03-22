<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:events,slug,' . $this->route('event')?->id,
            'short_body' => 'required|string|min:10',
            'long_body' => 'required|string|min:20',
            'description' => 'nullable|string|max:500',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'location' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'price' => 'nullable|numeric|min:0',
            'registration_url' => 'nullable|url',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
            'is_active' => 'required|boolean',
        ];

        // For update, make images optional
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['cover_image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:20480';
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:20480';
        }

        return $rules;
    }

    /**
     * Get the validation messages that apply to the request.
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'Event organizer is required.',
            'user_id.exists' => 'Selected organizer does not exist.',
            'title.required' => 'Title is required.',
            'title.max' => 'Title may not be greater than 255 characters.',
            'slug.required' => 'Slug is required.',
            'slug.unique' => 'This slug is already taken.',
            'short_body.required' => 'Short description is required.',
            'short_body.min' => 'Short description must be at least 10 characters.',
            'long_body.required' => 'Content is required.',
            'long_body.min' => 'Content must be at least 20 characters.',
            'description.max' => 'Description may not be greater than 500 characters.',
            'start_date.required' => 'Start date is required.',
            'start_date.date' => 'Start date must be a valid date.',
            'end_date.required' => 'End date is required.',
            'end_date.date' => 'End date must be a valid date.',
            'end_date.after_or_equal' => 'End date must be after or equal to start date.',
            'start_time.date_format' => 'Start time must be in HH:MM format.',
            'end_time.date_format' => 'End time must be in HH:MM format.',
            'end_time.after' => 'End time must be after start time.',
            'location.required' => 'Location is required.',
            'location.max' => 'Location may not be greater than 255 characters.',
            'address.max' => 'Address may not be greater than 500 characters.',
            'price.numeric' => 'Price must be a number.',
            'price.min' => 'Price must be at least 0.',
            'registration_url.url' => 'Registration URL must be a valid URL.',
            'cover_image.image' => 'Cover image must be an image.',
            'cover_image.mimes' => 'Cover image must be a file of type: jpeg, png, jpg, gif, webp.',
            'cover_image.max' => 'Cover image may not be greater than 20MB.',
            'image.image' => 'Image must be an image.',
            'image.mimes' => 'Image must be a file of type: jpeg, png, jpg, gif, webp.',
            'image.max' => 'Image may not be greater than 20MB.',
            'is_active.required' => 'Active status is required.',
            'is_active.boolean' => 'Active status must be true or false.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'user_id' => 'Event organizer',
            'title' => 'Title',
            'slug' => 'Slug',
            'short_body' => 'Short description',
            'long_body' => 'Content',
            'description' => 'Description',
            'start_date' => 'Start date',
            'end_date' => 'End date',
            'start_time' => 'Start time',
            'end_time' => 'End time',
            'location' => 'Location',
            'address' => 'Address',
            'price' => 'Price',
            'registration_url' => 'Registration URL',
            'cover_image' => 'Cover image',
            'image' => 'Image',
            'is_active' => 'Active status',
        ];
    }
}