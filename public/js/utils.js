function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatPostDate(dateString) {
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, '0');
    const month = date.toLocaleString('en-US', {'month': 'short'});
    const year = date.getFullYear();

    return `${day} ${month} ${year}`;
}

function getRelativeTime(timestamp) {
    if(!timestamp) return '';

    const past = new Date(timestamp);
    if(isNaN(past.getTime())) return '';

    const seconds = Math.max(0, Math.floor((Date.now() - past.getTime()) / 1000));

    if(seconds < 60) return 'just now';

    const units = [
        [31536000, 'year'],
        [2592000, 'month'],
        [604800, 'week'],
        [86400, 'day'],
        [3600, 'hour'],
        [60, 'minute'],
    ];

    for(const [unitSecond, label] of units) {
        if(seconds >= unitSecond) {
            const count = Math.floor(seconds / unitSecond);
            return `${count} ${label}${count > 1 ? 's' : ''} ago`;
        }
    }

    return 'just now';
}

function getTimeForMessageBox(timestamp) {
    if (!timestamp) return '';

    return new Date(timestamp).toLocaleTimeString('en-US', {
        hour: '2-digit',
        minute: '2-digit',
        timeZone: 'Asia/Jakarta'
    });
}

function getCsrfHeaderName() {
    return document.querySelector('meta[name="csrf-header"]').content;
}

function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]').content;
}

async function updateCsrfToken(response) {
    const name = getCsrfHeaderName();
    const fresh = response.headers.get(getCsrfHeaderName());
    if(fresh) {
        document.querySelector('meta[name="csrf-token"]').content = fresh;
        return;
    }

    try {
        const clone = response.clone();
        const data = await clone.json();
        if(data.csrf_hash) {
            document.querySelector('meta[name="csrf-token"]').content = data.csrf_hash;
        }
    } catch {}
}

async function apiFetch(url, options = {}) {
    const headerName = getCsrfHeaderName();
    const response = await fetch(url, {
        ...options,
        headers: {
            ...(options.headers || {}),
            [headerName]: getCsrfToken()
        }
    });

    await updateCsrfToken(response);
    return response;
}

function buildPostCardHtml(post) {
    return `
        <div class="post-card" data-post-id="${post.id}">
            <div class="post-header">
                <img src="${BASE_URL}/avatar/${encodeURIComponent(post.avatar_url)}" alt="" class="post-avatar">
                <div class="post-header-info">
                    <span class="post-username">${escapeHtml(post.username)}</span>
                    <span class="post-timestamp">${formatPostDate(post.created_at)}</span>
                </div>

                ${buildPostCardMenuHtml(post)}
            </div>

            <div class="post-content">
                <p class="post-text">${escapeHtml(post.caption)}</p>
                <img src="${contentImageBaseUrl}${encodeURIComponent(post.content_url)}" alt="" class="post-image">
            </div>

            <div class="post-engagement">
                <button class="post-action post-like-btn ${post.liked_by_me ? 'liked' : ''}" data-post-id="${post.id}">
                    <i class="bi ${post.liked_by_me ? 'bi-heart-fill' : 'bi-heart'}"></i>
                    <span class="post-action-count">${post.like_count}</span>
                </button>
                <a href="${BASE_URL}/posts/${post.id}/comments" class="post-action">
                    <i class="bi bi-chat"></i>
                    <span class="post-action-count">3</span>
                </a>
            </div>
        </div>
    `;
}

function buildPostCardMenuHtml(post) {
    if(post.user_id != currentUID) return '';

    return `
    <div class="post-menu">
        <button class="post-menu-trigger" aria-label="Post options">
            <i class="bi bi-three-dots-vertical"></i>
        </button>
        <div class="post-menu-dropdown">
            <a href="${BASE_URL}/post/${post.id}/edit" class="post-menu-item">
                <i class="bi bi-pencil-square"></i> Edit
            </a>
            <a href="${BASE_URL}/post/${post.id}/delete" class="post-menu-item post-menu-item-danger">
                <i class="bi bi-trash"></i> Delete
            </a>
        </div>
    </div>`;
}
