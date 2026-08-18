let ws = null;
let retryDelay = 1000;
const MAX_DELAY = 30000;

async function connectWS() {
    try {
        const res = await fetch('/ws-ticket');
        if(res.status === 401) return;
        if(!res.ok) {
            scheduleReconnect();
            return;
        }

        const { ticket } = await res.json();
        ws = new WebSocket(`${WS_BASE_URL}/goapi/ws?ticket=${ticket}`);

        ws.onopen = () => {
            console.log("ws connected");
            retryDelay = 1000;
            if(notificationLoaded) loadNotifications();
            if(typeof messageLoaded !== 'undefined' && messageLoaded) loadMessages();
        };

        ws.onmessage = (e) => {
            const msg = JSON.parse(e.data);
            console.log("ws frame:", msg);
            if(msg.type === 'notification') {
                handleNotification(msg.notification);
            } else if(msg.type === 'message') {
                if(typeof handleIncomingMessage === 'function') {
                    handleIncomingMessage(msg.message);
                }
            }
        };

        ws.onclose = () => {
            scheduleReconnect();
        };

        ws.onerror = () => {
            ws.close();
        };
    } catch(error) {
        scheduleReconnect();
    }

    return ws;
}

function sendWS(payload) {
    if(!ws || ws.readyState !== WebSocket.OPEN) return false;

    ws.send(JSON.stringify(payload));
    return true;
}

function scheduleReconnect() {
    console.log("reconnecting in", retryDelay, "ms");
    setTimeout(connectWS, retryDelay);
    retryDelay = Math.min(retryDelay * 2, MAX_DELAY);
}

if(document.querySelector('.notification-dropdown')) {
    connectWS();
}