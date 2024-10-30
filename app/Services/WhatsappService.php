<?php

namespace App\Services;

use App\Models\SecretKey;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    protected $baseUrl;
    protected $secretKey;

    public function __construct()
    {
        $this->baseUrl = env('WPP_CONNECT_BASE_URL');
    }

    public function getSecretKey()
    {
        // Logic to retrieve the secret key
        return SecretKey::first();
    }

    public function createSession()
    {
        $secretKey = $this->getSecretKey();

        if (!$secretKey) {
            Log::error("Secret key not found.");
            return [
                'message' => 'Secret key not found.',
                'error' => 'Secret key is missing from the database.',
            ];
        }

        // Construct full session name and URL
        $fullSessionName = "{$secretKey->session_name}:{$secretKey->token}";
        $encodedSessionName = urlencode($fullSessionName);
        // dd($encodedSessionName);
        // dd($fullSessionName);
        $url = "{$this->baseUrl}/api/$encodedSessionName/start-session";

        // Construct full session name and URL
        // $fullSessionName = $secretKey->session_name . ":" . $secretKey->token;
        // $url = "{$this->baseUrl}/api/$fullSessionName/start-session";

        Log::info("Request URL: " . $url);

        $response = Http::post($url, [
            'webhook' => '',  // Consider adding a valid webhook URL if needed
            'waitQrCode' => false,
        ]);

        // dd($response);
        Log::info("Response Body: " . $response->body()); // Log the response body

        if ($response->successful()) {
            $responseData = $response->json(); // Get the full response data
            Log::info("Response Data: " . json_encode($responseData)); // Log the full response data
            // dd($responseData);

            // You can process the response as needed
            $qrCodeUrl = $responseData['qrcode'] ?? null;

            return [
                'message' => 'Session created successfully!',
                'qrCodeUrl' => $qrCodeUrl,
                'full_response' => $responseData, // Optionally return the full response
            ];
        } else {
            // Handle the error response
            Log::error("Failed to create session: " . $response->body()); // Log error message
            return [
                'message' => 'Failed to create session.',
                'error' => $response->body(),
            ];
        }

    }


    public function checkConnectionState()
    {
        // Retrieve the first SecretKey record
        $secretKey = $this->getSecretKey();

        if (!$secretKey) {
            throw new \Exception("Secret key not found.");
        }

        // Construct full session name and URL
        $fullSessionName = $secretKey->session_name . ":" . $secretKey->token;
        $encodedSessionName = urlencode($fullSessionName);
        // dd($encodedSessionName);
        // dd($fullSessionName);
        $url = "{$this->baseUrl}/api/$encodedSessionName/check-connection-session";
        // dd($url);
        try {
            $response = Http::withHeaders([
                'accept' => '*/*',
                'Content-Type' => 'application/json',
            ])->get($url);

            // Check if the response was successful
            if ($response->successful()) {
                $data = $response->json();

                // dd($data);
                // Log the response for debugging
                Log::info("Connection state response: ", $data);

                // Check the connection status in the response
                if (isset($data['status']) && $data['status'] === true) {
                    return ['connected' => true, 'message' => $data['message'] ?? 'Connected'];
                } else {
                    return ['connected' => false, 'message' => $data['message'] ?? 'Disconnected'];
                }
            } else {
                throw new \Exception('Failed to check connection: ' . ($response->json()['message'] ?? 'Unknown error'));
            }
        } catch (\Exception $e) {
            Log::error('Error checking connection status: ' . $e->getMessage());
            return ['connected' => false, 'message' => 'Error checking connection status'];
        }
    }



    public function sendMessage(Array $data)
    {
        $secretKey = $this->getSecretKey();

        if (!$secretKey) {
            throw new \Exception("Secret key not found.");
        }

        // Construct full session name and URL
        $fullSessionName = $secretKey->session_name . ":" . $secretKey->token;
        $encodedSessionName = urlencode($fullSessionName);

        $url = "{$this->baseUrl}/api/{$encodedSessionName}/send-message";

        $data = [
            'phone' => $data['phone'],
            'isGroup' => false,
            'isNewsletter' => false,
            'message' => $data['message'],
        ];

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post($url, $data);

        if ($response->successful()) {
            return $response->json();
        } else {
            $errorMessage = $response->json()['message'] ?? 'Unknown error';
            throw new \Exception("Failed to send message: $errorMessage");
        }
    }

}
