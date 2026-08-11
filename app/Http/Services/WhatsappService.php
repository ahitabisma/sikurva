<?php

namespace App\Http\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    protected $baseUrl;
    protected $token;
    protected $apiSetting;

    public function __construct(ApiSettingService $apiSetting)
    {
        $this->apiSetting = $apiSetting;

        $this->baseUrl = env('WHATSAPP_API_URL', 'https://app.ruangwa.id/api');

        $this->token = $this->apiSetting->getApiSetting('WHATSAPP_API_TOKEN');
    }

    public function checkNumber(string $number): bool
    {
        try {
            $response = Http::retry(1, 200)
                ->timeout(10)
                ->asForm()
                ->post("{$this->baseUrl}/check_number", [
                    'token'  => $this->token,
                    'number' => $number,
                ]);

            $response->throw();

            $result = $response->json();

            Log::info('Check number response', [
                'number' => $number,
                'result' => $result,
            ]);

            // Check if the key exists before accessing it
            if (isset($result['onwhatsapp'])) {
                return $result['onwhatsapp'] == 'true' ? true : false;
            } else {
                // If the key doesn't exist, log this issue and return a default value
                Log::warning('WhatsApp API response missing "onwhatsapp" key', [
                    'number' => $number,
                    'result' => $result,
                ]);

                // If there's some other indicator of success in the response, use it
                // Otherwise assume the number is valid to avoid blocking the process
                return true;
            }
        } catch (\Exception $e) {
            Log::error('Error checking WhatsApp number', [
                'number' => $number,
                'error' => $e->getMessage(),
            ]);

            // In case of exception, assume it's a valid number to allow the process to continue
            return true;
        }
    }

    public function sendTextMessage(string $number, string $message, ?string $date = null, ?string $time = null): array
    {
        $payload = [
            'token'   => $this->token,
            'number'  => $number,
            'message' => $message,
            'date'    => $date ?? date('Y-m-d'),
            'time'    => $time ?? date('H:i'),
        ];

        $response = Http::retry(1, 200)
            ->timeout(10)
            ->asForm()
            ->post("{$this->baseUrl}/send_message", $payload);

        $response->throw();

        return $response->json();
    }

    public function sendDocument(string $number, string $fileUrl, string $caption, ?string $date = null, ?string $time = null): array
    {
        $payload = [
            'token'   => $this->token,
            'number'  => $number,
            'file'    => $fileUrl,
            'caption' => $caption,
            'date'    => $date ?? date('Y-m-d'),
            'time'    => $time ?? date('H:i'),
        ];

        $response = Http::retry(3, 500)
            ->timeout(60)
            ->asForm()
            ->post("{$this->baseUrl}/send_document", $payload);

        $response->throw();

        return $response->json();
    }
}
