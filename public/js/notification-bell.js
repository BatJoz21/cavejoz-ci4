document.addEventListener('click', async (event) => {
    const trigger = event.target.closest('.notification-trigger');
    const dropdown = document.querySelector('.notification-dropdown');
    if(!dropdown) return;

    if(trigger) {
        event.stopPropagation();
        const isOpening = !dropdown.classList.contains('open');
        dropdown.classList.toggle('open');

        if(isOpening) {
            // mark everything read, then clear the visual indicators
            try {
                await fetch('#', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({ [tokenName]: tokenHash })
                });
                document.querySelector('.notif-dot')?.remove();
                dropdown.querySelectorAll('.notification-item.unread')
                    .forEach(item => item.classList.remove('unread'));
            } catch(error) {}
        } else if(!event.target.closest('.notification-dropdown')) {
            dropdown.classList.remove('open');
        }
    }
});