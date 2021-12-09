<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionPost;
use App\Http\Resources\TransactionResource;
use App\Models\Category;
use App\Models\PaymentType;
use App\Models\Transaction;
use App\Models\VCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function getTransaction(Transaction $transaction)
    {
        return new TransactionResource($transaction);
    }

    public function getLastTransaction()
    {
        $transactions = Auth::user()->user_type == 'A' ? DB::table('transactions') : Auth::user()->vcard_ref->transactions();
        if (Auth::user()->user_type == 'V') {
            TransactionResource::$format = 'detailed';
        }
        return new TransactionResource($transactions->orderByDesc('date')->first());
    }

    public function getTransactions(Request $request)
    {
        $transactions = Auth::user()->user_type == 'A' ? DB::table('transactions') : Auth::user()->vcard_ref->transactions();

        if ($request->has("type")) {
            $transactions = $transactions->where("type", $request->type);
        }

        if ($request->has("start_date")) {
            $transactions = $transactions->whereDate("date", '>=' ,$request->start_date.' 00:00:00');
        }

        if ($request->has("end_date")) {
            $transactions = $transactions->where('date', '<=', $request->end_date.' 00:00:00');
        }

        if ($request->has("payment_type")) {
            $transactions = $transactions->where("payment_type", $request->payment_type);
        }

        $transactions = Auth::user()->user_type == 'A' ? $transactions->orderByDesc('created_at')->paginate(10) : $transactions->orderByDesc('created_at')->paginate(10);
        return TransactionResource::collection($transactions);
    }

    public function getPairTransaction(Request $request, Transaction $transaction)
    {
        return new TransactionResource($transaction->pairTransaction);
    }

    //Deprecated - Could use filter
    public function getTransactionsByPaymentType(Request $request, PaymentType $paymentType)
    {
        trigger_error("Deprecated function called.", E_USER_NOTICE);
        return new TransactionResource($paymentType->transactions);
    }

    public function getTransactionsByVcard(Request $request, VCard $vCard)
    {
        return new TransactionResource($vCard->transactions);
    }

    public function getTransactionsByCategory(Request $request, Category $category)
    {
        return new TransactionResource($category->transactions);
    }

    public function postTransaction(TransactionPost $request)
    {
        $transaction = new Transaction();
        //TODO uma transação quando é criada, a tranação primária pode ser de crédito?
            // (credit transactions are not allowed for the vCard owner)
        //TODO uma transação secundária tem categoria? como e porquê? para quem eu
        //transfiro dinheiro tem ou pode saber qual é a minha categoria
        date_default_timezone_set('Europe/Lisbon');
        $validated_data = $request->validated();
        try {
            if (Auth::user()->user_type == 'V') {
                ///set transaction
                $transaction->vcard = Auth::user()->vcard_ref->phone_number;
                $transaction->date = date("Y-m-d");
                $transaction->datetime = date('Y-m-d G:i:s', time());
                $transaction->type = $validated_data["type"];
                $transaction->value = $validated_data["value"];
                $transaction->old_balance = $transaction->vcard_ref->balance;

                //Debit part
                $transaction->new_balance = $transaction->vcard_ref->balance - $transaction->value;

                $transaction->payment_type = $validated_data["payment_type"];
                $transaction->payment_reference = $validated_data["payment_reference"];
                if ($request->has("category")) {
                    $transaction->category_id = $validated_data["category"];
                }
                if ($request->has("description")) {
                    $transaction->description = $validated_data["description"];
                }
                if ($request->has("custom_options")) {
                    $transaction->custom_options = $validated_data["custom_options"];
                }
                if ($request->has("custom_data")) {
                    $transaction->custom_data = $validated_data["custom_data"];
                }

                DB::beginTransaction();
                $transaction->save();
            }

            if ($validated_data["payment_type"] == 'VCARD' || Auth::user()->user_type == 'A') {
                ///set pair_transaction
                $pair_transaction = new Transaction();
                $pair_transaction->vcard = Auth::user()->user_type == 'V' ? $validated_data["payment_reference"] : $validated_data["vcard"];
                $pair_transaction->date = date("Y-m-d");
                $pair_transaction->datetime = date('Y-m-d G:i:s', time());
                $pair_transaction->type = 'C';
                $pair_transaction->value = $validated_data["value"];
                $pair_transaction->old_balance = $pair_transaction->vcard_ref->balance;
                $pair_transaction->new_balance = $pair_transaction->vcard_ref->balance + $pair_transaction->value;
                //Credit part
                $pair_transaction->new_balance = $pair_transaction->vcard_ref->balance + $pair_transaction->value;

                $pair_transaction->payment_type = $validated_data["payment_type"];
                $pair_transaction->payment_reference = Auth::user()->user_type == 'V' ? $transaction->vcard : $validated_data["payment_reference"];
                $pair_transaction->category_id = null;
                if ($request->has("description"))
                    $pair_transaction->description = $validated_data["description"];
                if ($request->has("custom_options"))
                    $pair_transaction->custom_options = $validated_data["custom_options"];
                if ($request->has("custom_data"))
                    $pair_transaction->custom_data = $validated_data["custom_data"];
                $pair_transaction->save();

                $pairVcard = $pair_transaction->vcard_ref;
                $pairVcard->balance = $pair_transaction->new_balance;
                $pairVcard->save();

                if (Auth::user()->user_type == 'V') {
                    $transaction->pair_transaction = $pair_transaction->id;
                    $transaction->pair_vcard = $pair_transaction->vcard;
                    $pair_transaction->pair_transaction = $transaction->id;
                    $pair_transaction->pair_vcard = $transaction->vcard;

                    $transaction->save();
                    $pair_transaction->save();
                }

            }

            if (Auth::user()->user_type == 'V') {
                $vcard = $transaction->vcard_ref;
                $vcard->balance = $transaction->new_balance;
                $vcard->save();
            }
            DB::commit();
            return new TransactionResource($transaction->id != null ? $transaction : $pair_transaction);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(array(
                'code'      =>  400,
                'message'   =>  $th->getMessage()
                ), 400);
        }
    }

    public function patchTransaction(Request $request, Transaction $transaction)
    {
        $data = $request->all();

        if ($request->has("description")) {
            $transaction->description = $data["description"];
        }
        if ($request->has("category") && $data["category"] != null) {
            if (Auth::user()->user_type == 'V' && Category::findOrFail($data["category"])->type != $transaction->type) {
                return response()->json(array(
                    'code'      =>  400,
                    'message'   =>  'Invalid Category, the category need to be the type of transaction.'
                    ), 422);
            }
            $transaction->category_id = Category::findOrFail($data["category"])->id;
        }else{
            $transaction->category_id = null;
        }
        $transaction->save();
        return new TransactionResource($transaction);
    }
}
