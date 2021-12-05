<?php

namespace App\Http\Requests;

use App\Models\Category;
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
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type'              => ['required','bail', Rule::in(['D', 'C'])],
            'value'             => ['required','bail','numeric', function ($attribute, $value, $fail) {
                                        if ($value <= 0) {
                                            $fail('The value need to be positive.');
                                        }
                                        if (!preg_match("/^[0-9]+(\.[0-9]{1,2})?$/", $value)) {
                                            $fail('The value need to have two decimal places.');
                                        }
                                        if (Auth::user()->user_type == 'V' && $value > VCard::findOrFail(Auth::user()->vcard_ref->phone_number)->balance) {
                                            $fail('Dont have enough balance to complete this transaction.');
                                        }
                                        if (Auth::user()->user_type == 'V' && $value > VCard::findOrFail(Auth::user()->vcard_ref->phone_number)->max_debit) {
                                            $fail('The value exced the max debit per transaction transaction.');
                                        }
                                    }],
            'category'          => ['nullable','exists:categories,id',function($attribute, $value, $fail) {
                                        if (Auth::user()->user_type == 'V' && Category::findOrFail($value)->vcard != Auth::user()->vcard_ref->phone_number) {
                                            $fail('Invalid Category, the category need to by yours.');
                                        }
                                    }], //verificar Apenas podem ser inseridas na BD categorias do owner da transação (a implementar no Controlador da Transação)
            'payment_type'      => ['required','bail','exists:payment_types,code'],
            'payment_reference' => ['required',function($attribute, $value, $fail) {
                                            if (PaymentType::findOrFail($this->request->all()["payment_type"])->validation_rules != null &&
                                                !preg_match("/^".PaymentType::findOrFail($this->request->all()["payment_type"])->validation_rules["regex"]."?$/", $value)) {
                                                $fail('Invalid Payment Reference format.');
                                            }
                                            if (Auth::user()->user_type == 'V' && $value == Auth::user()->vcard_ref->phone_number) {
                                                $fail('You cant transfer to your self.');
                                            }
                                        }],
            'description'       => 'nullable',
            'custom_options'    => 'nullable',
            'custom_data'       => 'nullable'
        ];
    }
}
