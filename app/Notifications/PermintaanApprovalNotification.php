<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PermintaanApprovalNotification extends Notification
{
    use Queueable;

    protected $status;
    protected $alasanPenolakan;
    protected $permintaan;

    public function __construct(string $status, ?string $alasanPenolakan = null, $permintaan = null)
    {
        $this->status = $status;
        $this->alasanPenolakan = $alasanPenolakan;
        $this->permintaan = $permintaan;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        if ($this->status === 'approved') {
            $message = (new MailMessage)
                ->subject('✅ Permintaan Distribusi Pupuk Disetujui')
                ->greeting('Halo ' . $notifiable->nama . ',')
                ->line('Kabar baik! Permintaan distribusi pupuk Anda telah **disetujui** oleh admin.')
                ->line('📋 **Detail Permintaan:**');

            // Add items detail if available
            if ($this->permintaan && isset($this->permintaan->items)) {
                foreach ($this->permintaan->items as $item) {
                    $namaPupuk = $item['nama_pupuk'] ?? "Pupuk ID: {$item['pupuk_id']}";
                    $message->line("• {$namaPupuk}: **{$item['jumlah_diminta']} unit**");
                }
            }

            return $message
                ->line('📅 **Tanggal Persetujuan:** ' . now()->format('d F Y, H:i'))
                ->line('')
                ->line('Distribusi akan segera diproses. Anda dapat memantau status distribusi melalui dashboard.')
                ->action('Lihat Dashboard', url('/kepala-desa/dashboard'))
                ->line('')
                ->line('Terima kasih atas kerja sama Anda!')
                ->salutation('Salam,
Tim Dinas Pertanian');
        } else {
            $message = (new MailMessage)
                ->error()
                ->subject('❌ Permintaan Distribusi Pupuk Ditolak')
                ->greeting('Halo ' . $notifiable->nama . ',')
                ->line('Mohon maaf, permintaan distribusi pupuk Anda **ditolak** oleh admin.')
                ->line('📋 **Detail Permintaan:**');

            // Add items detail if available
            if ($this->permintaan && isset($this->permintaan->items)) {
                foreach ($this->permintaan->items as $item) {
                    $namaPupuk = $item['nama_pupuk'] ?? "Pupuk ID: {$item['pupuk_id']}";
                    $message->line("• {$namaPupuk}: {$item['jumlah_diminta']} unit");
                }
            }

            return $message
                ->line('')
                ->line('📝 **Alasan Penolakan:**')
                ->line('> ' . $this->alasanPenolakan)
                ->line('')
                ->line('📅 **Tanggal Penolakan:** ' . now()->format('d F Y, H:i'))
                ->line('')
                ->line('Anda dapat mengajukan permintaan baru dengan memperbaiki kekurangan yang disebutkan di atas.')
                ->action('Ajukan Permintaan Baru', url('/kepala-desa/dashboard'))
                ->line('')
                ->line('Jika ada pertanyaan atau memerlukan klarifikasi lebih lanjut, silakan hubungi admin kami.')
                ->salutation('Salam,
Tim Dinas Pertanian');
        }
    }

    public function toArray(object $notifiable): array
    {
        return [
            'status' => $this->status,
            'alasan_penolakan' => $this->alasanPenolakan,
            'permintaan_id' => $this->permintaan->id ?? null,
        ];
    }
}
