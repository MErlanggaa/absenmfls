// Firebase Cloud Messaging Service Worker
// File: public/firebase-messaging-sw.js
// This file MUST be in the public root (not in /js/) for FCM to work

importScripts('https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging-compat.js');

// Firebase config will be injected via query string or hardcoded here
// For security, use environment-specific values
const firebaseConfig = {
    apiKey: self.FIREBASE_API_KEY || "YOUR_API_KEY",
    authDomain: self.FIREBASE_AUTH_DOMAIN || "YOUR_PROJECT.firebaseapp.com",
    projectId: self.FIREBASE_PROJECT_ID || "YOUR_PROJECT_ID",
    storageBucket: self.FIREBASE_STORAGE_BUCKET || "YOUR_PROJECT.appspot.com",
    messagingSenderId: self.FIREBASE_MESSAGING_SENDER_ID || "YOUR_SENDER_ID",
    appId: self.FIREBASE_APP_ID || "YOUR_APP_ID",
};

firebase.initializeApp(firebaseConfig);

const messaging = firebase.messaging();

// Handle background messages (when app is not in focus)
messaging.onBackgroundMessage((payload) => {
    console.log('[SW] Background message received:', payload);

    const { title, body } = payload.notification || {};
    const data = payload.data || {};

    const notificationOptions = {
        body: body || 'Ada notifikasi baru',
        icon: '/favicon.ico',
        badge: '/favicon.ico',
        data: { url: data.url || '/' },
        actions: [
            { action: 'open', title: 'Buka' },
            { action: 'close', title: 'Tutup' },
        ],
    };

    self.registration.showNotification(title || 'Notifikasi', notificationOptions);
});

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
