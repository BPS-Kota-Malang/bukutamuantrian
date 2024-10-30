<?php

namespace App\Filament\Resources\SubMethodResource\Pages;

use App\Filament\Resources\SubMethodResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubMethods extends ListRecords
{
    protected static string $resource = SubMethodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
