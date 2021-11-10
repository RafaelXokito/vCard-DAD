<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
    public function getUser($username)
    {
        return new UserResource(User::where('username', $username)->first());
    }
}
