<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Jobs\SayYesJob;
use Twilio\TwiML\VoiceResponse;

class CallController extends Controller
{
    public function SayName()
    {


        $response = new VoiceResponse();

        // Introduce a delay of 8.5 seconds
        sleep(8.5);

        // Say "Daniel"
        Log::info('Decimos el nombre');
        $gather = $response->gather([
            'input'         => 'speech',
            'timeout'       => '13',
            'action'        => url('/api/SayYes'),
            'method'        => 'POST',
            'language'      => 'es-ES',
            'speechModel'   => 'googlev2_short',
            'speechTimeout' => '1',
        ]);

        $gather->say('Daniel Jesús', [
            'language' => 'es-ES',
            'voice' => 'Polly.Lucia-Neural',
            'rate' => '1'
        ]);

        Log::info('Terminando SayName, pasará a SayYes');

        return response($response)->header('Content-Type', 'text/xml');
    }

    public function SayYes()
    {
        Log::info('6 sec para el si');

        $response = new VoiceResponse();

        $response->pause(['length' => 6]);

        $gather = $response->gather([
            'input'         => 'speech',
            'timeout'       => '13',
            'action'        => url('/api/SayEmail'),
            'method'        => 'POST',
            'language'      => 'es-ES',
            'speechModel'   => 'googlev2_short',
            'speechTimeout' => '1',
        ]);

        $gather->say('Si', [
            'language' => 'es-ES',
            'voice' => 'Polly.Lucia-Neural',
            'rate' => '1'
        ]);

        Log::info('hemos dicho si');

        return response($response)->header('Content-Type', 'text/xml');
    }

    public function SayEmail()
    {

        $response = new VoiceResponse();

        $response->pause(['length' => 6]);
        Log::info('decimos email');

        $gather = $response->gather([
            'input'         => 'speech',
            'timeout'       => '13',
            'action'        => url('/api/SayYesEmail'),
            'method'        => 'POST',
            'language'      => 'es-ES',
            'speechModel'   => 'googlev2_short',
            'speechTimeout' => '1',
        ]);

        $gather->say('de dominguez arroba airzone control punto com', [
            'language' => 'es-ES',
            'voice' => 'Polly.Lucia-Neural',
            'rate' => '1'
        ]);

        Log::info('hemos dicho el email');

        return response($response)->header('Content-Type', 'text/xml');
    }
    public function SayYesEmail()
    {
        Log::info('6 sec para el si');

        $response = new VoiceResponse();

        $response->pause(['length' => 6]);

        $gather = $response->gather([
            'input'         => 'speech',
            'timeout'       => '13',
            'action'        => url('/api/SayCompany'),
            'method'        => 'POST',
            'language'      => 'es-ES',
            'speechModel'   => 'googlev2_short',
            'speechTimeout' => '1',
        ]);

        $gather->say('Si', [
            'language' => 'es-ES',
            'voice' => 'Polly.Lucia-Neural',
            'rate' => '1'
        ]);

        Log::info('hemos dicho si');

        return response($response)->header('Content-Type', 'text/xml');
    }
    public function SayCompany()
    {

        $response = new VoiceResponse();

        $response->pause(['length' => 5]);
        Log::info('decimos company');

        $gather = $response->gather([
            'input'         => 'speech',
            'timeout'       => '13',
            'action'        => url('/api/SayYes'),
            'method'        => 'POST',
            'language'      => 'es-ES',
            'speechModel'   => 'googlev2_short',
            'speechTimeout' => '1',
        ]);

        $gather->say('Corporación Empresarial Altra ', [
            'language' => 'es-ES',
            'voice' => 'Polly.Lucia-Neural',
            'rate' => '1'
        ]);

        Log::info('Hemos dicho company');

        return response($response)->header('Content-Type', 'text/xml');
    }
    public function SayYesCompany()
    {

        $response = new VoiceResponse();

        $response->pause(['length' => 5]);
        Log::info('decimos company');

       
        $response->say('Si ', [
            'language' => 'es-ES',
            'voice' => 'Polly.Lucia-Neural',
            'rate' => '1'
        ]);

        Log::info('Hemos dicho company');

        return response($response)->header('Content-Type', 'text/xml');
    }

}
