<?php

namespace App\Filament\Resources\SpeechAudioResource\Pages;

use App\Filament\Resources\SpeechAudioResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSpeechAudio extends EditRecord
{
    protected static string $resource = SpeechAudioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
