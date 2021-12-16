<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ConfirmationCodePost extends FormRequest
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
            'confirmationCode' => ['required', 'digits:4']
        ];
    }

    public function messages()
	{
		return [
			'confirmationCode.required' => 'Confirmation code is required',
            'confirmationCode.digits:4' => 'Confirmation code must have 4 digits',
		];
	}
}
