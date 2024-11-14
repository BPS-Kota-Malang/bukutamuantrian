<?php

namespace App\Filament\Resources\SecretKeyResource\Pages;

use App\Filament\Resources\SecretKeyResource;
use App\Models\SecretKey;
use App\Services\WhatsappService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Pages\Actions\Modal\Actions\ButtonAction;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class CreateSecretKey extends CreateRecord
{
    protected static string $resource = SecretKeyResource::class;

    protected function beforeSave(): void
    {
        // Check if a SecretKey already exists
        if (SecretKey::count() > 0) {
            // Redirect to the edit page if a key already exists
            throw new ModelNotFoundException("A secret key already exists. Please edit the existing key.");
        }
    }

    protected function getActions(): array
    {
        return [
            Action::make('generateToken')
                ->label('Generate Token')
                ->action('generateToken')
                ->color('primary'),
        ];
    }

    public function generateToken(): void
    {
        try {
            $data = $this->form->getState();
            $sessionName = $data['session_name'];
            $secretKey = $data['key'];
            $server_host_url = $data['server_host_url'];

            // Instantiate the WhatsappService and generate the token
            $whatsappService = new WhatsappService();

            $token = $whatsappService->generateToken( $sessionName, $secretKey);

            // dd($token);
            // Save or update the token in the database
            SecretKey::updateOrCreate(
                [
                    'session_name' => $token['session']
                ],
                [
                    'key' => $secretKey,
                    'token' => $token['token'],
                    'server_host_url' => $server_host_url
                ]
            );

            Notification::make()
                ->title('Success')
                ->body('Token generated and saved successfully!')
                ->success()
                ->send();

            $this->redirect(SecretKeyResource::getUrl('index'));
        } catch (\Exception $e) {
            Log::error('Error generating token: ' . $e->getMessage());

            Notification::make()
                ->title('Error')
                ->body('Failed to generate token: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

}
