<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Jobs\SayYesJob;
use Twilio\TwiML\VoiceResponse;

class CallController extends Controller
{
    public $RandInf = []; // Inicializar el array
    public string $filePath;

    public function __construct()
    {
        $this->RandInf = [];
        $this->filePath = '/home/ddominguez/projects/Results.txt';
    }

    public function SayName()
    {

        $name = $this->returnName();
        file_put_contents($this->filePath, "\n". 'Empezamos llamada'."\n". ' - ' .$name . "\n", FILE_APPEND);
                
        
        $response = new VoiceResponse();

        // Introduce a delay of 8.5 seconds
        sleep(8.5);

        // Say "Daniel"
        $gather = $response->gather([
            'input'         => 'speech',
            'timeout'       => '13',
            'action'        => url('/api/SayYes'),
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
    public function SayYes()
    {

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

        Log::info('hemos dicho si al nombre');

        return response($response)->header('Content-Type', 'text/xml');
    }
    public function SayEmail()
    {

        $email = $this->returnEmail();
        file_put_contents($this->filePath, ' - ' .$email . "\n", FILE_APPEND);
                
        $response = new VoiceResponse();

        $response->pause(['length' => 6]);

        $gather = $response->gather([
            'input'         => 'speech',
            'timeout'       => '13',
            'action'        => url('/api/SayYesEmail'),
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
    public function SayYesEmail()
    {

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

        Log::info('hemos dicho si al email');

        return response($response)->header('Content-Type', 'text/xml');
    }
    public function SayCompany()
    {

        $company = $this->returnCompany();
        file_put_contents($this->filePath,  ' - ' . $company. "\n", FILE_APPEND);
                
        
        $response = new VoiceResponse();

        $response->pause(['length' => 5]);

        $gather = $response->gather([
            'input'         => 'speech',
            'timeout'       => '13',
            'action'        => url('/api/SayYesCompany'),
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
    public function SayYesCompany()
    {
        $response = new VoiceResponse();

        $response->pause(['length' => 5]);

        $gather = $response->gather([
            'input'         => 'speech',
            'timeout'       => '13',
            'action'        => url('/api/SayYesCompany'),
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
