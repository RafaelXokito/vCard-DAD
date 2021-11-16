<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;

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

    public function getUsers()
    {
        return UserResource::collection(User::paginate(10));
    }
}
