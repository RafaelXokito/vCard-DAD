<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdministratorPost;
use App\Http\Requests\AdminPost;
use App\Http\Resources\AdminResource;
use App\Models\Administrator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdministratorController extends Controller
{
    public function postAdmin(AdministratorPost $request)
    {
        $admin = new Administrator();
        $validated_data = $request->validated();

        try {
            $admin->name = $validated_data["name"];
            $admin->email = $validated_data["email"];
            $admin->password = bcrypt($validated_data["password"]);

            $admin->save();
            //$admin->user_ref->sendEmailVerificationNotification();

            return new AdminResource($admin);
        } catch (\Throwable $th) {
            return response()->json(array(
                'code'      =>  400,
                'message'   =>  $th->getMessage()
                ), 400);
        }
    }

    public static function removeAdmin(Administrator $admin)
    {
        $oldName = $admin->name;
        if (Administrator::all()->count() > 1) {
            $admin->delete();
        }else {
            return response()->json(array(
                'code'      =>  422,
                'message'   =>  "Admin ". $oldName ." cannot be removed, you cant delete last admin!"
            ), 422);
        }
        return response()->json(array(
            'code'      =>  200,
            'message'   =>  "Admin ". $oldName ." was removed!"
        ), 200);
    }
}
