<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserConfirmationCodePatch;
use App\Http\Requests\UserPasswordPatch;
use App\Http\Requests\UserPatch;
use App\Http\Requests\UserPhotoPost;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\VCard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    public function getUsers(Request $request)
    {
        UserResource::$format = 'vCardAdminList';
        $users = DB::table('view_auth_users')
            ->select('view_auth_users.*',DB::Raw('`vcards`.`max_debit` AS `max_debit`'),DB::Raw('IFNULL( `users`.`created_at` , `vcards`.`created_at`) AS `created_at`'))
            ->leftJoin('users', 'view_auth_users.id', '=', 'users.id')
            ->leftJoin('vcards', 'view_auth_users.id', '=', 'vcards.phone_number');
        if ($request->has("type")) {
            $users = $users->where("view_auth_users.user_type", $request->type);
        }
        if ($request->has("name")) {
            $users = $users->where("view_auth_users.name",'LIKE', "%".$request->name."%");
        }
        if ($request->has("page")) {
            $users = $users->orderBy('created_at', 'desc')->paginate(15);
        }else {
            $users = $users->orderBy('created_at', 'desc')->get();
        }
        return UserResource::collection($users);
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
                Storage::disk('public')->delete('fotos\\'.$userAux->photo_url);
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
