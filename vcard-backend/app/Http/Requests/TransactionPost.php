<?php

namespace App\Http\Requests;

use App\Models\PaymentType;
use App\Models\VCard;
use Illuminate\Foundation\Http\FormRequest;
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
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $data = $this->request->all();
        //return ['payment_type' => "", 'payment_reference' => ""];
        return [
            'vcard'             => ['required','bail','exists:vcards,phone_number'],
            'type'              => ['required','bail', Rule::in(['D'/*, 'C'*/])],
            'value'             => ['required','bail','numeric', function ($attribute, $value, $fail) {
                                        if ($value <= 0) {
                                            $fail('The value need to be positive.');
                                        }
                                        if (!preg_match("/^[0-9]+(\.[0-9]{1,2})?$/", $value)) {
                                            $fail('The value need to have two decimal places.');
                                        }
                                        if ($value > VCard::findOrFail($this->vcard)->balance) {
                                            $fail('Dont have enough balance to complete this transaction.');
                                        }
                                        if ($value > VCard::findOrFail($this->vcard)->max_debit) {
                                            $fail('Dont have enough balance to complete this transaction.');
                                        }
                                    }],
            'category_id'       => ['nullable','exists:categories,id'],
            'payment_type'      => ['required','bail','exists:payment_types,code'],
            'payment_reference' => ['required',function($attribute, $value, $fail) {
                                            if (!preg_match("/^".PaymentType::findOrFail($this->request->all()["payment_type"])->validation_rules["regex"]."?$/", $value)) {
                                                $fail('The value need to have two decimal places.');
                                            }
                                        }],
            'description'       => 'nullable',
            'custom_options'    => 'nullable',
            'custom_data'       => 'nullable'
        ];
    }
}
