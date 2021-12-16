<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserConfirmationCodePatch extends FormRequest
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
            'password'          => ['required', 'string'],
            'confirmation_code' => ['required', 'digits:4']
        ];
    }
	
	public function messages()
	{
		return [
			'password.required' => 'A password is required',
            'password.string' => 'Password must be a string',
			'confirmation_code.required' => 'Confirmation code is required',
            'confirmation_code.digits:4' => 'Confirmation code must have 4 digits',
		];
	}
}
