<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PaymentTypePatch extends FormRequest
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
            'code' => ['required', 'string', 'size:10'],
            'name' => ['required','string', 'size:50'],
            'description' => ['nullable','string', 'size:255'],
            'name' => ['nullable','string', 'JSON'],
            'custom_options' => ['nullable','string', 'JSON'],
            'custom_data' => ['nullable','string', 'JSON'],
        ];
    }

    public function messages()
	{
		return [
			'code.required' => "Payment Type's code is required",
            'code.string' => "Payment Type's code must be a string",
            'code.size:10' => "Payment Type's code must have less than 11 characters!",
			'name.required' => "Payment Type's name is required",
            'name.string' => "Payment Type's name must be a string",
            'name.size:50' => "Payment Type's name must have less than 51 characters!",
            'description.string' => "Payment Type's description must be a string",
            'description.size:255' => "Payment Type's description must have less than 256 characters!",
            'name.string' => "Payment Type's name must be a string",
            'name.JSON' => "Payment Type's name must be a valid JSON string",
            'custom_options.string' => "Payment Type's custom options must be a string",
            'custom_options.JSON' => "Payment Type's custom options must be a valid JSON string",
            'custom_data.string' => "Payment Type's custom data must be a string",
            'custom_data.JSON' => "Payment Type's custom data must be a valid JSON string",
		];
	}
}
