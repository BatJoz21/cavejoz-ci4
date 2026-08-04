// Post menu trigger
document.querySelectorAll('.post-menu-trigger').forEach(trigger => {
    trigger.addEventListener('click', (event) => {
        event.stopPropagation();

        const dropdown = trigger.nextElementSibling;

        document.querySelectorAll('.post-menu-dropdown.open').forEach(openDropDown => {
            if(openDropDown !== dropdown) {
                openDropDown.classList.remove('open');
            }
        });

        dropdown.classList.toggle('open');
    });
});

document.addEventListener('click', () => {
    document.querySelectorAll('.post-menu-dropdown.open').forEach(dropdown => {
        dropdown.classList.remove('open');
    });
});

// Load more post
const POSTS_PER_PAGE = 10;

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

            const response = await fetch(`/users/${profileUserId}/posts/${currentPage}`)
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

function buildPostCardHtml(post) {
    return `
        <div class="post-card" data-post-id="${post.id}">
            <div class="post-header">
                <img src="${profileAvatarUrl}" alt="" class="post-avatar">
                <div class="post-header-info">
                    <span class="post-username">${profileUsername}</span>
                    <span class="post-timestamp">${formatPostDate(post.created_at)}</span>
                </div>

                <?php if(session('user')['id'] == $post['user_id']): ?>
                    <div class="post-menu">
                        <button class="post-menu-trigger" aria-label="Post options">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <div class="post-menu-dropdown">
                            <a href="<?= base_url('/post/' . $post['id'] . '/edit') ?>" class="post-menu-item">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                            <a href="<?= base_url('/post/' . $post['id'] . '/delete') ?>" class="post-menu-item post-menu-item-danger">
                                <i class="bi bi-trash"></i> Delete
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="post-content">
                <p class="post-text">${escapeHtml(post.caption)}</p>
                <img src="${contentImageBaseUrl}${post.content_url}" alt="" class="post-image">
            </div>

            <div class="post-engagement">
                <button class="post-action post-like-btn ${post.liked_by_me ? 'liked' : ''}" data-post-id="${post.id}">
                    <i class="bi ${post.liked_by_me ? 'bi-heart-fill' : 'bi-heart'}"></i>
                    <span class="post-action-count">${post.like_count}</span>
                </button>
                <a href="${openCommentSectionUrl}" class="post-action">
                    <i class="bi bi-chat"></i>
                    <span class="post-action-count">3</span>
                </a>
            </div>
        </div>
    `;
}