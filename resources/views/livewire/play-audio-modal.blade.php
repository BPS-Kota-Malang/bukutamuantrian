{{-- <x-filament-modal id="play-audio-modal">
    <x-slot name="title">
        Play Sound
    </x-slot>

    <x-slot name="subheading">
        This will play the queue announcement.
    </x-slot>

    <x-slot name="footer">
        <x-filament-button wire:click="$emit('closePlayAudioModal')">Close</x-filament-button>
    </x-slot>

    <audio controls>
        @foreach($audioFiles as $audioFile)
            <source src="{{ $audioFile }}" type="audio/mp3">
        @endforeach
        Your browser does not support the audio element.
    </audio>
</x-filament-modal>

@push('scripts')
    <script>
        window.addEventListener('open-play-audio-modal', event => {
            const modal = document.getElementById('play-audio-modal');
            modal.classList.remove('hidden');
            modal.classList.add('block');
        });

        window.addEventListener('closePlayAudioModal', event => {
            const modal = document.getElementById('play-audio-modal');
            modal.classList.remove('block');
            modal.classList.add('hidden');
        });
    </script>
@endpush --}}

{{-- <x-filament::modal id="play-audio" sticky-header>
    <x-slot name="heading">
        Modal heading
    </x-slot>

    <x-slot name="trigger">
        <x-filament::button>
            Open modal
        </x-filament::button>
    </x-slot>

    {{-- Modal content
</x-filament::modal> --}}

<div>
    @if($audioFiles)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                let audioFiles = @json($audioFiles);
                let index = 0;

                function playNext() {
                    if (index >= audioFiles.length) return;

                    let audio = new Audio(audioFiles[index]);
                    audio.play();
                    audio.onended = () => {
                        index++;
                        playNext();
                    };
                }

                playNext();
            });
        </script>
    @endif
</div>


