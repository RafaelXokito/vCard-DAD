<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SigninPost extends FormRequest
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
            'username' => ['required', function ($attribute, $value, $fail) {
                                            if (!preg_match("/^(9[0-9]{8})?$/", $value) && !preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}?$/", $value)) {
                                                $fail('The phone number need to follow the portuguese number or email.');
                                            }
                                        }
            ],
            'password' => 'required|string',
        ];
    }
}
