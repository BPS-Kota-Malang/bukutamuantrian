<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SecretKeyResource\Pages;
use App\Models\SecretKey;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SecretKeyResource extends Resource
{
    protected static ?string $model = SecretKey::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Whatsapp Server';

    public static function form(Form $form): Form
    {
        $existingKey = SecretKey::first();

        return $form
            ->schema([
                Forms\Components\TextInput::make('key')
                    ->required()
                    ->label('Masukkan Secret Key')
                    ->default($existingKey ? $existingKey->key : null)
                    ->disabled($existingKey !== null),
                Forms\Components\TextInput::make('session_name')
                    ->required()
                    ->label('Session Name')
                    ->placeholder('Enter session name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')
                    ->label('Secret Key'),
                TextColumn::make('session_name')
                    ->label('Session Name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->disabled(SecretKey::count() === 0),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSecretKeys::route('/'),
            'create' => Pages\CreateSecretKey::route('/create'),
            'edit' => Pages\EditSecretKey::route('/{record}/edit'),
        ];
    }
}
