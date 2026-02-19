<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Firebase Cloud Messaging Service
 * Uses FCM HTTP v1 API with Service Account credentials
 *
 * Setup:
 * 1. Go to Firebase Console > Project Settings > Service Accounts
 * 2. Click "Generate new private key" -> download JSON
 * 3. Save the JSON file to storage/app/firebase/service-account.json
 * 4. Set FIREBASE_PROJECT_ID in .env
 */
class FirebaseService
{
    protected string $projectId;
    protected string $serviceAccountPath;
    protected ?string $accessToken = null;

    public function __construct()
    {
        $this->projectId = config('firebase.project_id', env('FIREBASE_PROJECT_ID', ''));
        $this->serviceAccountPath = storage_path('app/firebase/service-account.json');
    }

    /**
     * Send a push notification to a single device token.
     */
    /**
     * @return bool|string Returns true on success, 'unregistered' if token is expired, false on other errors.
     */
    public function sendToDevice(string $fcmToken, string $title, string $body, array $data = []): bool|string
    {
        if (empty($this->projectId)) {
            Log::warning('Firebase: FIREBASE_PROJECT_ID not set in .env');
            return false;
        }

        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return false;
        }

        $payload = [
            'message' => [
                'token' => $fcmToken,
                'notification' => [
                    'title' => $title,
                    'body'  => $body,
                ],
                'data' => array_merge(
                    array_map('strval', $data), 
                    [
                        'url' => $data['url'] ?? url('/'),
                        'click_action' => $data['url'] ?? url('/')
                    ]
                ),
                'android' => [
                    'priority' => 'high',
                    'notification' => [
                        'click_action' => $data['url'] ?? url('/'),
                        'sound' => 'default'
                    ]
                ],
                'apns' => [
                    'payload' => [
                        'aps' => [
                            'sound' => 'default',
                            'badge' => 1,
                        ],
                    ],
                ],
                'webpush' => [
                    'headers' => [
                        'Urgency' => 'high',
                    ],
                    'fcm_options' => [
                        'link' => $data['url'] ?? url('/'),
                    ],
                ],
            ],
        ];

        Log::info('Firebase: Sending Payload', ['payload' => json_encode($payload)]);

        $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";

        $response = Http::withToken($accessToken)
            ->post($url, $payload);

        if ($response->successful()) {
            Log::info('Firebase: Notification sent successfully', ['token' => substr($fcmToken, 0, 20) . '...']);
            return true;
        }

        // Token expired / unregistered — perlu dihapus dari database
        $responseBody = $response->json();
        $errorCode = $responseBody['error']['details'][0]['errorCode'] ?? '';
        if ($errorCode === 'UNREGISTERED' || $response->status() === 404) {
            Log::warning('Firebase: Token UNREGISTERED (expired), will be deleted', ['token' => substr($fcmToken, 0, 20) . '...']);
            return 'unregistered';
        }

        Log::error('Firebase: Failed to send notification', [
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);
        return false;
    }

    /**
     * Send notification to multiple device tokens (multicast).
     */
    public function sendToMultiple(array $fcmTokens, string $title, string $body, array $data = []): array
    {
        $results = ['success' => 0, 'failure' => 0, 'unregistered' => []];

        foreach ($fcmTokens as $token) {
            $result = $this->sendToDevice($token, $title, $body, $data);
            if ($result === true) {
                $results['success']++;
            } elseif ($result === 'unregistered') {
                $results['failure']++;
                $results['unregistered'][] = $token; // tandai untuk dihapus
            } else {
                $results['failure']++;
            }
        }

        return $results;
    }

    /**
     * Send notification to a topic (e.g., 'all-users', 'dept-IT').
     */
    public function sendToTopic(string $topic, string $title, string $body, array $data = []): bool
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) return false;

        $payload = [
            'message' => [
                'topic' => $topic,
                'notification' => [
                    'title' => $title,
                    'body'  => $body,
                ],
                'data' => array_map('strval', $data),
            ],
        ];

        $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";

        $response = Http::withToken($accessToken)->post($url, $payload);

        return $response->successful();
    }

    /**
     * Get OAuth2 access token from Service Account JSON.
     * Caches the token in memory for the request lifecycle.
     */
    protected function getAccessToken(): ?string
    {
        if ($this->accessToken) {
            return $this->accessToken;
        }

        if (!file_exists($this->serviceAccountPath)) {
            Log::warning('Firebase: service-account.json not found at ' . $this->serviceAccountPath);
            Log::warning('Firebase: Download from Firebase Console > Project Settings > Service Accounts');
            return null;
        }

        try {
            $serviceAccount = json_decode(file_get_contents($this->serviceAccountPath), true);

            $now = time();
            
            $base64UrlEncode = function($data) {
                return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
            };

            $header = $base64UrlEncode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
            $payload = $base64UrlEncode(json_encode([
                'iss'   => $serviceAccount['client_email'],
                'sub'   => $serviceAccount['client_email'],
                'aud'   => 'https://oauth2.googleapis.com/token',
                'iat'   => $now,
                'exp'   => $now + 3600,
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            ]));

            $signingInput = $header . '.' . $payload;
            $privateKey = $serviceAccount['private_key'];

            openssl_sign($signingInput, $signature, $privateKey, OPENSSL_ALGO_SHA256);
            $jwt = $signingInput . '.' . $base64UrlEncode($signature);

            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion'  => $jwt,
            ]);

            if ($response->successful()) {
                $this->accessToken = $response->json('access_token');
                return $this->accessToken;
            }

            Log::error('Firebase: Failed to get access token', ['response' => $response->body()]);
            return null;

        } catch (\Exception $e) {
            Log::error('Firebase: Exception getting access token', ['error' => $e->getMessage()]);
            return null;
        }
    }
}
