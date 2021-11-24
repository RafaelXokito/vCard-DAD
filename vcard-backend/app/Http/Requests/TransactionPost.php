<?php

namespace App\Http\Requests;

use App\Models\PaymentType;
use App\Models\VCard;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TransactionPost extends FormRequest
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
            'type'              => ['required','bail', Rule::in(['D'/*, 'C'*/])],
            'value'             => ['required','bail','numeric', function ($attribute, $value, $fail) {
                                        if ($value <= 0) {
                                            $fail('The value need to be positive.');
                                        }
                                        if (!preg_match("/^[0-9]+(\.[0-9]{1,2})?$/", $value)) {
                                            $fail('The value need to have two decimal places.');
                                        }
                                        if ($value > VCard::findOrFail(Auth::user()->vcard_ref->phone_number)->balance) {
                                            $fail('Dont have enough balance to complete this transaction.');
                                        }
                                        if ($value > VCard::findOrFail(Auth::user()->vcard_ref->phone_number)->max_debit) {
                                            $fail('The value exced the max debit per transaction transaction.');
                                        }
                                    }],
            'category'       => ['nullable','exists:categories,id'],
            'payment_type'      => ['required','bail','exists:payment_types,code'],
            'payment_reference' => ['required',function($attribute, $value, $fail) {
                                            if (!preg_match("/^".PaymentType::findOrFail($this->request->all()["payment_type"])->validation_rules["regex"]."?$/", $value)) {
                                                $fail('Invalid Payment Reference format.');
                                            }
                                        }],
            'description'       => 'nullable',
            'custom_options'    => 'nullable',
            'custom_data'       => 'nullable'
        ];
    }
}
