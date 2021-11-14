<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
    public function getUser(User $user)
    {
        return new UserResource($user);
    }

    public function getUsers()
    {
        return UserResource::collection(User::all());
    }
}
