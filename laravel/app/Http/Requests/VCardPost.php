<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class VCardPost extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone_number'             => ['required','bail' ,'unique:App\Models\User,username', function ($attribute, $value, $fail) {
                if (!preg_match("/^([9][1236])[0-9]*?$/", $value)) {
                    $fail('The phone number need to follow the portuguese number.');
                }
            }],
            'name'              => ['required', 'string'],
            'email'             => ['required', 'email', 'unique:App\Models\User,email'],
            'photo_url'         => ['nullable', 'mimes:jpeg,jpg,png', 'max:8192'],
            'password'          => ['required', 'string'],
            'confirmation_code' => ['required', 'digits:4']
        ];
    }

    public function messages()
	{
		return [
			'phone_number.required' => "vCard's phone number is required",
			'phone_number.unique:App\Models\User,username' => "vCard's confirmation code is required",
            'name.required' => "vCard's name is required",
            'name.string' => "vCard's name must be a string",
            'email.required' => "vCard's email must be a string",
			'email.email' => 'Wrong format for an email',
			'email.unique:App\Models\User,email' => 'The email has already been taken',
            'photo_url.mimes:jpeg,jpg,png' => 'Must be jpeg, jpg or png format',
			'photo_url.max:8192' => 'Maximum size of photo is 8192 pixels',
            'password.required' => "vCard's password is required",
            'password.string' => "vCard's password must be a string",
			'confirmation_code.required' => "vCard's confirmation code is required",
            'confirmation_code.digits:4' => "vCard's confirmation code must have 4 digits",
		];
	}
}
