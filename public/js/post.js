// Post menu trigger
document.addEventListener('click', (event) => {
    const trigger = event.target.closest('.post-menu-trigger');

    document.querySelectorAll('.post-menu-dropdown.open').forEach(dropdown => {
        if(dropdown !== trigger?.nextElementSibling) {
            dropdown.classList.remove('open');
        }
    });

    if(trigger) {
        trigger.nextElementSibling.classList.toggle('open');
    }
});