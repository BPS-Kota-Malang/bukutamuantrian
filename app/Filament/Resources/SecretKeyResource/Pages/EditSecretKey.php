<?php

namespace App\Filament\Resources\SecretKeyResource\Pages;

use App\Filament\Resources\SecretKeyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSecretKey extends EditRecord
{
    protected static string $resource = SecretKeyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return route('filament.resources.secret-keys.index'); // Redirect to the index or any other page
    }
}
