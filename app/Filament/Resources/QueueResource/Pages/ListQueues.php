<?php
namespace App\Filament\Resources\QueueResource\Pages;
use App\Filament\Resources\QueueResource;
// use App\Livewire\QueueAnnouncement;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListQueues extends ListRecords
{
    protected static string $resource = QueueResource::class;
    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            // QueueAnnouncement::class,
        ];
    }
}
