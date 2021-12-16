<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class VCardPhotoPost extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check() && Auth::user()->user_type == 'V';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'photo_url'         => ['required', 'mimes:jpeg,jpg,png', 'max:8192'],
        ];
    }

    public function messages()
	{
		return [
			'photo_url.required' => 'A photo is required',
            'photo_url.mimes:jpeg,jpg,png' => 'Must be jpeg, jpg or png format',
			'photo_url.max:8192' => 'Maximum size of photo is 8192 pixels',
		];
	}
}
