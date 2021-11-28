<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserConfirmationCodePatch;
use App\Http\Requests\UserPasswordPatch;
use App\Http\Requests\UserPatch;
use App\Http\Requests\UserPhotoPost;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function getUser(User $user)
    {

        if ($user->user_type == 'V') {
            UserResource::$format = 'detailedVCard';
        }elseif ($user->user_type == 'A') {
            UserResource::$format = 'detailedAdmin';
        }
        return new UserResource($user);
    }

    public function getMe()
    {
        if (Auth::user()->user_type == 'V') {
            UserResource::$format = 'detailedVCard';
        }elseif (Auth::user()->user_type == 'A') {
            UserResource::$format = 'detailedAdmin';
        }
        return new UserResource(Auth::user());
    }

    public function getUsers()
    {
        return UserResource::collection(User::paginate(10));
    }
    /*
	public function update_password(PasswordPost $request, User $user)
    {
        $user->password = bcrypt($request->validated()['password']);
        $user->save();
        return new UserResource($user);
    }*/

    public function postUserPhoto(UserPhotoPost $request, User $user)
    {
        //return $request;
        $validated_data = $request->validated();
        if ($user->user_type == 'A') {
            UserResource::$format = 'detailedAdmin';
            $userAux = $user->admin_ref;
        }else if ($user->user_type == 'V') {
            UserResource::$format = 'detailedVCard';
            $userAux = $user->vcard_ref;
        }
        try {

            if ($request->has("photo_url")) {
                $userAux->photo_url = basename(Storage::disk('public')->putFileAs('fotos\\', $request->photo_url, $user->username . "_" . Str::random(6) . '.jpg'));
            }
            $userAux->save();
            return response()->json(array(
                'code'      => 200,
                'photo_url'   => $userAux->photo_url
            ), 200);
        } catch (\Throwable $th) {
            return response()->json(array(
                'code'      =>  400,
                'message'   =>  $th->getMessage()
            ), 400);
        }
    }

    public function patchUser(UserPatch $request, User $user)
    {
        $validated_data = $request->validated();
        if ($user->user_type == 'A') {
            UserResource::$format = 'detailedAdmin';
            $userAux = $user->admin_ref;
        }else if ($user->user_type == 'V') {
            UserResource::$format = 'detailedVCard';
            $userAux = $user->vcard_ref;
        }
        try {
            if ($request->has("name")) {
                $userAux->name = $validated_data["name"];
            }
            if ($request->has("email")) {
                $userAux->email = $validated_data["email"];
            }
            $userAux->save();
            return new UserResource($user);
        } catch (\Throwable $th) {
            return response()->json(array(
                'code'      =>  400,
                'message'   =>  $th->getMessage()
            ), 400);
        }
    }

    public function patchPasswordUser(UserPasswordPatch $request, User $user)
    {
        $validated_data = $request->validated();

        if ($user->user_type == 'A') {
            UserResource::$format = 'detailedAdmin';
            $userAux = $user->admin_ref;
        }else if ($user->user_type == 'V') {
            UserResource::$format = 'detailedVCard';
            $userAux = $user->vcard_ref;
        }

        try {

            if (Hash::check($validated_data["password_old"], $user->password)) {
                $userAux->password = bcrypt($validated_data["password_new"]);
                $userAux->save();
                return new UserResource($user);
            }else {
                return response()->json(array(
                    'code'      =>  422,
                    'message'   =>  "Old Password does not match with the current password!"
                ), 422);
            }

        } catch (\Throwable $th) {
            return response()->json(array(
                'code'      =>  400,
                'message'   =>  $th->getMessage()
            ), 400);
        }
    }

    public function patchConfirmationCodeUser(UserConfirmationCodePatch $request, User $user)
    {
        $validated_data = $request->validated();

        if ($user->user_type == 'A') {
            UserResource::$format = 'detailedAdmin';
            $userAux = $user->admin_ref;
        }else if ($user->user_type == 'V') {
            UserResource::$format = 'detailedVCard';
            $userAux = $user->vcard_ref;
        }

        try {

            if (Hash::check($validated_data["password"], $user->password)) {
                $userAux->confirmation_code = bcrypt($validated_data["confirmation_code"]);
                $userAux->save();
                return new UserResource($user);
            }else {
                return response()->json(array(
                    'code'      =>  422,
                    'message'   =>  "Old Password does not match with the current password!"
                ), 422);
            }

        } catch (\Throwable $th) {
            return response()->json(array(
                'code'      =>  400,
                'message'   =>  $th->getMessage()
            ), 400);
        }
    }

}
