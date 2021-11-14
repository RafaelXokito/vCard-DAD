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
use Illuminate\Support\Facades\DB;
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
    public function signin (SigninPost $request, $default = true) {
        //TODO PostSignin
        if ($default) {
            $validator = $request->validated();
        }else {
            $validator = $request;
        }

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
            if (Auth::attempt(['username' => $validator["username"], 'password' => $validator["password"]])) {
                $auxResponse = json_decode((string) $response->content(), true);
                $auxResponse["username"] = Auth::user()->username;
                $auxResponse["user_type"] = Auth::user()->user_type;
                $auxResponse["id"] = Auth::user()->id;
            }else {
                $auxResponse = json_decode((string) $response->content(), true);

            }
            return response()->json(
                ["user" => $auxResponse]
            );
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
        $validator = $request->validated();
        DB::beginTransaction();

        try {
            $vcard->phone_number = $validator["phone_number"];
            $vcard->name = $validator["name"];
            $vcard->email = $validator["email"];
            if ($request->hasFile('photo_url')) {
                $vcard->photo_url = basename(Storage::disk('local')->putFileAs('vcard_photos\\', $validator['photo_url'], $vcard->phone_number . "_" . Str::random(6) . '.jpg'));
            }
            $vcard->password = bcrypt($validator["password"]);
            $vcard->confirmation_code = bcrypt($validator["confirmation_code"]);
            $vcard->blocked = false;
            $vcard->save();
            DB::commit();

            $requestSignin = new SigninPost(['username' => $validator["phone_number"], 'password' => $validator["password"]]);

            return $this->signin($requestSignin, false);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json(array(
                'code'      =>  400,
                'message'   =>  $th->getMessage()
                ), 400);
        }
    }
}
