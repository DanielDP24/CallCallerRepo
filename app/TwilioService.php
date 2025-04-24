<?php

namespace App;

use Illuminate\Support\Facades\Log;
use Twilio\TwiML\VoiceResponse;

class TwilioService
{
    /**
     * Create a new class instance.
     */

    private readonly Voiceresponse $response;
    public function __construct()
    {
        $this->response = new VoiceResponse;
    }
    public function response(): VoiceResponse
    {
        return $this->response;
    }
    public function laravelResponse()
    {
        return response($this->response->__toString(), 200)->header('Content-Type', 'text/xml');;
    }
    public function gather(string $action, string $hints = '', ?string $speech = null): void
    {

        Log::info('action es : ' . $action);

        $gather = $this->response->gather([
            'input' => 'speech',
            'timeout' => '6',
            'action' => $action,
            'method' => 'POST',
            'language' => 'es-ES',
            'speechModel' => 'googlev2_short',
            'speechTimeout' => '1',
            'actionOnEmptyResult' => true,
            'hints' => $hints,
        ]);

        if ($speech !== null) {
            $gather->say($speech, $this->voiceConfig());
        }
    }
    public function say(string $speech)
    {
        $this->response->say($speech, $this->voiceConfig());
    }
    private function voiceConfig(): array
    {
        return [
            'language' => 'es-ES',
            'voice' => 'Google.es-ES-Chirp3-HD-Zephyr',
            'rate' => '1'
        ];
    }
}
