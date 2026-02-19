/**
 * Firebase Push Notification Manager
 * File: public/js/firebase-push.js
 *
 * Include this in your main layout AFTER setting window.firebaseConfig
 */

import { initializeApp } from 'https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js';
import { getMessaging, getToken, onMessage } from 'https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging.js';

class FirebasePushManager {
    constructor(config, vapidKey) {
        this.app = initializeApp(config);
        this.messaging = getMessaging(this.app);
        this.vapidKey = vapidKey;
    }

    /**
     * Request notification permission and get FCM token.
     * Sends the token to the Laravel backend.
     */
    async init() {
        try {
            const permission = await Notification.requestPermission();
            if (permission !== 'granted') {
                console.log('❌ Push notification permission denied.');
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'NOTIFIKASI MATI',
                        text: 'Bos belum ijinin notifikasi di gembok browser, pantesan nggak muncul!',
                        confirmButtonText: 'OKE, GUE SETTING'
                    });
                }
                return;
            }

            // Register service worker - config sudah di-inject server-side, tidak perlu query string
            const registration = await navigator.serviceWorker.register('/firebase-messaging-sw.js', {
                updateViaCache: 'none' // Force check for new SW on every load
            });

            // Force update check
            registration.update();

            // Get FCM token
            const token = await getToken(this.messaging, {
                vapidKey: this.vapidKey,
                serviceWorkerRegistration: registration,
            });

            if (token) {
                console.log('✅ FCM Token obtained:', token.substring(0, 20) + '...');
                await this.saveTokenToServer(token);
                // Notification for user
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'NOTIFIKASI AKTIF',
                        text: 'HP lo sudah terdaftar buat nerima push notification, Bos!',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            }

            // Handle foreground messages (when app is open)
            onMessage(this.messaging, (payload) => {
                console.log('Foreground message:', payload);
                this.showForegroundNotification(payload);
            });

        } catch (error) {
            console.error('Firebase push init error:', error);
        }
    }

    /**
     * Save FCM token to Laravel backend.
     */
    async saveTokenToServer(token) {
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            const response = await fetch('/fcm-token', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || '',
                },
                body: JSON.stringify({
                    token: token,
                    device_type: 'web',
                }),
            });

            if (response.ok) {
                console.log('✅ FCM token saved to server successfully.');
            } else {
                console.error('❌ Failed to save FCM token to server. Status:', response.status);
                const errBody = await response.text();
                console.error('Response details:', errBody);
            }
        } catch (error) {
            console.error('❌ Error sending FCM token to server:', error);
        }
    }

    /**
     * Show in-app toast notification for foreground messages.
     */
    showForegroundNotification(payload) {
        const { title, body } = payload.notification || {};
        const data = payload.data || {};

        // Create toast element
        const toast = document.createElement('div');
        toast.className = 'fcm-toast';
        toast.innerHTML = `
            <div class="fcm-toast-icon">🔔</div>
            <div class="fcm-toast-content">
                <strong>${title || 'Notifikasi'}</strong>
                <p>${body || ''}</p>
            </div>
            <button class="fcm-toast-close" onclick="this.parentElement.remove()">✕</button>
        `;

        if (data.url) {
            toast.style.cursor = 'pointer';
            toast.addEventListener('click', (e) => {
                if (!e.target.classList.contains('fcm-toast-close')) {
                    window.location.href = data.url;
                }
            });
        }

        document.body.appendChild(toast);

        // Also trigger system notification if possible
        if (Notification.permission === 'granted') {
            navigator.serviceWorker.ready.then(registration => {
                registration.showNotification(title || 'MFLS', {
                    body: body || '',
                    icon: '/loog.jpeg',
                    badge: '/loog.jpeg',
                    vibrate: [200, 100, 200],
                    data: data,
                    tag: 'mfls-notif-' + Date.now(), // Force individual notifications
                    requireInteraction: true
                });
            });
        }

        // Auto-remove after 6 seconds
        setTimeout(() => toast.remove(), 6000);
    }
}

// Auto-initialize if config is available
document.addEventListener('DOMContentLoaded', () => {
    if (window.firebaseConfig && window.firebaseVapidKey) {
        const manager = new FirebasePushManager(window.firebaseConfig, window.firebaseVapidKey);
        window.firebasePushManager = manager; // Expose for debugging
        manager.init();
    }
});

export default FirebasePushManager;
