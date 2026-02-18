<?php

namespace App\Notifications;

use App\Channels\FcmChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApprovalStatusUpdated extends Notification
{
    use Queueable;

    public $approvalRequest;
    public $status;

    public function __construct($approvalRequest, $status)
    {
        $this->approvalRequest = $approvalRequest;
        $this->status = $status;
    }

    public function via(object $notifiable): array
    {
        return ['database', FcmChannel::class];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $statusText = $this->status == 'approved' ? 'Disetujui' : 'Ditolak';
        return (new MailMessage)
                    ->subject('Status Pengajuan Diupdate: ' . $statusText)
                    ->line('Pengajuan Anda "' . $this->approvalRequest->title . '" telah ' . strtolower($statusText))
                    ->action('Lihat Detail', route('approval-requests.show', $this->approvalRequest->id))
                    ->line('Terima kasih.');
    }

    public function toArray(object $notifiable): array
    {
        $statusText = $this->status == 'approved' ? 'Disetujui' : 'Ditolak';
        return [
            'title'   => 'Status Pengajuan Diupdate',
            'message' => 'Pengajuan "' . $this->approvalRequest->title . '" telah ' . strtolower($statusText),
            'link'    => route('approval-requests.show', $this->approvalRequest->id),
            'type'    => 'approval_status',
            'created_at' => now(),
        ];
    }

    public function toFcm(object $notifiable): array
    {
        $statusText = $this->status == 'approved' ? 'Disetujui ✅' : 'Ditolak ❌';
        $emoji = $this->status == 'approved' ? '✅' : '❌';
        return [
            'title' => "{$emoji} Pengajuan {$statusText}",
            'body'  => 'Pengajuan "' . $this->approvalRequest->title . '" telah ' . strtolower(str_replace(['✅', '❌'], '', $statusText)),
            'data'  => [
                'type'   => 'approval_status',
                'status' => $this->status,
                'id'     => (string) $this->approvalRequest->id,
                'url'    => route('approval-requests.show', $this->approvalRequest->id),
            ],
        ];
    }
}
