<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserPatch extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'              => ['nullable', ''],
            'email'             => ['nullable', 'email', 'unique:App\Models\User,email,'.Auth::user()->id],
        ];
    }
	
		public function messages()
	{
		return [
			'name.string' => "User's name must be a string",
            'email.email' => 'Wrong format for an email',
            'email.unique:App\Models\User,email' => 'The email has already been taken',
            'email.Auth::user()->id' => 'This email does not belong to the authenticated user',
		];
	}
}
