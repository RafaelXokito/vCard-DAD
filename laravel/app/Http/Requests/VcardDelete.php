<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class VcardDelete extends FormRequest
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
            'password'          => ['required', 'string'],
            'confirmation_code' => ['required', 'digits:4']
        ];
    }

    public function messages()
	{
		return [
			'password.required' => "vCard's password is required",
            'password.string' => "vCard's password must be a string",
			'confirmation_code.required' => "vCard's confirmation code is required",
            'confirmation_code.digits:4' => "vCard's confirmation code must have 4 digits",
		];
	}
}
