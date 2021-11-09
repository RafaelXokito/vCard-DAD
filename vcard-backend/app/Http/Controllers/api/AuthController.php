<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SigninPost;
use App\Http\Requests\VCardPost;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\VCard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

class AuthController extends Controller
{
    const PASSPORT_SERVER_URL = "http://localhost";
    const CLIENT_ID = 2;
    const CLIENT_SECRET = 'gLptW4CUN5LQOLOiZYw2EcAchleK1ORMYMuDfvvV';
    public function signin (SigninPost $request) {
        //TODO PostSignin
        $validator = $request->validated();

        request()->request->add([
            'grant_type' => 'password',
            'client_id' => self::CLIENT_ID,
            'client_secret' => self::CLIENT_SECRET,
            'username' => $validator["username"],
            'password' => $validator["password"],
            'scope' => ''
        ]);

        $request = Request::create(self::PASSPORT_SERVER_URL . '/oauth/token', 'POST');

        $response = Route::dispatch($request);
        $errorCode = $response->getStatusCode();
        if ($errorCode == '200') {
            return json_decode((string) $response->content(), true);
        } else {
            return response()->json(
                ['msg' => 'User credentials are invalid'], $errorCode
            );
        }
    }

    public function logout (Request $request) {
        $accessToken = $request->user()->token();
        $token = $request->user()->tokens->find($accessToken);
        $token->revoke();
        $token->delete();
        return response(['msg' => 'Token revoked'], 200);
    }

    public function registerVCard (VCardPost $request) {
        $vcard = new VCard();
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

            //return new VCardResource($vcard);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(array(
                'code'      =>  400,
                'message'   =>  $th->getMessage()
                ), 400);
        }
    }
}
