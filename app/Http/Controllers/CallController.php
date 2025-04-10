<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Prism;
use Prism\Prism\Schema\ObjectSchema;
use Prism\Prism\Schema\StringSchema;
use Twilio\TwiML\VoiceResponse;

class CallController extends Controller
{
    public $RandInf = []; // Inicializar el array
    public string $filePath;
    private string $uuid;


    public function __construct(private DatabaseController $DatabaseController)
    {
        $this->RandInf = [];
        $this->filePath = '/home/ddominguez/projects/Results.csv';
        $this->uuid = request()->input('uuid', '');
    }

    public function SayName(Request $request)
    {
        $name = strtolower($this->returnName());

        $this->DatabaseController->insertField('name_given', $name);

        $response = new VoiceResponse();

        // Introduce a delay of 8.5 seconds
        sleep(8.5);

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
        Log::info('Decimos el nombre' . $name);
        return response($response)->header('Content-Type', 'text/xml');
    }
    public function SayYes(Request $request)
    {
        $response = new VoiceResponse();
        $response->pause(['length' => 5]);

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
        $email =  strtolower($this->returnEmail());
        $this->DatabaseController->insertField('email_given', $email);

        file_put_contents($this->filePath, ' - ' . $email . "\n", FILE_APPEND);
        $second = $request->input('second') ?? '';

        //si está lleno 
        if (!empty($second)) {
            $email = strtolower($request->input('email_given'));
        } else {

            $schema = new ObjectSchema(
                name: 'email_transcription',
                description: 'Structured email transcription',
                properties: [
                    new StringSchema('readable_email', 'Email formatted for text-to-speech readability')
                ],
                requiredFields: ['readable_email']
            );

            //LÓGICA DE CORECCIÓN DE EMAIL AI

            $prompt = <<<EOT
        You are an advanced email transcription proofreader.
        Your job is to convert this email into a readable email, for example.
        Its VERY IMPORTANT not to create any word to complete or improve the email, just convert the words given, but NEVER invent ANY WORD not given by the user.

        - Replace symbols with their correct spoken words:
          - "@" → "arroba"
          - ".com" → "punto com" 
          - "." → "punto"
          - etc
       
        **Your Task Generate a Readable Version for TTS**
        - Convert the email into a version optimized for text-to-speech (TTS):
          - The "@" symbol should be spoken as "arroba".
          - The ".com" should be spoken as "punto com".
          - The username and domain should be spaced clearly to enhance pronunciation.
        
        **Example Output:**
        -   If the email provided is= "ddominguez@airzonecontrol.com"
            You should return= "de domínguez arroba airzone control punto com"
        -   If the email provided is= "cielo_azul@cieloazul.org"
            You should return= "cielo barra baja azul arroba cielo azul punto o erre ge"
        -   If the email provided is= "jesusgonzalez@ijg.es"
            You should return= "jesus gonzalez arroja i jota ge punto es"

        The provided email snippet: "$email"
        EOT;

            $response = Prism::structured()
                ->using(Provider::OpenAI, 'gpt-4o')
                ->withSchema($schema)
                ->withPrompt($prompt)
                ->asStructured();
            Log::info(json_encode($response->structured, JSON_PRETTY_PRINT));

            $email = $response->structured['readable_email'];
            Log::info("decimos email $email XXXXXXXXXXXXXXXXXXXXX");

        }


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
        Log::info("decimos email $email");

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

        $company =  strtolower($this->returnCompany());
        $response = new VoiceResponse();

        if (!empty($second)) {
            $company = strtolower($request->input('company_given'));
        } else {
            $this->DatabaseController->insertField('company_given', $company);
        }
        $response->pause(['length' => 5]);

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

        $response->pause(['length' => 5]);

        $gather = $response->gather([
            'input'         => 'speech',
            'timeout'       => '13',
            'action'        => '',
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

        $filePath = public_path('ClientesIberia_Limpio.json');

        if (!file_exists($filePath)) {
            return "Archivo no encontrado";
        }

        // Leer y decodificar JSON
        $content = file_get_contents($filePath);
        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE || empty($data)) {
            return "Error en el JSON o sin datos";
        }

        // Extraer todos los nombres
        $names = array_column($data, 'name');

        if (empty($names)) {
            return "No hay nombres en el archivo";
        }

        // Seleccionar un nombre aleatorio
        return $names[array_rand($names)];
    }
    public function returnCompany(): string
    {
        $filePath = public_path('ClientesIberia_Limpio.json');

        if (!file_exists($filePath)) {
            return "Archivo no encontrado";
        }

        // Leer y decodificar JSON
        $content = file_get_contents($filePath);
        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE || empty($data)) {
            return "Error en el JSON o sin datos";
        }

        // Extraer todos los nombres
        $companys = array_column($data, 'company');

        if (empty($companys)) {
            return "No hay nombres en el archivo";
        }

        // Seleccionar un nombre aleatorio
        return $companys[array_rand($companys)];
    }
    public function returnEmail(): string
    {
        $filePath = public_path('ClientesIberia_Limpio.json');

        if (!file_exists($filePath)) {
            return "Archivo no encontrado";
        }

        // Leer y decodificar JSON
        $content = file_get_contents($filePath);
        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE || empty($data)) {
            return "Error en el JSON o sin datos";
        }

        // Extraer todos los nombres
        $emails = array_column($data, 'email');

        if (empty($emails)) {
            return "No hay nombres en el archivo";
        }

        // Seleccionar un nombre aleatorio
        return $emails[array_rand($emails)];
    }
}
