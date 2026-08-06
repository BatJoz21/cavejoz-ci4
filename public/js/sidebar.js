const sideBarToggle = document.getElementById('sidebarToggle');
const sideBar = document.querySelector('.cavejoz-sidebar');
const mainContent = document.querySelector('.main-content');
const backDrop = document.getElementById('sidebarBackdrop');

function setSidebar(open) {
    sideBar.classList.toggle('collapsed', !open);
    mainContent.classList.toggle('sidebar-collapsed', !open);
    if(backDrop) backDrop.classList.toggle('visible', open);
}

if(sideBarToggle && sideBar && mainContent) {
    sideBarToggle.addEventListener('click', () => {
        setSidebar(sideBar.classList.contains('collapsed'));
    });
}

if(backDrop) {
    backDrop.addEventListener('click', () => setSidebar(false));
}