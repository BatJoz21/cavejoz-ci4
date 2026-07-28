document.addEventListener('DOMContentLoaded', () => {
    const sideBarToggle = document.getElementById('sidebarToggle');
    const sideBar = document.querySelector('.cavejoz-sidebar');
    const mainContent = document.querySelector('.main-content');

    if(sideBarToggle && sideBar && mainContent) {
        sideBarToggle.addEventListener('click', () => {
            sideBar.classList.toggle('collapsed');
            mainContent.classList.toggle('sidebar-collapsed');
        });
    }
});