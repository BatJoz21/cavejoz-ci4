<?= $this->extend('Layouts/main-layout') ?>

<?= $this->section('title') ?>Post<?= $this->endSection() ?>

<?= $this->section('main') ?>

<?php if(!empty($post)): ?>
    <div class="comment-page">
        <div class="post-card">
            <div class="post-header">
                <img src="<?= $post['avatar_url'] && $post['avatar_url'] !== 'default' ? base_url('/avatar/' . $post['avatar_url']) : base_url('assets/img/default_avatar.png') ?>" alt="" class="post-avatar">
                <div class="post-header-info">
                    <span class="post-username"><?= esc($post['username']) ?></span>
                    <span class="post-timestamp"><?= date('d M Y', strtotime($post['created_at'])) ?></span>
                </div>

                <?php if(session('user')['id'] == $post['user_id']): ?>
                    <div class="post-menu">
                        <button class="post-menu-trigger" aria-label="Post options">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <div class="post-menu-dropdown">
                            <a href="<?= base_url('/posts/' . $post['id'] . '/edit') ?>" class="post-menu-item">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                            <form action="<?= base_url('/posts/' . $post['id'] . '/delete') ?>" method="post" class="post-delete-form">
                                <?= csrf_field() ?>
                                <button type="submit" class="post-menu-item post-menu-item-danger">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="post-content">
                <p class="post-text"><?= esc($post['caption']) ?></p>
                <img src="<?= base_url('/content/image/' . $post['content_url']) ?>" alt="" class="post-image">
            </div>

            <div class="post-engagement">
                <button class="post-action post-like-btn <?= $post['liked_by_me'] ? 'liked' : '' ?>" data-post-id="<?= $post['id'] ?>">
                    <i class="bi <?= $post['liked_by_me'] ? 'bi-heart-fill' : 'bi-heart' ?>"></i>
                    <span class="post-action-count"><?= esc($post['like_count']) ?></span>
                </button>
                <a href="<?= base_url('/posts/' . $post['id'] . '/comments') ?>" class="post-action">
                    <i class="bi bi-chat"></i>
                    <span class="post-action-count"><?= esc($post['comment_count']) ?></span>
                </a>
            </div>
        </div>

        <div class="comment-form-wrapper">
            <img src="<?= base_url('/avatar/' . ($post['avatar_url'] ?? 'default')) ?>" alt="" class="comment-form-avatar">
            <form method="post" action="<?= base_url('/posts/' . $post['id'] . '/comments') ?>" class="comment-form">
                <?= csrf_field() ?>
                <textarea name="content" id="content" class="comment-input" placeholder="Add a comment..." maxlength="500" required></textarea>
                <button type="submit" class="btn-comment-submit">Post</button>
            </form>
        </div>

        <?php if(!empty($comments)): ?>
            <div class="comment-list">
                <?php foreach($comments as $comment): ?>
                    <div class="comment-item">
                        <img src="<?= $comment['avatar_url'] && $comment['avatar_url'] !== 'default' ? base_url('/avatar/' . $comment['avatar_url']) : base_url('assets/img/default_avatar.png') ?>" alt="" class="comment-avatar">
                        <div class="comment-body">
                            <div class="comment-meta">
                                <span class="comment-username"><?= esc($comment['username']) ?></span>
                                <span class="comment-timestamp"><?= date('d M Y', strtotime($comment['created_at'])) ?></span>
                            </div>
                            <p class="comment-text"><?= esc($comment['content']) ?></p>
                        </div>
                        <?php if($post['user_id'] == session('user')['id'] || $comment['user_id'] == session('user')['id']): ?>
                            <form action="<?= base_url('/posts/' . $post['id'] . '/comments/' . $comment['id'] . '/delete') ?>" method="post" class="confirm-delete-form">
                                <?= csrf_field() ?>
                                <button type="submit" class="comment-delete-btn" aria-label="Delete comment">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if(!empty($currentPage) && !empty($totalPage)): ?>
                <div class="pagination">
                    <a href="<?= base_url('/posts/' . $post['id'] . '/comments?page=' . 1) ?>" class="pagination-btn <?= ($currentPage == 1) ? 'disabled' : '' ?>">
                        <i class="bi bi-chevron-double-left"></i>
                    </a>

                    <?php for($i = 1; $i <= $totalPage; $i++): ?>
                        <?php if($i == $currentPage - 1 || $i == $currentPage || $i == $currentPage + 1): ?>
                            <a href="<?= base_url('/posts/' . $post['id'] . '/comments?page=' . $i) ?>" class="pagination-btn <?= ($currentPage == $i) ? 'disabled' : '' ?>">
                                <?= $i ?>
                            </a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <a href="<?= base_url('/posts/' . $post['id'] . '/comments?page=' . $totalPage) ?>" class="pagination-btn <?= ($currentPage >= $totalPage) ? 'disabled' : '' ?>">
                        <i class="bi bi-chevron-double-right"></i>
                    </a>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="bi bi-chat-dots"></i>
                <p class="empty-title">No comment</p>
                <p class="empty-subtitle">Be the first to say something.</p>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>