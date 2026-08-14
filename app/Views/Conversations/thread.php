<?= $this->extend('Layouts/main-layout') ?>

<?= $this->section('title') ?>Title<?= $this->endSection() ?>

<?= $this->section('main') ?>

    <div class="thread-page">
    <?php if(!empty($conversation)): ?>
        <div class="thread-header">
            <a href="<?= base_url('/chat') ?>" class="thread-back" aria-label="Back">
                <i class="bi bi-arrow-bar-left"></i>
            </a>
            <img src="<?= base_url('/avatar/' . ($conversation['avatar_url'] ?? 'default')) ?>" alt="" class="thread-avatar">
            <a href="<?= base_url('/profile/' . $conversation['username']) ?>" class="thread-username"><?= esc($conversation['username']) ?></a>
        </div>

        <div class="thread-messages" id="threadMessages">
        <?php if(!empty($messages)): ?>
                <?php foreach($messages as $m): ?>
                    <div class="message-item <?= ($m['sender_id'] == session('user')['id']) ? 'own' : '' ?>">
                        <div class="message-bubble">
                            <p class="message-text"><?= esc($m['content']) ?></p>
                            <span class="message-time"><?= date('H:i', strtotime($m['created_at'])) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="bi bi-chat-dots"></i>
                <p class="empty-title">No message yet</p>
                <p class="empty-subtitle">Start send message to your friend</p>
            </div>
        <?php endif; ?>
        </div>

        <div class="thread-input-wrapper">
            <form method="post" action="<?= base_url('/chat/' . $conversation['id'] . '/message') ?>" id="messageForm" class="thread-form">
                <?= csrf_field() ?>
                <input type="text" name="messageInput" id="messageInput" class="thread-input" placeholder="Message..." autocomplete="off" required>
                <button type="submit" class="btn-send" aria-label="Send"><i class="bi bi-send"></i></button>
            </form>
        </div>
    <?php endif; ?>
    </div>

<?= $this->endSection() ?>