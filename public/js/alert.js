document.querySelectorAll('.alert-close').forEach(btn => {
    btn.addEventListener('click', () => {
        const alert = btn.closest('.alert-panel');
        if (alert) {
            alert.remove();
        }
    });
});

document.addEventListener('submit', (event) => {
    if(event.target.classList.contains('confirm-delete-form')) {
        if(!confirm('Delete this item? This cannot be undone.')) {
            event.preventDefault();
        }
    }
});