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
}
