const bioTextarea = document.getElementById('bio');
const bioCounter = document.getElementById('bioCounter');
const BIO_MAX_LENGTH = 150;

if(bioTextarea && bioCounter) {
    const updateCounter = () => {
        const remaining = BIO_MAX_LENGTH - bioTextarea.value.length;
        if(remaining > 1) {
            bioCounter.textContent = `${remaining} characters remaining`;
        } else {
            bioCounter.textContent = `${remaining} character remaining`;
        }
        bioCounter.style.color = remaining <= 10 ? 'var(--cave-danger)' : '#8b8d94';
    };

    updateCounter();

    bioTextarea.addEventListener('input', updateCounter);
}