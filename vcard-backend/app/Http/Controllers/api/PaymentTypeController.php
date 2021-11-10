<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentTypeResource;
use App\Models\PaymentType;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentTypeController extends Controller
{
    public function getPaymentTypeByTransaction(Request $request, Transaction $transaction)
    {
        return new PaymentTypeResource($transaction->paymentType);
    }

    public function getPaymentTypes(Request $request)
    {
        return new PaymentTypeResource(PaymentType::all());
    }
}
