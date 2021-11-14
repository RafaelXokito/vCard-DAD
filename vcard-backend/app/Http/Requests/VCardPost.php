<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VCardPost extends FormRequest
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
            'phone_number'             => ['required','bail' ,'unique:App\Models\User,username', function ($attribute, $value, $fail) {
                if (!preg_match("/^([9][1236])[0-9]*?$/", $value)) {
                    $fail('The phone number need to follow the portuguese number.');
                }
            }],
            'name'              => ['required', 'string'],
            'email'             => ['required', 'email', 'unique:App\Models\User,email'],
            'photo_url'         => ['nullable', 'image', 'max:8192'],
            'password'          => ['required', 'string'],
            'confirmation_code' => ['required', 'digits:4']
        ];
    }
}
