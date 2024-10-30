<x-filament::page>

    @if ($connectionStatus !== 'Connected')
        <div>
            <x-filament::button wire:click="startSession" color="success">
                Start WPPConnect Session
            </x-filament::button>
        </div>

        @if ($qrCodeUrl)
            <div class="mt-4">
                <h2 class="text-xl">Scan the QR Code</h2>
                <img src="{{ $qrCodeUrl }}" alt="QR Code" class="mt-2" />
            </div>
        @endif
    @endif

    <!-- Connection Status Label -->
    <div class="flex items-center mt-4">
        <span class="inline-block px-3 py-1 font-semibold text-sm rounded-full
            {{ $connectionStatus === 'Connected' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
            {{ $connectionStatus }}
        </span>

        @if ($connectionStatus === 'Connected')
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        @else
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        @endif
    </div>

    <!-- Display success or error messages -->
    @if (session()->has('message'))
        <div class="p-4 mt-4 text-white bg-green-500 rounded">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="p-4 mt-4 text-white bg-red-500 rounded">
            {{ session('error') }}
        </div>
    @endif
</x-filament::page>
