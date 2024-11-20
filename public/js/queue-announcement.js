document.addEventListener('DOMContentLoaded', function () {
    console.log('Script loaded!');
    const audioPlaylist = document.getElementById('audio-playlist');
    const audioFiles = Array.from(audioPlaylist.children);
    const audioPlayer = new Audio();

    let currentAudioIndex = 0;

    document.getElementById('start-audio-btn').addEventListener('click', function () {
        playNextAudio();
    });

    function playNextAudio() {
        if (currentAudioIndex >= audioFiles.length) {
            return; // No more audio files to play
        }

        const currentAudioFile = audioFiles[currentAudioIndex];
        const audioSrc = currentAudioFile.getAttribute('data-src');

        audioPlayer.src = audioSrc;
        audioPlayer.play();

        audioPlayer.addEventListener('ended', function () {
            currentAudioIndex++;
            playNextAudio();
        });
    }
});
