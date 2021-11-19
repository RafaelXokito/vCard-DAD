<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public static function sendSmsNotificaition($phonenumber,$message)
    {/*
        $basic  = new \Vonage\Client\Credentials\Basic("ccbf874f", "y06SmX3QB8XHVrsd");
        $client = new \Vonage\Client($basic);

        $response = $client->sms()->send(
            new \Vonage\SMS\Message\SMS("351".$phonenumber, "VCard", $message)
        );

        $message = $response->current();*/
    }

    public static function makeVerification()
    {
        $basic  = new \Vonage\Client\Credentials\Basic("ccbf874f", "y06SmX3QB8XHVrsd");
        $client = new \Vonage\Client($basic);
        $request = new \Vonage\Verify\Request("351".Auth::user()->vcard_ref->phone_number, "VCard");
        $response = $client->verify()->start($request);

        return $response;
    }

    public static function verifyVerification($request_id, $code)
    {
        $basic  = new \Vonage\Client\Credentials\Basic("ccbf874f", "y06SmX3QB8XHVrsd");
        $client = new \Vonage\Client($basic);

        $result = $client->verify()->check($request_id, $code);
        return $result;
    }

    public static function cancelVerification($request_id)
    {
        $basic  = new \Vonage\Client\Credentials\Basic("ccbf874f", "y06SmX3QB8XHVrsd");
        $client = new \Vonage\Client($basic);

        try {
            $result = $client->verify()->cancel($request_id);
            return $result->getResponseData();
        }
        catch(Exception $e) {
            return 'Message: ' .$e->getMessage();
        }
    }
}
