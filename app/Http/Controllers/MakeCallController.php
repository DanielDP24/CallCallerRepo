<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class MakeCallController extends Controller
{
    public function realizarLlamada()
    {
        $sid = config("services.twilio.sid");
        $token = config("services.twilio.token");
       
        $twilio = new Client($sid, $token);

        $result =  $twilio->calls->create(

            "+34951798775", //from
            "+34951794023", //to
            [
                "url" => "http://54.247.29.41:8001/api/SayName",
              //  "record" => true
            ]
        );
    }}
