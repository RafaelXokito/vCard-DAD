<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\VCardPost;
use App\Http\Resources\VCardResource;
use App\Models\VCard;
use Illuminate\Http\Request;
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



}
