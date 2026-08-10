<div class="header-notification" id="notificationMenu">
    <button class="notification-trigger" aria-label="Notifications">
        <i class="bi bi-bell"></i>
        <?php if(!empty($unreadCount) && $unreadCount > 0): ?>
            <span class="notif-dot"></span>
        <?php endif; ?>
    </button>

    <div class="notification-dropdown">
        <div class="notification-dropdown-header"><span>Notifications</span></div>
        <div class="notification-list" id="notificationList">
            <p class="notification-status">Loading...</p>
        </div>
        <a href="<?= base_url('/notifications') ?>" class="notification-see-all">See all</a>
    </div>
</div>