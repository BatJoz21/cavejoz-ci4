document.addEventListener('click', async (event) => {
    const likeBtn = event.target.closest('.post-like-btn');
    if(!likeBtn) return;

    const postId = likeBtn.dataset.postId;
    likeBtn.disabled = true;

    try {
        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
        const headerName = document.querySelector('meta[name="csrf-header"]').content;

        const response = await fetch(`/posts/${postId}/like`, {
            method: 'POST',
            headers: {
                [headerName]: tokenMeta.content,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        const data = await response.json();

        // The token is regenerated on every request, so keep the page in sync.
        if(data.csrf) {
            tokenMeta.content = data.csrf;
        }

        const icon = likeBtn.querySelector('i');
        const count = likeBtn.querySelector('.post-action-count');

        likeBtn.classList.toggle('liked', data.liked);
        icon.classList.toggle('bi-heart-fill', data.liked);
        icon.classList.toggle('bi-heart', !data.liked);
        count.textContent = data.count;
    } catch(error) {
        //
    } finally {
        likeBtn.disabled = false;
    }
});
