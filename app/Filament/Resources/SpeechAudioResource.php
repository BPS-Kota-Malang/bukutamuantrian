<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpeechAudioResource\Pages;
use App\Filament\Resources\SpeechAudioResource\RelationManagers;
use App\Models\SpeechAudio;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\FileUpload;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Livewire;

class SpeechAudioResource extends Resource
{
    protected static ?string $model = SpeechAudio::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Audio';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('text')
                    ->required()
                    ->label('Text to convert'),
                TextInput::make('filename')
                    ->required()
                    ->label('Filename'),
                FileUpload::make('audiopath')
                    ->required()
                    ->preserveFilenames()
                    ->label('Upload Audio')
                    ->uploadingMessage('Uploading attachment...')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('text'),
                TextColumn::make('filename'),
                TextColumn::make('audiopath')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSpeechAudio::route('/'),
            'create' => Pages\CreateSpeechAudio::route('/create'),
            'edit' => Pages\EditSpeechAudio::route('/{record}/edit'),
        ];
    }
}
