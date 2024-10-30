<?php

namespace App\Filament\Pages;

use App\Http\Controllers\WppConnectController;
use App\Models\SecretKey;
use Filament\Pages\Page;
use Filament\Forms;
use Illuminate\Support\Facades\Log;

class WppConnect extends Page
{
    protected static string $view = 'filament.pages.wpp-connect';
    protected static ?string $navigationLabel = 'Whatsapp Server';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'Whatsapp Server';

    public $qrCodeUrl;
    public $connectionStatus; // New property for connection status
    protected $wppConnectController;


    public function mount()
    {
        $wppConnectController = app(WppConnectController::class); // Resolve the controller from the service container

        try {
            $response = $wppConnectController->checkConnectionState();
            // dd($response); // To verify the data structure if needed
            $this->connectionStatus = $response['connected'] ? 'Connected' : 'Disconnected';
            session()->flash('message', $response['message']);
        } catch (\Exception $e) {
            Log::error('Error retrieving connection status: ' . $e->getMessage());
            session()->flash('error', 'Unable to retrieve connection status.');
        }
    }

    public function startSession()
    {
        $wppConnectController = app(WppConnectController::class);

        try {
            $response = $wppConnectController->createSession();

            // Log the response body for debugging
            if ($response instanceof \Illuminate\Http\JsonResponse) {
                Log::info("Create Session Response Body: " . $response->getContent()); // Log the raw JSON response
                $data = $response->getData(true); // Get the data as an array

                // Check if qrCodeUrl is present in the response
                if (isset($data['qrCodeUrl'])) {
                    $this->qrCodeUrl = $data['qrCodeUrl'];
                    session()->flash('message', 'Session started successfully! Scan the QR code.');
                } else {
                    $this->qrCodeUrl = null;
                    Log::warning('QR code URL not found in response.'); // Log warning
                    session()->flash('error', 'QR code URL not found in response.');
                }
            } else {
                Log::error('Failed to start session: Unexpected response type.');
                session()->flash('error', 'Failed to start session: Unexpected response type.');
            }
        } catch (\Exception $e) {
            Log::error('Error starting session: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while starting the session.');
        }
    }


    public function sendMessage(array $data)
    {
        try {
            return $this->wppConnectController->sendMessage($data); // Directly call the controller method
        } catch (\Exception $e) {
            Log::error('Error sending message: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while sending the message.');
        }
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('phone')
                ->label('Phone Number')
                ->required(),
            Forms\Components\Textarea::make('message')
                ->label('Message')
                ->required(),
        ];
    }
}
