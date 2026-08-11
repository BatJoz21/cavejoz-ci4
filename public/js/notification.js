const notificationTrigger = document.querySelector('.notification-trigger');
const notificationDropdown = document.querySelector('.notification-dropdown');
const notificationList = document.getElementById('notificationList');
let notificationLoaded = false;

async function loadNotifications() {
    notificationList.innerHTML = '<p class="notification-status">Loading...</p>';

    try {
        const response = await fetch(`${BASE_URL}/notifications/latest`);
        const data = await response.json();
        const items = data['notifications'] ?? [];

        if(items.length === 0) {
            notificationList.innerHTML = '<p class="notification-status">No Notifications yet.</p>';
        } else {
            notificationList.innerHTML = items.map(buildNotificationHtml).join('');
        }
        notificationLoaded = true;
    } catch(error) {
        notificationList.innerHTML = '<p class="notification-status">Loading...</p>';
        notificationLoaded = false;
    }
}

function handleNotification(notif) {
    if(!notificationList) return;

    if(!notificationLoaded) return;

    const placeholder = notificationList.querySelector('.notification-status');
    if (placeholder) {
        notificationList.innerHTML = '';
    }

    notificationList.insertAdjacentHTML('afterbegin', buildNotificationHtml(notif));
}

document.addEventListener('click', (event) => {
    if(!notificationDropdown) return;

    if(event.target.closest('.notification-trigger')) {
        event.stopPropagation();
        const opening = !notificationDropdown.classList.contains('open');
        notificationDropdown.classList.toggle('open', opening);

        if(opening && !notificationLoaded) {
            loadNotifications();
        }
    } else if(!event.target.closest('.notification-dropdown')) {
        notificationDropdown.classList.remove('open');
    }
});