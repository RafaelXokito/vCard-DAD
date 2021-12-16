<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserPasswordPatch extends FormRequest
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
            'password_old'          => ['required', 'string'],
            'password_new'          => ['required', 'string']
        ];
    }
	
	public function messages()
	{
		return [
			'password_old.required' => "User's password is required",
            'password_old.string' => "User's password must be a string",
			'password_new.required' => "User's password is required",
            'password_new.string' => "User's Password must be a string",
		];
	}
}
