<?= $this->extend('Layouts/main-layout') ?>

<?= $this->section('title') ?><?= esc($data['username'] ?? 'Profile') ?><?= $this->endSection() ?>

<?= $this->section('main') ?>

    <?php if(!empty($data)): ?>
        <script>
            const profileUserId = <?= json_encode($data['id']) ?>;
            const profileUsername = <?= json_encode($data['username']) ?>;
            const profileAvatarUrl = <?= json_encode(base_url('/avatar/' . $data['avatar_url'])) ?>;
            const contentImageBaseUrl = <?= json_encode(base_url('/content/image/')) ?>;
        </script>

        <div class="profile-header">
            <img src="<?= base_url('/avatar/' . ($data['avatar_url'] ?? 'default')) ?>" alt="" class="profile-avatar">

            <div class="profile-info">
                <div class="profile-info-top">
                    <h1 class="profile-username"><?= esc($data['username']) ?></h1>
                    <?php if(session('user')['id'] == $data['id']): ?>
                        <a href="<?= base_url('/profile/edit') ?>" class="btn-profile-action">Edit Profile</a>
                    <?php elseif(empty($data['friendship_status']) || $data['friendship_status'] == ''): ?>
                        <?= form_open('/friends') ?>
                            <input type="hidden" name="addressee_id" value="<?= esc($data['id']) ?>">
                            <button type="submit" class="btn-profile-action">Add Friend</button>
                        <?= form_close() ?>
                    <?php elseif($data['friendship_status'] === 'pending'): ?>
                        <button type="submit" class="btn-profile-action" disabled>Pending request</button>
                    <?php endif; ?>
                </div>

                <div class="profile-stats">
                    <span><strong><?= esc($data['total_post'], '0') ?></strong> posts</span>
                    <span><strong><?= esc($data['total_friend'], '0') ?></strong> friends</span>
                </div>

                <p class="profile-fullname"><?= esc($data['full_name']) ?></p>
                <p class="profile-bio"><?= esc($data['bio']) ?></p>
            </div>
        </div>

        <?php if(!empty($posts)): ?>
            <div class="postList" id="postList">
                <?php foreach($posts as $post): ?>
                    <script>
                        const openCommentSectionUrl = <?= json_encode(base_url('/posts/' . $post['id'] . '/comments')) ?>
                    </script>

                    <div class="post-card">
                        <div class="post-header">
                            <img src="<?= base_url('/avatar/' . ($data['avatar_url'] ?? 'default')) ?>" alt="" class="post-avatar">
                            <div class="post-header-info">
                                <span class="post-username"><?= esc($data['username']) ?></span>
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
                                <span class="post-action-count">3</span>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="load-more-wrapper" id="loadMoreWrapper">
                <button class="btn-load-more" id="loadMoreBtn">Load More</button>
            </div>
        <?php else: ?>
            <div class="profile-empty-state">
                <i class="bi bi-camera-fill"></i>
                <p class="profile-empty-title">No post yet</p>
                <?php if(session('user')['id'] == $data['id']): ?>
                    <p class="profile-empty-subtitle">When you share posts, they'll show up here.</p>
                    <a href="<?= base_url('/posts/create') ?>" class="btn-empty-cta">Create a post</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>

<?= $this->endSection() ?>