// This file MUST be in the public root (not in /js/) for FCM to work

// Standard Service Worker lifecycle for PWA
self.addEventListener('install', (event) => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(clients.claim());
});

// Simple fetch listener to pass PWA criteria
self.addEventListener('fetch', (event) => {
    event.respondWith(
        fetch(event.request).catch(() => {
            return caches.match(event.request);
        })
    );
});

importScripts('https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging-compat.js');

// Parse configuration from query string
const urlParams = new URLSearchParams(location.search);
const firebaseConfig = {
    apiKey: urlParams.get('apiKey'),
    authDomain: urlParams.get('authDomain'),
    projectId: urlParams.get('projectId'),
    storageBucket: urlParams.get('storageBucket'),
    messagingSenderId: urlParams.get('messagingSenderId'),
    appId: urlParams.get('appId'),
};

// Consolidated notification display logic
function showNotification(payload) {
    const data = payload.data || {};
    const title = payload.notification?.title || data.title || 'MFLS NOTIFICATION';
    const body = payload.notification?.body || data.body || 'Ada notifikasi baru';

    const notificationOptions = {
        body: body || data.body || 'Ada notifikasi baru',
        icon: '/loog.jpeg', // Warning: Image size too large for Android (1563px). Should be 192px.
        // badge: '/loog.jpeg', // Commented out to let Android use default bell icon regarding size issue
        data: {
            url: data.url || payload.fcmOptions?.link || '/'
        },
        vibrate: [200, 100, 200],
        tag: 'mfls-notif',
        renotify: true,
        requireInteraction: true,
        actions: [
            { action: 'open', title: 'LIHAT' },
        ],
    };

    return self.registration.showNotification(title || data.title || 'MFLS NOTIFICATION', notificationOptions);
}

// Handle background messages via Firebase SDK
if (firebaseConfig.apiKey) {
    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();

    messaging.onBackgroundMessage((payload) => {
        console.log('[SW] Background message received via FCM:', payload);
        showNotification(payload);
    });
}

// Generic Push Listener (Fallack for DevTools "Push" button and other push types)
self.addEventListener('push', (event) => {
    console.log('[SW] Push event received:', event);
    let payload = {};
    if (event.data) {
        try {
            payload = event.data.json();
        } catch (e) {
            payload = { notification: { body: event.data.text() } };
        }
    }
    event.waitUntil(showNotification(payload));
});

// Handle notification click
self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    const url = event.notification.data?.url || '/';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then((clientList) => {
            for (const client of clientList) {
                if (client.url.includes(url) && 'focus' in client) {
                    return client.focus();
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(url);
            }
        })
    );
});
