const threadMessage = document.getElementById('threadMessages');
let messageLoaded = false;
let nextCursor = 0;
let loadingOrder = false;

let lastTypingSent = 0;
let typingTimer = null;
const TYPING_THROTTLE = 2000;
const TYPING_TIMEOUT = 3000;
const typingIndicator = document.getElementById('typingIndicator');

function buildMessageHtml(m) {
    return `
        <div class="message-item ${m.sender_id == userID ? 'own' : ''}">
            <div class="message-bubble">
                <p class="message-text">${escapeHtml(m.content)}</p>
                <span class="message-time">${getTimeForMessageBox(m.created_at)}</span>
            </div>
        </div>
    `;
}

function showTypingIndicator() {
    if(typingIndicator) typingIndicator.classList.add('visible')
}

function hideTypingIndicator() {
    if(typingIndicator) typingIndicator.classList.remove('visible');
}

function handleTypingIndicator(conversationId) {
    if(typeof CONVERSATION_ID === 'undefined') return;
    if(conversationId != CONVERSATION_ID) return;

    showTypingIndicator();
    clearTimeout(typingTimer);
    typingTimer = setTimeout(hideTypingIndicator, TYPING_TIMEOUT);
}

async function loadMessages() {
    try {
        const response = await fetch(`${BASE_URL}/chat/${CONVERSATION_ID}/message`);
        const data = await response.json();
        const items = data['messages'] ?? [];
        nextCursor = data['next_cursor'] ?? 0;

        if(items.length === 0) {
            threadMessage.innerHTML = `<div class="empty-state">
                <i class="bi bi-chat-dots"></i>
                <p class="empty-title">No message yet</p>
                <p class="empty-subtitle">Start send message to your friend</p>
            </div>`;
        } else {
            threadMessage.innerHTML = items.map(buildMessageHtml).join('');

            markConversationRead();
        }

        updateLoadOlderButton();
        messageLoaded = true;
    } catch(error) {
        threadMessage.innerHTML = `<div class="empty-state">
            <i class="bi bi-chat-dots"></i>
            <p class="empty-title">No message yet</p>
            <p class="empty-subtitle">Start send message to your friend</p>
        </div>`;
        messageLoaded = false;
    }
}

async function markConversationRead() {
    try {
        await apiFetch(`${BASE_URL}/chat/${CONVERSATION_ID}/read`, { 
            method: 'PUT',
        });
    } catch (error) {}
}

function appendMessage(m) {
    const empty = threadMessage.querySelector('.empty-state');
    if(empty) threadMessage.innerHTML = '';

    threadMessage.insertAdjacentHTML('beforeend', buildMessageHtml(m))
    threadMessage.scrollTop = threadMessage.scrollHeight;
}

function handleIncomingMessage(m) {
    if(typeof CONVERSATION_ID === 'undefined') return;
    if(m.conversation_id != CONVERSATION_ID) return;

    if(m.sender_id != userID) {
        hideTypingIndicator();
        clearTimeout(typingTimer);
    }

    appendMessage(m);
    if(m.sender_id != userID) markConversationRead();
}

const messageForm = document.getElementById('messageForm');
const messageInput = document.getElementById('messageInput');

if(messageForm) {
    messageForm.addEventListener('input', () => {
        const now = Date.now();
        if(now - lastTypingSent < TYPING_THROTTLE) return;
        lastTypingSent = now;
        sendWS({
            type: "typing",
            conversation_id: CONVERSATION_ID
        });
    });

    messageForm.addEventListener('submit', (e) => {
        e.preventDefault();

        const content = messageInput.value.trim();
        if(!content) return;

        const sent = sendWS({
            type: 'send_message',
            conversation_id: CONVERSATION_ID,
            content: content
        });

        if(!sent) {
            console.warn('socket not open, message not sent');
            return
        }

        messageInput.value = '';
        messageInput.focus();
    });
}

if(threadMessage && typeof CONVERSATION_ID !== 'undefined') {
    loadMessages();
}