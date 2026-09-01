<?php

namespace App\Notifications;

use App\Models\LeaveRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaveStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public LeaveRequest $leaveRequest)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $status = $this->leaveRequest->status === 'approved' ? 'disetujui' : 'ditolak';
        $leaveType = $this->leaveRequest->leaveType->name;
        $period = $this->leaveRequest->start_date->format('d M Y') . ' - ' . $this->leaveRequest->end_date->format('d M Y');

        $mail = (new MailMessage)
            ->subject("Pengajuan {$leaveType} kamu {$status}")
            ->greeting("Halo {$notifiable->name},")
            ->line("Pengajuan {$leaveType} kamu untuk tanggal {$period} telah {$status}.");

        if ($this->leaveRequest->status === 'rejected') {
            $mail->line('Kalau ada pertanyaan soal alasan penolakan, silakan hubungi manager kamu langsung.');
        }

        return $mail->line('Terima kasih.');
    }
}
