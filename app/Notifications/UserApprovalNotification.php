<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserApprovalNotification extends Notification
{
    use Queueable;

    protected $status;
    protected $alasanPenolakan;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $status, ?string $alasanPenolakan = null)
    {
        $this->status = $status;
        $this->alasanPenolakan = $alasanPenolakan;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        if ($this->status === 'approved') {
            return (new MailMessage)
                ->subject('Akun Anda Telah Disetujui')
                ->greeting('Halo ' . $notifiable->nama . ',')
                ->line('Selamat! Akun Anda telah disetujui oleh Administrator.')
                ->line('Anda sekarang dapat login ke sistem Inventaris Pupuk menggunakan kredensial yang telah Anda daftarkan.')
                ->action('Login Sekarang', url('/login'))
                ->line('Terima kasih telah mendaftar di Sistem Inventaris Pupuk Dinas Pertanian.');
        } else {
            return (new MailMessage)
                ->subject('Pendaftaran Akun Ditolak')
                ->greeting('Halo ' . $notifiable->nama . ',')
                ->line('Mohon maaf, pendaftaran akun Anda telah ditolak oleh Administrator.')
                ->line('Alasan penolakan: ' . ($this->alasanPenolakan ?? 'Tidak ada alasan yang diberikan.'))
                ->line('Jika Anda merasa ini adalah kesalahan atau ingin mengajukan pertanyaan lebih lanjut, silakan hubungi Administrator.')
                ->line('Terima kasih.');
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'status' => $this->status,
            'alasan_penolakan' => $this->alasanPenolakan,
        ];
    }
}
