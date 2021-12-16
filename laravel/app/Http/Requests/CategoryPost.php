<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CategoryPost extends FormRequest
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
            'name' => ['required', 'string'],
            'type' => ['required', Rule::in(['D', 'C'])]
        ];
    }

    public function messages()
	{
		return [
			'name.required' => "Category's name is required",
            'name.string' => "Category's name must be a string",
            'type.required' => "Category's type is required",
			'type.Rule::in(['D', 'C'])' => "Category's type can either be Credit or Debit",
		];
	}
}
