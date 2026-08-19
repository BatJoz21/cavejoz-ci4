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

        <div class="thread-message-wrapper">
            <button id="loadOlderBtn" class="btn-load-older" style="display: none;">
                Load older message
            </button>

            <div class="thread-messages" id="threadMessages"></div>
        </div>

        <div class="thread-input-wrapper">
            <form action="<?= base_url('/chat/' . $conversation['id'] . '/message') ?>" id="messageForm" class="thread-form">
                <?= csrf_field() ?>
                <input type="text" name="messageInput" id="messageInput" class="thread-input" placeholder="Message..." autocomplete="off" required>
                <button type="submit" class="btn-send" aria-label="Send"><i class="bi bi-send"></i></button>
            </form>
        </div>
    <?php endif; ?>
    </div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>

<?php if(!empty($conversation)): ?>
    <script>
        const CONVERSATION_ID = <?= (int) $conversation['id'] ?>;
    </script>
<?php endif; ?>
    <script src="<?= base_url('js/chat.js') ?>"></script>
    <script src="<?= base_url('js/loadOlderChat.js') ?>"></script>

<?= $this->endSection() ?>