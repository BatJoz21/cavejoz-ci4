<?= $this->extend('Layouts/main-layout') ?>

<?= $this->section('title') ?>Messages<?= $this->endSection() ?>

<?= $this->section('main') ?>

    <div class="chats-page">
        <h1 class="chats-title">Messages</h1>

        <?php if(!empty($conversations)): ?>
            <div class="chat-list">
                <?php foreach($conversations as $c): ?>
                    <a href="<?= base_url('/chat/' . $c['id']) ?>" class="chat-item <?= ($c['has_unread']) ? 'unread' : '' ?>">
                        <img src="<?= base_url('/avatar/' . ($c['avatar_url'] ?? 'default')) ?>" alt="" class="chat-avatar">
                        <div class="chat-body">
                            <div class="chat-meta">
                                <span class="chat-username"><?= esc($c['username']) ?></span>
                                <span class="chat-time"><?= esc(get_relative_time($c['last_message_at']) ?? '') ?></span>
                            </div>
                            <p class="chat-preview"><?= esc($c['last_message'] ?? '') ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

            <?php if(!empty($currentPage) && !empty($totalPage)): ?>
                <div class="pagination">
                    <a href="<?= base_url('/chat?page=' . 1) ?>" class="pagination-btn <?= ($currentPage == 1) ? 'disabled' : '' ?>">
                        <i class="bi bi-chevron-double-left"></i>
                    </a>

                    <?php for($i = 1; $i <= $totalPage; $i++): ?>
                        <a href="<?= base_url('/chat?page=' . $i) ?>" class="pagination-btn <?= ($currentPage == $i) ? 'disabled' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>

                    <a href="<?= base_url('/chat?page=' . $totalPage) ?>" class="pagination-btn <?= ($currentPage == $totalPage) ? 'disabled' : '' ?>">
                        <i class="bi bi-chevron-double-right"></i>
                    </a>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="bi bi-chat-dots"></i>
                <p class="empty-title">No message yet.</p>
                <p class="empty-subtitle">Start a conversation from someone's profile.</p>
            </div>
        <?php endif; ?>
    </div>

<?= $this->endSection() ?>