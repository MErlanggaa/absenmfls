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
                return;
            }

            $fcmData = $notification->toFcm($notifiable);
            if (empty($fcmData)) return;

            $title = $fcmData['title'] ?? 'Notifikasi Baru';
            $body  = $fcmData['body']  ?? '';
            $data  = $fcmData['data']  ?? [];

            $tokens = DeviceToken::where('user_id', $notifiable->id)
                ->whereNotNull('token')
                ->pluck('token')
                ->toArray();

            if (empty($tokens)) return;

            $this->firebase->sendToMultiple($tokens, $title, $body, $data);
            
        } catch (\Exception $e) {
            Log::error('FcmChannel Error: ' . $e->getMessage());
            // We catch so 'database' channel still works
        }
    }
}
