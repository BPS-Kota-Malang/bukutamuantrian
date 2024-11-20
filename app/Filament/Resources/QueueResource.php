<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QueueResource\Pages;
use App\Livewire\PlayAudioModal;
use App\Models\Operator;
use App\Models\Queue;
use App\Models\SpeechAudio;
use App\Services\QueueAnnouncementService;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
// use Filament\Forms\Components\View;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
// use Illuminate\View;
use Livewire\Livewire;

class QueueResource extends Resource
{
    protected static ?string $model = Queue::class;

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
            ->columns([
                TextColumn::make('transaction.customer.name')
                    ->label('Nama Pengguna Layanan'),
                TextColumn::make('transaction.service.name')
                    ->label('Nama Layanan'),
                TextColumn::make('date')
                    ->label('Tanggal Pelayanan')
                    ->sortable('date')
                    ->alignCenter(),
                SelectColumn::make('operator_id')
                    ->options(
                        Operator::all()->pluck('name', 'id')
                    ),
                TextColumn::make('number')
                    ->label('Nomor Antrian')
                    ->alignCenter(),
                SelectColumn::make('status')
                    ->options([
                        'queue' => 'Queue',
                        'onprocess' => 'On Process',
                        'done' => 'Done',
                    ])
            ])
            ->filters([
                Filter::make('status')
                        ->label('Filter by Status')
                        ->form([
                            Select::make('status')
                                ->options([
                                    'queue' => 'Queue',
                                    'onprocess' => 'On Process',
                                    'done' => 'Done',
                                ])
                                ->placeholder('All Statuses'),
                        ])
                        ->query(function (Builder $query, array $data): Builder {
                            return $data['status']
                                ? $query->where('status', $data['status'])
                                : $query;
                        }),
                    Filter::make('date')
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
                Tables\Actions\Action::make('play-sound')
                    ->label('Play Sound')
                    ->icon('heroicon-o-play')
                    ->modalContent(
                        (function (Queue $record) {
                            // You can handle any logic needed before showing the modal here
                            // For example, fetching audio files for the announcement
                            $announcementService = app(QueueAnnouncementService::class);
                            $audioFiles = $announcementService->getAnnouncementAudioFiles($record);

                            // Dispatch the event or update the Livewire component with the audio files
                            // You may dispatch an event to play the audio and show the modal
                            // $this->emit('play-queue-announcement', $audioFiles);

                            // Show the modal with the content
                            return view('livewire.queue-announcement', [
                                'record' => $record,
                                'audioFiles' => $audioFiles
                            ]);
                        })
                        // fn(Queue $record) : View => view('livewire.queue-announcement', [ 'record' => $record ])
                    )

                    // ->modalSize('lg')
                    ->modalSubmitAction(false)
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }


    protected function getTableActions(): array
    {
        return [

        ];
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
            'index' => Pages\ListQueues::route('/'),
            'create' => Pages\CreateQueue::route('/create'),
            'edit' => Pages\EditQueue::route('/{record}/edit'),
        ];
    }
}
