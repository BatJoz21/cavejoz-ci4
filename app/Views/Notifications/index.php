<?= $this->extend('Layouts/main-layout') ?>

<?= $this->section('title') ?>Notifications<?= $this->endSection() ?>

<?= $this->section('main') ?>

    <div class="notifications-page">
        <div class="notifications-header">
            <h1 class="notifications-title">Notifications</h1>
            <?= form_open('/notifications/mark-all-read') ?>
                <input type="hidden" name="_method" value="PATCH">
                <button type="submit" class="btn-mark-read">Mark all read</button>
            <?= form_close() ?>
        </div>

        <?php if(!empty($notifications)): ?>
            <div class="notification-list">
                <?php foreach($notifications as $notif): ?>
                    <a href="<?= base_url('/notifications/' . $notif['id'] . '/visit') ?>" class="notification-item <?= ($notif['is_read']) ? '' : 'unread' ?>">
                        <img src="<?= $notif['avatar_url'] && $notif['avatar_url'] !== 'default' ? base_url('/avatar/' . $notif['avatar_url'] ?? 'default') : base_url('assets/img/default_avatar.png') ?>" alt="" class="notification-avatar">
                        <div class="notification-body">
                            <p class="notification-text"><?= esc($notif['preview']) ?></p>
                            <span class="notification-time"><?= esc(get_relative_time($notif['created_at'])) ?></span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="bi bi-bell"></i>
                <p class="empty-title">No Notifications yet.</p>
                <p class="empty-subtitle">Activity on your posts and friend requests will show up here.</p>
            </div>
        <?php endif; ?>
    </div>

<?= $this->endSection() ?>