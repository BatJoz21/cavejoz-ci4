const avatarTrigger = document.getElementById('avatarTrigger');
const avatarInput = document.getElementById('avatar');
const avatarFileName = document.getElementById('avatarFileName');
const avatarPreview = document.getElementById('avatarPreview');

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

// Live preview for edit profile
if(avatarTrigger && avatarInput && avatarFileName && avatarPreview) {
    avatarTrigger.addEventListener('click', () => {
        avatarInput.click();
    });

    avatarInput.addEventListener('change', () => {
        if(avatarInput.files.length > 0) {
            const file = avatarInput.files[0];
            avatarFileName.textContent = file.name;

            const reader = new FileReader();
            reader.onload = (event) => {
                avatarPreview.src = event.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
} else {
    avatarFileName.textContent = 'No new file chosen';
}
