document.querySelectorAll('.alert-close').forEach(btn => {
    btn.addEventListener('click', () => {
        const alert = btn.closest('.alert-panel');
        if (alert) {
            alert.remove();
        }
    });
});
