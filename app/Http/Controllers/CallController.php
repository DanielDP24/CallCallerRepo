<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Twilio\TwiML\VoiceResponse;

class CallController extends Controller
{
    public $RandInf = []; // Inicializar el array
    public string $filePath;

    public function __construct()
    {
        $this->RandInf = [];
        $this->filePath = '/home/ddominguez/projects/Results.csv';
    }

    public function SayName(Request $request)
    {
        $name = $this->returnName();


        $response = new VoiceResponse();

        // Introduce a delay of 8.5 seconds
        sleep(8.5);

        // Say "Daniel"
        $gather = $response->gather([
            'input'         => 'speech',
            'timeout'       => '13',
            'action'        => url()->query("/api/SayYes", [
                'uuid' => $request->input('uuid'),
                'name' => $name
            ]),
            'method'        => 'POST',
            'language'      => 'es-ES',
            'speechModel'   => 'googlev2_short',
            'speechTimeout' => '1',
        ]);

        $gather->say($name, [
            'language' => 'es-ES',
            'voice' => 'Polly.Lucia-Neural',
            'rate' => '1'
        ]);
        Log::info('Decimos el nombre');
        return response($response)->header('Content-Type', 'text/xml');
    }
    public function SayYes(Request $request)
    {
        $response = new VoiceResponse();
        $response->pause(['length' => 6]);

        $gather = $response->gather([
            'input'         => 'speech',
            'timeout'       => '13',
            'action'        => url()->query("/api/SayEmail", [
                'uuid' => $request->input('uuid'),
                'name' => $request->input('name')
            ]),
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

        Log::info('hemos dicho si al nombre');

        return response($response)->header('Content-Type', 'text/xml');
    }
    public function SayEmail(Request $request)
    {
        $email = $this->returnEmail();
        file_put_contents($this->filePath, ' - ' . $email . "\n", FILE_APPEND);

        $response = new VoiceResponse();

        $response->pause(['length' => 8.5]);

        $gather = $response->gather([
            'input'         => 'speech',
            'timeout'       => '13',
            'action'        => url()->query("/api/SayYesEmail", [
                'uuid' => $request->input('uuid'),
                'name' => $request->input('name'),
                'email' => $email
            ]),
            'method'        => 'POST',
            'language'      => 'es-ES',
            'speechModel'   => 'googlev2_short',
            'speechTimeout' => '1',
        ]);

        $gather->say($email, [
            'language' => 'es-ES',
            'voice' => 'Polly.Lucia-Neural',
            'rate' => '1'
        ]);
        Log::info('decimos email');



        return response($response)->header('Content-Type', 'text/xml');
    }
    public function SayYesEmail(Request $request)
    {
        $response = new VoiceResponse();

        $response->pause(['length' => 4]);

        $gather = $response->gather([
            'input'         => 'speech',
            'timeout'       => '13',
            'action'        => url()->query("/api/SayCompany", [
                'uuid' => $request->input('uuid'),
                'name' => $request->input('name'),
                'email' => $request->input('email'),
            ]),
            'method'        => 'POST',
            'language'      => 'es-ES',
            'speechModel'   => 'googlev2_short',
            'speechTimeout' => '1',
        ]);

        $gather->say('Si, es correcto, si es correcto, si es correcto', [
            'language' => 'es-ES',
            'voice' => 'Polly.Lucia-Neural',
            'rate' => '1'
        ]);

        Log::info('hemos dicho si al email');

        return response($response)->header('Content-Type', 'text/xml');
    }
    public function SayCompany(Request $request)
    {

        $company = $this->returnCompany();
        $response = new VoiceResponse();

        $response->pause(['length' => 3]);

        $gather = $response->gather([
            'input'         => 'speech',
            'timeout'       => '13',
            'action'        => url()->query("/api/SayYesCompany", [
                'uuid' => $request->input('uuid'),
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'company' => $company,
            ]),
            'method'        => 'POST',
            'language'      => 'es-ES',
            'speechModel'   => 'googlev2_short',
            'speechTimeout' => '1',
        ]);

        $gather->say($company, [
            'language' => 'es-ES',
            'voice' => 'Polly.Lucia-Neural',
            'rate' => '1'
        ]);
        Log::info('decimos company');

        return response($response)->header('Content-Type', 'text/xml');
    }
    public function SayYesCompany(Request $request)
    {
        $response = new VoiceResponse();

        $response->pause(['length' => 4]);

        $url = url()->query("/api/SayYesCompany", [
            'uuid' => $request->input('uuid'),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'company' => $request->input('company'),
        ]);


        Log::info("información ULTIMA URL -> $url");

        $gather = $response->gather([
            'input'         => 'speech',
            'timeout'       => '13',
            'action'        => url()->query("http://54.247.29.41:8000/api/CreateJson", [
                'uuid' => $request->input('uuid'),
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'company' => $request->input('company'),
            ]),
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
        Log::info('Hemos dicho si a company');

        return response($response)->header('Content-Type', 'text/xml');
    }
    public function returnName(): string
    {
        $filePath = public_path('Nombres.txt');
        if (!file_exists($filePath)) {
            return "Archivo no encontrado";
        }

        $content = file_get_contents($filePath);
        $names = array_map('trim', explode(',', $content));

        if (empty($names)) {
            return "No hay nombres en el archivo";
        }

        return $names[array_rand($names)];
    }
    public function returnCompany(): string
    {
        $filePath = public_path('Empresas.txt');

        if (!file_exists($filePath)) {
            return "Archivo no encontrado";
        }

        $content = file_get_contents($filePath);
        $companies = array_map('trim', explode(',', $content));

        if (empty($companies)) {
            return "No hay empresas en el archivo";
        }

        return $companies[array_rand($companies)];
    }
    public function returnEmail(): string
    {
        $filePath = public_path('Emails.txt');


        if (!file_exists($filePath)) {
            return "Archivo no encontrado";
        }

        $content = file_get_contents($filePath);
        $emails = array_map('trim', explode(',', $content));

        if (empty($emails)) {
            return "No hay emails en el archivo";
        }

        return $emails[array_rand($emails)];
    }
}
