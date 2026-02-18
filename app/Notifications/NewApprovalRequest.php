<?php

namespace App\Notifications;

use App\Channels\FcmChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewApprovalRequest extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $approvalRequest;

    /**
     * Create a new notification instance.
     */
    public function __construct($approvalRequest)
    {
        $this->approvalRequest = $approvalRequest;
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
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Pengajuan Approval Baru: ' . $this->approvalRequest->title)
                    ->line('Ada pengajuan approval baru dari ' . $this->approvalRequest->user->name)
                    ->line('Judul: ' . $this->approvalRequest->title)
                    ->line('Tipe: ' . $this->approvalRequest->type)
                    ->action('Lihat Detail', route('approval-requests.show', $this->approvalRequest->id))
                    ->line('Mohon segera direview.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title'   => 'Permintaan Approval Baru',
            'message' => 'Dari ' . $this->approvalRequest->user->name . ': ' . $this->approvalRequest->title,
            'link'    => route('approval-requests.show', $this->approvalRequest->id),
            'type'    => 'approval_request',
            'created_at' => now(),
        ];
    }

    /**
     * Get the Firebase Cloud Messaging representation.
     */
    public function toFcm(object $notifiable): array
    {
        return [
            'title' => '📋 Pengajuan Approval Baru',
            'body'  => 'Dari ' . $this->approvalRequest->user->name . ': ' . $this->approvalRequest->title,
            'data'  => [
                'type'   => 'approval_request',
                'id'     => (string) $this->approvalRequest->id,
                'url'    => route('approval-requests.show', $this->approvalRequest->id),
            ],
        ];
    }
}
