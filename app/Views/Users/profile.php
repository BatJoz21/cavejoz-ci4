<?= $this->extend('Layouts/main-layout') ?>

<?= $this->section('title') ?><?= esc($data['username'] ?? 'Profile') ?><?= $this->endSection() ?>

<?= $this->section('main') ?>

    <?php if(!empty($data)): ?>
        <script>
            const BASE_URL = <?= json_encode(rtrim(base_url(), '/')) ?>;
            const currentUID = <?= json_encode(session('user')['id']) ?>;

            const profileUserId = <?= json_encode($data['id']) ?>;
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
                    <div class="post-card">
                        <div class="post-header">
                            <img src="<?= base_url('/avatar/' . ($post['avatar_url'] ?? 'default')) ?>" alt="" class="post-avatar">
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
                                        <form action="<?= base_url('/posts/' . $post['id'] . '/delete') ?>" method="post" class="confirm-delete-form">
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
                                <span class="post-action-count"><?= $post['comment_count'] ?></span>
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

<?= $this->section('script') ?>
    <script>
        // Load more post
        const POSTS_PER_PAGE = 5;

        const loadMoreBtn = document.getElementById('loadMoreBtn');
        const loadMoreWrapper = document.getElementById('loadMoreWrapper');
        const postList = document.getElementById('postList');

        let currentPage = 1;

        if(loadMoreBtn) {
            loadMoreBtn.addEventListener('click', async () => {
                loadMoreBtn.disabled = true;
                loadMoreBtn.textContent = 'Loading...'

                try {
                    currentPage++;

                    const response = await fetch(`/users/${profileUserId}/posts/${currentPage}`);
                    const data = await response.json();

                    if(!data.success) {
                        loadMoreBtn.disabled = false;
                        loadMoreBtn.textContent = 'Load More';
                        return;
                    }

                    const posts = data.posts || [];

                    posts.forEach(post => {
                        postList.insertAdjacentHTML('beforeend', buildPostCardHtml(post));
                    });

                    if(posts.length < POSTS_PER_PAGE) {
                        loadMoreWrapper.remove();
                    } else {
                        loadMoreBtn.disabled = false;
                        loadMoreBtn.textContent = 'Load More';
                    }
                } catch(error) {
                    loadMoreBtn.disabled = false;
                    loadMoreBtn.textContent = 'Load More';
                }
            });
        }
    </script>
<?= $this->endSection() ?>