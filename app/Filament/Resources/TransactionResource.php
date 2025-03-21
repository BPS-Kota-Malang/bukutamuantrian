<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Main';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Transaction::query()->orderBy('created_at', 'desc'))
            ->columns([
                TextColumn::make('customer.name')
                    ->label('Nama Pengguna'),
                TextColumn::make('sub_method.name')
                    ->label('Media Pelayanan'),
                TextColumn::make('queue_id')
                    ->label('Queue ID')
                    ->getStateUsing(fn ($record) => $record->queue ? $record->queue->id : '-') // Checks for queue before accessing ID
                    ->sortable(),
                TextColumn::make('service.name')
                    ->label('Layanan'),
                TextColumn::make('purpose.name')
                    ->label('Tujuan'),
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->date('d-m-Y')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->color(fn (string $state): string => match ($state) {
                        'Queue' => 'warning',
                        'Completed' => 'success',
                    }),
                SelectColumn::make('status')
                    ->options([
                        'Queue' => 'Queue',
                        'Completed' => 'Completed',
                    ])
                    ->afterStateUpdated(function ($state, $record) {
                        // This ensures status updates trigger the event
                        $record->update(['status' => $state]);
                    })
                    ->sortable()
                    ->searchable(),
                    // ->order(0),
            ])
            // ->orderBy('created_at')
            ->filters([
                Filter::make('status')
                        ->label('Filter by Status')
                        ->form([
                            Select::make('status')
                                ->options([
                                    'Queue' => 'Queue',
                                    'Completed' => 'Completed',
                                ])
                                ->placeholder('All Statuses'),
                        ])
                        ->query(function (Builder $query, array $data): Builder {
                            return $data['status']
                                ? $query->where('status', $data['status'])
                                : $query;
                        }),
                    Filter::make('created_at')
                        ->label('Filter by Date')
                        ->form([
                                DatePicker::make('date')->label('Tanggal'),
                            ])
                        ->query(function (Builder $query, array $data): Builder {
                            return $data['date']
                                ? $query->whereDate('date', $data['date'])
                                : $query;
                        }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make(),
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
