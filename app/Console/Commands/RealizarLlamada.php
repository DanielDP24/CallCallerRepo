<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class RealizarLlamada extends Command
{
    protected $signature = 'twilio:call';
    protected $description = 'Realiza una llamada usando Twilio';

    public function handle()
    {
        $sid = config("services.twilio.sid");
        $token = config("services.twilio.token");
        $twilio = new Client($sid, $token);

        $twilio->calls->create(
            
            "+34951798775",//from
            "+34951794023",
            [
                "url" => "http://54.247.29.41:8001/api/SayName",
            ]
        );
        Log::info('llamada realizada');
    }
}
