<?php

namespace App\Notifications;

use App\Channels\FcmChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EventReminder extends Notification
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
            'title'   => '💡 Pengingat Agenda: ' . $this->event->name,
            'message' => 'Jangan lupa hari ini ada agenda pada ' . $this->event->event_date->format('H:i') . ' WIB.',
            'link'    => route('events.show', $this->event->id),
            'type'    => 'event_reminder',
            'created_at' => now(),
        ];
    }

    public function toFcm(object $notifiable): array
    {
        return [
            'title' => '💡 Pengingat Agenda Hari Ini',
            'body'  => "Hari ini: {$this->event->name} jam " . $this->event->event_date->format('H:i') . " WIB. Jangan lupa absen ya!",
            'data'  => [
                'type' => 'event_reminder',
                'id'   => (string) $this->event->id,
                'url'  => route('events.show', $this->event->id),
            ],
        ];
    }
}
