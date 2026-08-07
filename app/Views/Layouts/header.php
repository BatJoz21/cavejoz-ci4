<header class="cavejoz-header">
    <button id="sidebarToggle" class="header-toggle" aria-label="Toggle sidebar">
        <i class="bi bi-list"></i>
    </button>
    
    <a href="<?= base_url('/') ?>" class="header-brand">
        <i class="bi bi-compass"></i>
        <span>CaveJoz</span>
    </a>
    <a href="<?= base_url('search') ?>" class="header-search-link" aria-label="Search">
        <i class="bi bi-search"></i>
    </a>
    <div class="header-notification" id="notificationMenu">
        <button class="notification-trigger" aria-label="Notifications">
            <i class="bi bi-bell"></i>
            <span class="notif-dot"></span>
        </button>

        <div class="notification-dropdown">
            <div class="notification-dropdown-header">
                <span>Notifications</span>
            </div>

            <div class="notification-list">
                <?php if(!empty($notifications)): ?>
                    <?php foreach($notifications as $notif): ?>
                        <a href="<?= base_url('') ?>" class="notification-item unread">
                            <img src="<?= base_url('/avatar/' . ($notif['avatar_url'] ?? 'default')) ?>" alt="" class="notification-avatar">
                            <div class="notification-body">
                                <p class="notification-text"><strong><?= $notif['username'] ?></strong> <?= $notif['preview'] ?></p>
                                <span class="notification-time"><?= $notif['created_at'] ?></span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <a href="<?= base_url('/notifications') ?>" class="notification-see-all">See all</a>
        </div>
    </div>
</header>