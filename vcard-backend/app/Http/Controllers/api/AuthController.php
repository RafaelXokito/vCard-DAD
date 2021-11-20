<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConfirmationCodePost;
use App\Http\Requests\SigninPost;
use App\Http\Requests\VCardPost;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\VCard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\NotificationController;

class AuthController extends Controller
{
    const PASSPORT_SERVER_URL = "http://vcard-backend.test";
    const CLIENT_ID = 2;

    public function signin(SigninPost $request, $default = true)
    {
        if ($default) {
            $validator = $request->validated();
        } else {
            $validator = $request;
        }

        request()->request->add([
            'grant_type' => 'password',
            'client_id' => self::CLIENT_ID,
            'client_secret' => env('PASSPORT_SECRET'),
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
                $auxResponse["photo_url"] = Auth::user()->photo_url != null ? "storage/fotos/" . Auth::user()->photo_url : "storage/fotos/default_image.png";
                $auxResponse["id"] = Auth::user()->id;
            } else {
                $auxResponse = json_decode((string) $response->content(), true);
            }

            return response()->json(
                ["user" => $auxResponse]
            );
        } else {
            return response()->json(
                ["message" => "The given data was invalid.", "errors" => ["auth" => ["User credentials are invalid."]]],
                $errorCode
            );
        }
    }

    public function logout(Request $request)
    {
        $accessToken = $request->user()->token();
        $token = $request->user()->tokens->find($accessToken);
        $token->revoke();
        $token->delete();
        return response(['msg' => 'Token revoked'], 200);
    }

    public function registerVCard(VCardPost $request)
    {
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

            $vcard->custom_data = json_encode(["phonenumber_confirmed" => "false"]);

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

    public function confirmationCode(ConfirmationCodePost $request, User $vcard)
    {
        $validator = $request->validated();
        if (Hash::check($validator["confirmationCode"], $vcard->vcard_ref->confirmation_code)) {
            return response()->json(
                [
                    'code'      =>  200,
                    'message'   =>  "Confirmation Code matched!"
                ],
                200
            );
        } else {
            return response()->json(
                [
                    'code'      =>  422,
                    'message'   =>  "Confirmation Code was NOT matched!"
                ],
                422
            );
        }
    }

    public function makeConfirmationPhoneNumber()
    {
        $vcard = Auth::user()->vcard_ref;
        $custom_data = $vcard->custom_data;
        $custom_data = json_decode((string) $custom_data, true);

        $response = NotificationController::makeVerification();
        $custom_data["phonenumber_confirmed"] = $response->getRequestId();

        $vcard->custom_data = json_encode($custom_data);
        $vcard->save();
        return response()->json(
            [
                'code'      =>  200,
                'message'   =>  "Confirmation Phone Code Generated!"
            ],
            200
        );
        //return NotificationController::makeVerification();
    }

    public function verifyConfirmationPhoneNumber(Request $request)
    {
        $vcard = Auth::user()->vcard_ref;
        $custom_data = $vcard->custom_data;
        $custom_data = json_decode((string) $custom_data, true);

        $result = NotificationController::verifyVerification($custom_data["phonenumber_confirmed"], $request->code);
        if ($result->getRequestData()["status"] == 0) {
            $custom_data["phonenumber_confirmed"] = true;
            $vcard->custom_data = json_encode($custom_data);
            $vcard->save();

            return response()->json(
                [
                    'code'      =>  200,
                    'message'   =>  "Confirmation Phone Code matched!"
                ],
                200
            );
        }
        if ($result->getRequestData()["status"] == 16) {
            return response()->json(
                [
                    'code'      =>  422,
                    'message'   =>  $result->getResponseData()["error_text"]
                ],
                422
            );
        }
        return response()->json(
            [
                'code'      =>  500,
                'message'   =>  $result->getResponseData()
            ],
            500
        );
    }

    public function cancelConfirmationPhoneNumber()
    {

        $vcard = Auth::user()->vcard_ref;
        $custom_data = $vcard->custom_data;
        $custom_data = json_decode((string) $custom_data, true);

        return NotificationController::cancelVerification($custom_data["phonenumber_confirmed"]);
    }

    public function checkConfirmationPhoneNumber()
    {
        $vcard = Auth::user()->vcard_ref;
        $custom_data = $vcard->custom_data;
        $custom_data = json_decode((string) $custom_data, true);

        if ($custom_data["phonenumber_confirmed"] == "true") {
            return response()->json(
                [
                    'code'      =>  200,
                    'message'   =>  "Confirmation Phone Code Done!"
                ],
                200
            );
        }
        return response()->json(
            [
                'code'      =>  300,
                'message'   =>  "Confirmation Phone Code Not Done!"
            ],
            200
        );
    }
}
