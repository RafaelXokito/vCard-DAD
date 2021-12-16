<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class VCardMaxDebitPatch extends FormRequest
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
            'max_debit' => 'required|numeric|min:0.01'
        ];
    }

    public function messages()
	{
		return [
			'max_debit.required' => 'Maximum debit is required',
            'max_debit.numeric' => 'Maximum debit must be a numeric value',
			'max_debit.min:0.01' => 'Minimum debit is 0.01 €',
		];
	}
}
