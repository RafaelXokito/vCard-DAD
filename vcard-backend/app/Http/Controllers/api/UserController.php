<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
	
	public function update_password(PasswordPost $request, User $user)
    {
        $user->password = bcrypt($request->validated()['password']);
        $user->save();
        return new UserResource($user);
    }
}
