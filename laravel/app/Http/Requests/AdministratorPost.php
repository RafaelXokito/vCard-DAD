<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AdministratorPost extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check() && Auth::user()->user_type == 'A';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'              => ['required', 'string'],
            'email'             => ['required', 'email', 'unique:App\Models\User,email'],
            'password'          => ['required', 'string'],
        ];
    }
	
	public function messages()
	{
		return [
			'name.required' => "Administrator's name is required",
			'name.string' => "Administrator's name must be a string",
			'email.required' => "Administrator's email is required",
			'email.email' => 'Wrong format for an email',
			'email.unique:App\Models\User,email' => 'The email has already been taken',
			'password.required' => "Administrator's password is required",
			'password.string' => "Administrator's password must be a string",
		];
	}
}
