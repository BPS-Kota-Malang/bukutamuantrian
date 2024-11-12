{{-- <x-filament-panels::page>
    <div class="mb-4 text-center">
        <h1 class="text-2xl font-bold">BUKU TAMU</h1>
        <h2 class="text-xl">PELAYANAN BPS KOTA MALANG</h2>
    </div> --}}

    {{-- <livewire:transaction-modal /> --}}

    {{-- <x-filament-panels::form wire:submit.prevent="submit"> <!-- Form points to submit method -->
        {{ $this->form }}
    </x-filament-panels::form>
</x-filament-panels::page> --}}


<!-- resources/views/filament/guest/pages/public-transaction.blade.php -->

<x-filament::page>
    <x-filament-panels::form wire:submit.prevent="submit"> <!-- Form points to submit method -->
        {{ $this->form }}
    </x-filament-panels::form>

    <!-- Modal for displaying transaction details -->
    @livewire('transaction-modal')
</x-filament::page>
