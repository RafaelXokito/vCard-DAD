<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CategoryPatch extends FormRequest
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
            'name' => ['nullable','string'],
            'type' => ['nullable', Rule::in(['D', 'C'])]
        ];
    }

    public function messages()
	{
		return [
			'name.string' => "Category's name must be a string",
			'type.Rule::in(['D', 'C'])' => "Category's type can either be Credit or Debit",
		];
	}
}
