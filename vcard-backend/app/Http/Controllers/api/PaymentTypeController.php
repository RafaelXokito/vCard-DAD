<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentTypePost;
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

    public function getPaymentTypes()
    {
        return PaymentTypeResource::collection(PaymentType::all());
    }

    public function getPaymentType(PaymentType $paymentType)
    {
        return new PaymentTypeResource($paymentType);
    }

    public function postPaymentType(PaymentTypePost $request)
    {
        $validated_data = $request->validated();
        try {
            $newPaymentType = new PaymentType();
            $newPaymentType->code = $validated_data["code"];
            $newPaymentType->name = $validated_data["name"];
            $newPaymentType->description = $validated_data["description"];

            if ($request->has("validation_rules")) {
                $newPaymentType->validation_rules = json_encode($validated_data["validation_rules"]);
            }
            if ($request->has("custom_options")) {
                $newPaymentType->custom_options = json_encode($validated_data["custom_options"]);
            }
            if ($request->has("custom_data")) {
                $newPaymentType->custom_data = json_encode($validated_data["custom_data"]);
            }

            $newPaymentType->save();
            return new PaymentTypeResource($newPaymentType);

        } catch (\Throwable $th) {
            return response()->json(array(
                'code'      =>  400,
                'message'   =>  $th->getMessage()
                ), 400);
        }
    }
    public function patchPaymentType(PaymentTypePost $request, PaymentType $paymentType)
    {
        $validated_data = $request->validated();
        try {
            if ($request->has("name")) {
                $paymentType->name = $validated_data["name"];
            }
            if ($request->has("description")) {
                $paymentType->name = $validated_data["description"];
            }
            if ($request->has("validation_rules")) {
                $paymentType->validation_rules = json_encode($validated_data["validation_rules"]);
            }
            if ($request->has("custom_options")) {
                $paymentType->custom_options = json_encode($validated_data["custom_options"]);
            }
            if ($request->has("custom_data")) {
                $paymentType->custom_data = json_encode($validated_data["custom_data"]);
            }
            $paymentType->save();
            return new PaymentTypeResource($paymentType);
        } catch (\Throwable $th) {
            return response()->json(array(
                'code'      =>  400,
                'message'   =>  $th->getMessage()
                ), 400);
        }
    }
    public function deletePaymentType(Request $request, PaymentType $paymentType)
    {
        $oldName = $paymentType->nome;
        $oldPaymentTypeID = $paymentType->cod;
        try {
            $paymentType->delete();

            return "Payment Type ".$oldName." was deleted.";
        } catch (\Throwable $th) {
            return response()->json(array(
                        'code'      =>  400,
                        'message'   =>  "Payment Type ".$oldName." was NOT deleted"
                    ), 400);
        }
    }
}
