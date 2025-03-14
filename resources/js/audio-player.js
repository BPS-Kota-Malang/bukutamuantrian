document.addEventListener('DOMContentLoaded', () => {
    Livewire.on('playAudio', (audioFiles) => {
        const audio = new Audio();
        let index = 0;

        const playNext = () => {
            if (index < audioFiles.length) {
                audio.src = audioFiles[index];
                audio.play();
                index++;
            } else {
                console.log('All audio files played.');
            }
        };

        audio.addEventListener('ended', playNext);
        playNext(); // Start playing the first file
    });
});
