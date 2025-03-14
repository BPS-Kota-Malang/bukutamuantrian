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

@if (is_array($audioFiles))
    @foreach ($audioFiles as $audioFile)
        <audio controls>
            <source src="{{ $audioFile }}" type="audio/mpeg">
            Your browser does not support the audio element.
        </audio>
    @endforeach
@else
    <p>No audio files available.</p>
@endif


{{-- <audio id="audio" style="display: none;">
    <source src="" type="audio/mpeg">
    Your browser does not support the audio element.
</audio>

<button id="play-button" >Play Audio Anonuncement</button> --}}



