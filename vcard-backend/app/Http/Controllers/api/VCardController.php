<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\VcardDelete;
use App\Http\Requests\VCardMaxDebitPatch;
use App\Http\Requests\VCardPost;
use App\Http\Resources\VCardResource;
use App\Models\VCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VCardController extends Controller
{
    public function getVcards()
    {
        return new VCardResource(VCard::all());
    }

    public function getVcard(Request $request, VCard $vcard)
    {
        if ($request->has("balance") && $request->balance == true) {
            VCardResource::$format = "balance";
        }

        return new VCardResource($vcard);
    }

    public function blockVCard(VCard $vcard)
    {
        $vcard->blocked = !$vcard->blocked;

        $vcard->save();

        return new VCardResource($vcard);
    }

    public function changeMaxDebit(VCardMaxDebitPatch $request, VCard $vcard)
    {
        $validated_data = $request->validated();
        $vcard->max_debit = $validated_data["max_debit"];

        $vcard->save();

        return new VCardResource($vcard);
    }

    public function postVcard(VCardPost $request)
    {
        $vcard = new vCard();
        $validated_data = $request->validated();

        try {
            $vcard->phone_number = $validated_data["phone_number"];
            $vcard->name = $validated_data["name"];
            $vcard->email = $validated_data["email"];
            if ($request->hasFile('imagem_url')) {
                $vcard->photo_url = basename(Storage::disk('local')->putFileAs('vcard_photos\\', $validated_data['photo_url'], $vcard->phone_number . "_" . Str::random(6) . '.jpg'));
            }
            $vcard->password = bcrypt($validated_data["password"]);
            $vcard->confirmation_code = bcrypt($validated_data["confirmation_code"]);
            $vcard->blocked = false;

            $vcard->save();

            return new VCardResource($vcard);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(array(
                'code'      =>  400,
                'message'   =>  $th->getMessage()
                ), 400);
        }
    }

    public function putcard(VCardPost $request, vCard $vcard)
    {
        $validated_data = $request->validated();
        try {
            $vcard->name = $validated_data["name"];
            $vcard->email = $validated_data["email"];
            if ($request->hasFile('photo_url')) {
                Storage::disk('public')->delete('estampas_privadas/'.$vcard->photo_url);
                $vcard->photo_url = basename(Storage::disk('public')->putFileAs('vcard_photos\\', $validated_data['photo_url'], $vcard->phone_number . "_" . Str::random(6) . '.jpg'));
            }
            $vcard->password = bcrypt($validated_data["password"]);
            $vcard->confirmation_code = bcrypt($validated_data["confirmation_code"]);
            $vcard->blocked = false;

            $vcard->save();

            return new VCardResource($vcard);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(array(
                'code'      =>  400,
                'message'   =>  $th->getMessage()
                ), 400);
        }
    }

    public function remove(VcardDelete $request, VCard $vcard)
    {
        $validated_data = $request->validated();

        if (!Hash::check($validated_data["password"], $vcard->password)) {
            return response()->json(array(
                'code'      =>  422,
                'message'   =>  "Password does not match!"
            ), 422);
        }

        if (!Hash::check($validated_data["confirmation_code"], $vcard->confirmation_code)) {
            return response()->json(array(
                'code'      =>  422,
                'message'   =>  "Confirmation Code does not match!"
            ), 422);
        }

        return $this->removeVcard($vcard);
    }

    public function removeVcard(VCard $vcard)
    {
        $oldName = $vcard->name;
        $oldPhoneNumber = $vcard->phone_number;
        if ($vcard->balance != 0) {
            return response()->json(array(
                'code'      =>  406, //Not Acceptable
                'message'   =>  "vCard balance may be 0 to remove account!"
                ), 422); //Not Acceptable
        }

        if ($vcard->transactions->count() == 0) {
            foreach ($vcard->categories as $categorie) {
                $categorie->forceDelete();
            }
            $vcard->forceDelete();
        }else {
            foreach ($vcard->categories as $categorie) {
                $categorie->delete();
            }
            foreach ($vcard->transactions as $transaction) {
                $transaction->delete();
            }
            $vcard->delete();
        }

        return response()->json(array(
            'code'      =>  200,
            'message'   =>  "vCard was removed [". $oldPhoneNumber ."]:". $oldName ."!"
        ), 200);
    }

    public function restoreVcard($phone_number)
    {
        $vcard = VCard::withTrashed()->findOrFail($phone_number);
        $this->authorize('restore', $vcard);
        try {
            $vcard->restore();
        } catch (\Throwable $th) {
            return response()->json(array(
                'code'      =>  200,
                'message'   =>  $th->getMessage()
            ), 200);
        }

        return new VCardResource($vcard);
    }
}
