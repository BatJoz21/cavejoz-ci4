const wrapper = document.querySelector('.thread-message-wrapper');
const loadOlderBtn = document.getElementById('loadOlderBtn');

function updateLoadOlderButton() {
    if(!loadOlderBtn) return;
    loadOlderBtn.style.display = nextCursor > 0 ? 'block' : 'none';
}

async function loadOlderMessage() {
    if(loadingOrder || nextCursor === 0) return;
    loadingOrder = true;
    loadOlderBtn.disabled = true;
    loadOlderBtn.textContent = 'Loading...';

    try {
        const response = await fetch(`${BASE_URL}/chat/${CONVERSATION_ID}/message?cursor=${nextCursor}`);
        const data = await response.json();
        const items = data['messages'] ?? [];
        nextCursor = data['next_cursor'] ?? 0;

        if(items.length > 0) {
            const previousHeight = wrapper.scrollHeight;
            threadMessage.insertAdjacentHTML('afterbegin', items.map(buildMessageHtml).join(''));
            wrapper.scrollTop = wrapper.scrollHeight - previousHeight;
        }
    } catch(error) {
        console.error('Failed to load older message ', error);
    } finally {
        loadingOrder = false;
        loadOlderBtn.disabled = false;
        loadOlderBtn.textContent = 'Load older message';
        updateLoadOlderButton();
    }
}

if(loadOlderBtn) {
    loadOlderBtn.addEventListener('click', loadOlderMessage);
}