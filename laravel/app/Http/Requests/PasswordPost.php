<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateUserPasswordRequest extends FormRequest
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
            'password' => ['required', 'confirmed', Password::min(4)],
            'oldpassword' => 'current_password:api',
        ];
    }

    public function messages()
	{
		return [
			'password.required' => 'A password is required',
            'password.confirmed' => 'Password must be confirmed',
            'password.Password::min(4)' => 'Password must have at least 4 characters!',
            'oldpassword.current_password:api' => 'Old Password does not match with the current password!',
		];
	}
}
