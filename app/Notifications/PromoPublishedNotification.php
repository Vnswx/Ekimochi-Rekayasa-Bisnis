<?php

namespace App\Notifications;

use App\Models\Promo;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PromoPublishedNotification extends Notification
{
    use Queueable;

    public function __construct(public Promo $promo)
    {
    }

    /**
     * Channel yang dipakai: database selalu (in-app), email bila
     * pengguna mengaktifkan preferensi promo + email.
     */
    public function via(object $notifiable): array
    {
        $channels = ['database'];

        if (method_exists($notifiable, 'notificationPreference')
            && $notifiable->notificationPreference('promo')
            && $notifiable->notificationPreference('email')
            && ! empty($notifiable->email)) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toArray(object $notifiable): array
    {
        $periode = $this->promo->ends_at
            ? 'Berlaku sampai ' . $this->promo->ends_at->translatedFormat('d M Y') . '.'
            : 'Berlaku selama persediaan masih ada.';

        return [
            'promo_id' => $this->promo->id,
            'category' => 'promo',
            'title' => 'Promo baru: ' . $this->promo->name,
            'message' => 'Diskon ' . $this->promo->discountLabel() . ' untuk promo ' . $this->promo->name . '. ' . $periode,
            'url' => route('blog'),
            'icon' => 'fa-solid fa-tags',
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $periode = $this->promo->ends_at
            ? 'Promo berlaku sampai ' . $this->promo->ends_at->translatedFormat('d F Y') . '.'
            : 'Promo berlaku selama persediaan masih ada.';

        return (new MailMessage)
            ->subject('Promo baru Ekimochi: ' . $this->promo->name)
            ->greeting('Halo ' . ($notifiable->name ?? 'Sahabat Ekimochi') . '!')
            ->line('Ada promo baru untukmu: ' . $this->promo->name . ' dengan diskon ' . $this->promo->discountLabel() . '.')
            ->line($periode)
            ->action('Lihat Promo', route('blog'))
            ->line('Kamu menerima email ini karena mengaktifkan notifikasi promo di halaman Preferensi Notifikasi.');
    }
}
