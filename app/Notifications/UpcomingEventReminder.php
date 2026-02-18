<?php

namespace App\Notifications;

use App\Channels\FcmChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UpcomingEventReminder extends Notification
{
    use Queueable;

    public $event;

    public function __construct($event)
    {
        $this->event = $event;
    }

    public function via(object $notifiable): array
    {
        return ['database', FcmChannel::class];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Reminder Event: ' . $this->event->name)
                    ->line('Jangan lupa, besok ada kegiatan: ' . $this->event->name)
                    ->line('Waktu: ' . $this->event->event_date->format('d M H:i'))
                    ->action('Lihat Detail', route('events.show', $this->event->id))
                    ->line('Mohon hadir tepat waktu.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title'   => 'Reminder Event Besok',
            'message' => 'Besok: ' . $this->event->name . ' jam ' . $this->event->event_date->format('H:i'),
            'link'    => route('events.show', $this->event->id),
            'type'    => 'event_reminder',
            'created_at' => now(),
        ];
    }

    public function toFcm(object $notifiable): array
    {
        return [
            'title' => '📅 Reminder Event Besok',
            'body'  => $this->event->name . ' — ' . $this->event->event_date->format('d M Y, H:i'),
            'data'  => [
                'type' => 'event_reminder',
                'id'   => (string) $this->event->id,
                'url'  => route('events.show', $this->event->id),
            ],
        ];
    }
}
