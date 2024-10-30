<?php

namespace App\Filament\Resources\SecretKeyResource\Pages;

use App\Filament\Resources\SecretKeyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSecretKeys extends ListRecords
{
    protected static string $resource = SecretKeyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
