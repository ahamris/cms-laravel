<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'logo' => 'required',
            'favicon' => 'required',
            'title' => 'required',
            'description' => 'required',
            'github' => 'required',
            'api_active' => 'required',
            'registration_active' => 'required',
            'token_active' => 'required',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'logo.required' => 'Logo is required',
            'favicon.required' => 'Favicon is required',
            'title.required' => 'Title is required',
            'description.required' => 'Description is required',
            'github.required' => 'Github is required',
            'api_active.required' => 'Api Active is required',
            'registration_active.required' => 'Registration Active is required',
            'token_active.required' => 'Token Active is required',
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'logo' => 'Logo',
            'favicon' => 'Favicon',
            'title' => 'Title',
            'description' => 'Description',
            'github' => 'Github',
            'api_active' => 'Api Active',
            'registration_active' => 'Registration Active',
            'token_active' => 'Token Active',
        ];
    }
}
