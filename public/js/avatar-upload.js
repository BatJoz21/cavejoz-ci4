const avatarTrigger = document.getElementById('avatarTrigger');
const avatarInput = document.getElementById('avatar');
const avatarFileName = document.getElementById('avatarFileName');

if (avatarTrigger && avatarInput && avatarFileName) {
    avatarTrigger.addEventListener('click', () => {
        avatarInput.click();
    });

    avatarInput.addEventListener('change', () => {
        if (avatarInput.files.length > 0) {
            avatarFileName.textContent = avatarInput.files[0].name;
        } else {
            avatarFileName.textContent = 'No file choosen';
        }
    });
}