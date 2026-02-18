<?php

namespace App\Channels;

use App\Models\DeviceToken;
use App\Services\FirebaseService;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

/**
 * Custom Laravel Notification Channel for Firebase Cloud Messaging.
 *
 * Usage in Notification class:
 *   public function via($notifiable): array {
 *       return ['database', FcmChannel::class];
 *   }
 *
 *   public function toFcm($notifiable): array {
 *       return [
 *           'title' => 'Hello',
 *           'body'  => 'World',
 *           'data'  => ['key' => 'value'],
 *       ];
 *   }
 */
class FcmChannel
{
    public function __construct(protected FirebaseService $firebase) {}

    /**
     * Send the given notification.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        try {
            if (!method_exists($notification, 'toFcm')) {
                Log::warning('FcmChannel: Method toFcm not found on notification ' . get_class($notification));
                return;
            }

            $fcmData = $notification->toFcm($notifiable);
            if (empty($fcmData)) {
                Log::warning('FcmChannel: toFcm returned empty data for notification ' . get_class($notification));
                return;
            }

            $title = $fcmData['title'] ?? 'Notifikasi Baru';
            $body  = $fcmData['body']  ?? '';
            $data  = $fcmData['data']  ?? [];

            Log::info("FcmChannel: Preparing to send to user {$notifiable->id}. Title: {$title}");

            $tokens = DeviceToken::where('user_id', $notifiable->id)
                ->whereNotNull('token')
                ->pluck('token')
                ->toArray();

            if (empty($tokens)) {
                Log::warning("FcmChannel: No device tokens found for user {$notifiable->id}");
                return;
            }

            Log::info("FcmChannel: Found " . count($tokens) . " tokens for user {$notifiable->id}. Sending...");

            $results = $this->firebase->sendToMultiple($tokens, $title, $body, $data);
            
            Log::info('FcmChannel: Send results', $results);
            
        } catch (\Exception $e) {
            Log::error('FcmChannel Error: ' . $e->getMessage());
            // We catch so 'database' channel still works
        }
    }
}
