{{-- @if (is_array($audioFiles) && count($audioFiles) > 0)
    <div id="audio-playback-container">
        <p>Click to start playing the announcement...</p>
        <!-- Hidden list of audio files for JavaScript to process -->
        <ul id="audio-playlist" style="display:block;">
            @foreach ($audioFiles as $audioFile)
                <li data-src="{{ asset($audioFile) }}"></li>
            @endforeach
        </ul>

        <!-- Button to start the audio playback -->
        <button id="start-audio-btn">Start Audio Playback</button>
    </div>
@else
    <p>No audio files available.</p>
@endif --}}

{{-- @if (is_array($audioFiles))
    @foreach ($audioFiles as $audioFile)
        <audio controls>
            <source src="{{ $audioFile }}" type="audio/mpeg">
            Your browser does not support the audio element.
        </audio>
    @endforeach
@else
    <p>No audio files available.</p>
@endif
 --}}

{{-- <audio id="audio" style="display: none;">
    <source src="" type="audio/mpeg">
    Your browser does not support the audio element.
</audio>

<button id="play-button" >Play Audio Anonuncement</button> --}}
@if (is_array($audioFiles) && count($audioFiles) > 0)
    <div id="audio-playback-container">
        <p>Click to start playing the announcement...</p>
        <!-- Hidden list of audio files for JavaScript to process -->
        <ul id="audio-playlist" style="display:none;">
            @foreach ($audioFiles as $audioFile)
                <li data-src="{{ asset($audioFile) }}"></li>
            @endforeach
        </ul>

        <!-- Button to start the audio playback -->
        <button id="start-audio-btn">Start Audio Playback</button>
    </div>
@else
    <p>No audio files available.</p>
@endif

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            console.log("Script loaded!");
            const audioPlaylist = document.getElementById("audio-playlist");
            const audioFiles = Array.from(audioPlaylist.children);
            const audioPlayer = new Audio();

            let currentAudioIndex = 0;

            document.getElementById("start-audio-btn").addEventListener("click", function() {
                playNextAudio();
            });

            function playNextAudio() {
                if (currentAudioIndex >= audioFiles.length) {
                    return; // No more audio files to play
                }

                const currentAudioFile = audioFiles[currentAudioIndex];
                const audioSrc = currentAudioFile.getAttribute("data-src");

                audioPlayer.src = audioSrc;
                audioPlayer.play();

                audioPlayer.addEventListener("ended", function() {
                    currentAudioIndex++;
                    playNextAudio();
                });
            }
        });
    </script>
@endpush



