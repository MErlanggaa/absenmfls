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

// Only initialize if we have config
if (firebaseConfig.apiKey) {
    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();

    // Handle background messages
    messaging.onBackgroundMessage((payload) => {
        console.log('[SW] Background message received:', payload);
        const { title, body, icon } = payload.notification || {};
        const data = payload.data || {};

        // Use icon from payload or default to loog
        const notificationIcon = icon || '/loog.jpeg';

        const notificationOptions = {
            body: body || 'Ada notifikasi baru',
            icon: notificationIcon,
            badge: '/loog.jpeg',
            data: {
                url: data.url || payload.fcmOptions?.link || '/'
            },
            vibrate: [200, 100, 200],
            requireInteraction: true, // Notif nggak ilang sampai di-swipe/klik
            actions: [
                { action: 'open', title: 'LIHAT' },
            ],
        };

        return self.registration.showNotification(title || 'MFLS NOTIFICATION', notificationOptions);
    });
}

// Handle notification click
self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    const url = event.notification.data?.url || '/';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then((clientList) => {
            for (const client of clientList) {
                if (client.url === url && 'focus' in client) {
                    return client.focus();
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(url);
            }
        })
    );
});
