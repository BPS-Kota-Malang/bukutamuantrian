<?php

namespace App\Filament\Resources\SpeechAudioResource\Pages;

use App\Filament\Resources\SpeechAudioResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSpeechAudio extends ListRecords
{
    protected static string $resource = SpeechAudioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
