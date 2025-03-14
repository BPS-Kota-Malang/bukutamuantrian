<?php

namespace App\Filament\Resources\SpeechAudioResource\Pages;

use App\Filament\Resources\SpeechAudioResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Google\Cloud\TextToSpeech\V1\TextToSpeechClient;
use Google\Cloud\TextToSpeech\V1\VoiceSelectionParams;
use Google\Cloud\TextToSpeech\V1\AudioConfig;
use Google\Cloud\TextToSpeech\V1\SynthesisInput;

class CreateSpeechAudio extends CreateRecord
{
    protected static string $resource = SpeechAudioResource::class;
}
