<?php

namespace App\Notifications;

use App\Channels\FcmChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class KpiNotification extends Notification
{
    use Queueable;

    public $kpi;
    public $status; // 'created' or 'approved'

    /**
     * Create a new notification instance.
     */
    public function __construct($kpi, $status)
    {
        $this->kpi = $kpi;
        $this->status = $status;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', FcmChannel::class];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        if ($this->status === 'created') {
            return [
                'title' => 'KPI Baru: Menunggu Persetujuan',
                'message' => 'KPI untuk ' . $this->kpi->user->name . ' telah diisi dan menunggu pengesahan Anda.',
                'link' => route('kpis.show', $this->kpi->id),
                'type' => 'kpi_created',
                'created_at' => now(),
            ];
        }

        return [
            'title' => 'KPI Disahkan',
            'message' => 'KPI Anda periode ' . $this->kpi->period_date->format('F Y') . ' telah disahkan oleh Project Director.',
            'link' => route('kpis.show', $this->kpi->id),
            'type' => 'kpi_approved',
            'created_at' => now(),
        ];
    }

    /**
     * Get the Firebase Cloud Messaging representation.
     */
    public function toFcm(object $notifiable): array
    {
        if ($this->status === 'created') {
            return [
                'title' => '📋 KPI Baru Perlu Approval',
                'body' => 'KPI ' . $this->kpi->user->name . ' menunggu pengesahan Anda.',
                'data' => [
                    'type' => 'kpi_created',
                    'id' => (string)$this->kpi->id,
                    'url' => route('kpis.show', $this->kpi->id),
                    'sound_url' => '/hidup-jokowi.mp3', // Custom notification sound
                ],
            ];
        }

        return [
            'title' => '✅ KPI Berhasil Disahkan',
            'body' => 'KPI periode ' . $this->kpi->period_date->format('F Y') . ' telah disahkan.',
            'data' => [
                'type' => 'kpi_approved',
                'id' => (string)$this->kpi->id,
                'url' => route('kpis.show', $this->kpi->id),
                'sound_url' => '/hidup-jokowi.mp3', // Custom notification sound
            ],
        ];
    }
}
