// Firebase Messaging Service Worker
// Config di-hardcode langsung — agar selalu tersedia saat Android background

importScripts('https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging-compat.js');

// Standard Service Worker lifecycle for PWA
self.addEventListener('install', function (event) {
    self.skipWaiting();
});

self.addEventListener('activate', function (event) {
    event.waitUntil(clients.claim());
});

// Simple fetch listener to pass PWA criteria
self.addEventListener('fetch', function (event) {
    event.respondWith(
        fetch(event.request).catch(function () {
            return caches.match(event.request);
        })
    );
});

// =====================================================
// FIREBASE CONFIG - hardcoded agar tidak null di Android
// =====================================================
var firebaseConfig = {
    apiKey: "AIzaSyC7vm7XRb9SBQp8Dp9REcZRflWe9e3wZXU",
    authDomain: "greenovation-80093.firebaseapp.com",
    projectId: "greenovation-80093",
    storageBucket: "greenovation-80093.firebasestorage.app",
    messagingSenderId: "92660714859",
    appId: "1:92660714859:web:ea3503eb568bd342d6502e"
};

// Tampilkan notifikasi
function showNotification(payload) {
    var data = payload.data || {};
    var notification = payload.notification || {};
    var title = notification.title || data.title || 'MFLS Notifikasi';
    var body = notification.body || data.body || 'Ada notifikasi baru';

    var link = '';
    if (payload.fcmOptions && payload.fcmOptions.link) link = payload.fcmOptions.link;
    else if (data.url) link = data.url;
    else link = '/';

    console.log('[SW] Showing notification:', title, body, link);

    var notificationOptions = {
        body: body,
        icon: '/loog.jpeg',
        badge: '/loog.jpeg',
        data: { url: link },
        vibrate: [500, 110, 500, 110, 450, 110, 200, 110, 170, 40, 450, 110, 200, 110, 170, 40, 500], // Custom vibration
        sound: '/hidup-jokowi.mp3', // Supported on some desktop browsers
        tag: 'mfls-notif',
        renotify: true,
    };

    return self.registration.showNotification(title, notificationOptions);
}

// Inisialisasi Firebase
firebase.initializeApp(firebaseConfig);
var messaging = firebase.messaging();

// Handle push saat app background
// Firebase Web SDK WAJIB panggil showNotification manual — tidak otomatis
messaging.onBackgroundMessage(function (payload) {
    console.log('[SW] onBackgroundMessage:', JSON.stringify(payload));
    return showNotification(payload);
});

// Fallback: Raw push listener
self.addEventListener('push', function (event) {
    console.log('[SW] Raw push event received');
    var payload = {};
    if (event.data) {
        try {
            payload = event.data.json();
        } catch (e) {
            payload = { notification: { body: event.data.text() } };
        }
    }
    event.waitUntil(showNotification(payload));
});

// Handle klik notifikasi
self.addEventListener('notificationclick', function (event) {
    event.notification.close();
    var url = (event.notification.data && event.notification.data.url) ? event.notification.data.url : '/';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function (clientList) {
            for (var i = 0; i < clientList.length; i++) {
                var client = clientList[i];
                if (client.url.indexOf(url) !== -1 && 'focus' in client) {
                    return client.focus();
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(url);
            }
        })
    );
});
