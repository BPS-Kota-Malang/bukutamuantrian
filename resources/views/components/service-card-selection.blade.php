<div>
    <!-- Breathing in, I calm body and mind. Breathing out, I smile. - Thich Nhat Hanh -->
    <div>
        <h2 class="mb-4 text-xl font-bold">Select a Service</h2>
        <div class="grid grid-cols-3 gap-4">
            @foreach ($meta['services'] as $service)
                <div
                    class="border rounded-lg p-4 text-center cursor-pointer hover:bg-blue-100 {{ $selectedService === $service->id ? 'bg-blue-200' : '' }}"
                    wire:click="$set('selectedService', {{ $service->id }})"
                >
                    <h3 class="text-lg font-bold">{{ $service->name }}</h3>
                </div>
            @endforeach
        </div>

        @if($selectedService)
            <div class="mt-4">
                <p class="font-semibold">You selected: {{ $services->firstWhere('id', $selectedService)?->name }}</p>
            </div>
        @endif
    </div>
</div>
