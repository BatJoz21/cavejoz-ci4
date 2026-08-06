<?= $this->extend('Layouts/main-layout') ?>

<?= $this->section('title') ?>Home<?= $this->endSection() ?>

<?= $this->section('main') ?>

    <div class="feed-page">
        <script>
            const BASE_URL = <?= json_encode(rtrim(base_url(), '/')) ?>;
            const currentUID = <?= json_encode(session('user')['id']) ?>;
            const contentImageBaseUrl = <?= json_encode(base_url('/content/image/')) ?>;
        </script>
        <a href="<?= base_url('/posts/create') ?>" class="feed-composer">
            <img src="<?= base_url('/avatar/' . (session('user')['avatar_url'] ?? 'default')) ?>" alt="" class="feed-composer-avatar">
            <span class="feed-composer-prompt">What's on your mind?</span>
            <i class="bi bi-image feed-composer-icon"></i>
        </a>

        <?php if(!empty($posts)): ?>
            <div class="post-list" id="postList">
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
            <div class="empty-state">
                <?php if(!empty($totalFriend) && $totalFriend == 0): ?>
                    <i class="bi bi-people-fill"></i>
                    <p class="empty-title">Your feed is empty</p>
                    <p class="empty-subtitle">Find people and befriend them, their posts will show up here.</p>
                    <a href="<?= base_url('/search') ?>" class="btn-empty-cta">Search for people</a>
                <?php else: ?>
                    <i class="bi bi-images"></i>
                    <p class="empty-title">Nothing to see here</p>
                    <p class="empty-subtitle">Your friends hasn't posted anything yet</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
    <script>
        // Load more post
        const POST_PER_PAGE = 2;

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

                    const response = await fetch(`feeds/posts/${currentPage}`);
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

                    if(posts.length < POST_PER_PAGE) {
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