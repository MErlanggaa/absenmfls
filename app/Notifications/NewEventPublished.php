<?php

namespace App\Notifications;

use App\Channels\FcmChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewEventPublished extends Notification
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

    public function toArray(object $notifiable): array
    {
        return [
            'title'   => 'Agenda Baru: ' . $this->event->name,
            'message' => 'Ada agenda baru pada ' . $this->event->event_date->format('d M, H:i') . ' WIB.',
            'link'    => route('events.show', $this->event->id),
            'type'    => 'event_new',
            'created_at' => now(),
        ];
    }

    public function toFcm(object $notifiable): array
    {
        return [
            'title' => 'Agenda Baru',
            'body'  => 'Ada agenda baru. Silahkan cek aplikasi.',
            'data'  => [
                'type' => 'event_new',
                'id'   => (string) $this->event->id,
                'url'  => route('events.show', $this->event->id),
            ],
        ];
    }
}
