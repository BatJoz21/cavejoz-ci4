// Post caption text counter
const captionTextArea = document.getElementById('caption');
const captionCounter = document.getElementById('captionCounter');
const CAPTION_MAX_LENGTH = 500;

if(captionTextArea && captionCounter) {
    const updateCaptionCounter = () => {
        const remaining = CAPTION_MAX_LENGTH - captionTextArea.value.length;
        if(remaining > 1) {
            captionCounter.textContent = `${remaining} characters remaining`;
        } else {
            captionCounter.textContent = `${remaining} character remaining`;
        }
        captionCounter.style.color = remaining <= 10 ? 'var(--cave-danger)' : '#8b8d94';
    };

    updateCaptionCounter();

    captionTextArea.addEventListener('input', updateCaptionCounter);
}