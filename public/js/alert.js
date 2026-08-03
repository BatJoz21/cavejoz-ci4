document.querySelectorAll('.alert-close').forEach(btn => {
    btn.addEventListener('click', () => {
        const alert = btn.closest('.alert-panel');
        if (alert) {
            alert.remove();
        }
    });
});

document.addEventListener('submit', (event) => {
    if(event.target.classList.contains('post-delete-form')) {
        if(!confirm('Delete this post? This cannot be undone.')) {
            event.preventDefault();
        }
    }
});