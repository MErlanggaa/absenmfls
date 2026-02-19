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
    const title = (payload.notification && payload.notification.title) || data.title || 'MFLS Notifikasi';
    const body = (payload.notification && payload.notification.body) || data.body || 'Ada notifikasi baru';
    const url = (payload.fcmOptions && payload.fcmOptions.link) || data.url || '/';

    console.log('[SW] showNotification. Title:', title, 'Body:', body, 'URL:', url);

    // PENTING: Icon yang terlalu besar atau format salah bisa diam-diam gagalkan notifikasi di Android!
    // Icon dihapus agar Android gunakan ikon default sistem.
    const notificationOptions = {
        body: body,
        // icon: '/icons/icon-192.png', // Aktifkan jika ada icon 192x192 PNG
        data: { url: url },
        vibrate: [200, 100, 200],
        tag: 'mfls-notif',
        renotify: true,
    };

    return self.registration.showNotification(title, notificationOptions);
}

// Handle background messages via Firebase SDK
// PENTING: Firebase SDK pada Web WAJIB memanggil showNotification secara manual.
// SDK tidak menampilkan notifikasi secara otomatis seperti native Android app.
if (firebaseConfig.apiKey) {
    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();

    messaging.onBackgroundMessage((payload) => {
        console.log('[SW] onBackgroundMessage received:', JSON.stringify(payload));
        return showNotification(payload);
    });
}

// Generic Push Listener (Fallback for raw push events)
self.addEventListener('push', (event) => {
    console.log('[SW] Raw push event received');
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
    const url = (event.notification.data && event.notification.data.url) || '/';

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
