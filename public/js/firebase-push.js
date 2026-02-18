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
                console.log('Push notification permission denied.');
                return;
            }

            // Register service worker with config in query string
            const configString = new URLSearchParams(this.app.options).toString();
            const registration = await navigator.serviceWorker.register(`/firebase-messaging-sw.js?${configString}`);

            // Get FCM token
            const token = await getToken(this.messaging, {
                vapidKey: this.vapidKey,
                serviceWorkerRegistration: registration,
            });

            if (token) {
                console.log('FCM Token obtained:', token.substring(0, 20) + '...');
                await this.saveTokenToServer(token);
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
        if (Notification.permission === 'granted' && navigator.serviceWorker.controller) {
            navigator.serviceWorker.ready.then(registration => {
                registration.showNotification(title || 'MFLS', {
                    body: body || '',
                    icon: '/loog.jpeg',
                    data: data
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
        manager.init();
    }
});

export default FirebasePushManager;
