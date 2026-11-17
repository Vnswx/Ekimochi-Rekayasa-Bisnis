<?php

namespace App\Services;

use App\Models\Promo;
use App\Models\User;
use App\Notifications\PromoPublishedNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class PromoNotificationService
{
    /**
     * Kirim notifikasi promo baru ke seluruh pengguna yang mengaktifkan
     * preferensi "Promo & Diskon".
     *
     * Notifikasi in-app (database) selalu dikirim ke penerima; channel email
     * hanya dicoba bila pengguna juga mengaktifkannya. Kegagalan email tidak
     * boleh menggagalkan penyimpanan promo di panel admin.
     *
     * @return array{sent:int, skipped:int, emailed:int, failed:int}
     */
    public function notifyPublished(Promo $promo): array
    {
        $sent = 0;
        $emailed = 0;
        $failed = 0;

        $skipped = User::query()
            ->where('role', 'user')
            ->where('notify_promo', false)
            ->count();

        User::query()
            ->where('role', 'user')
            ->where('notify_promo', true)
            ->chunkById(100, function ($users) use ($promo, &$sent, &$emailed, &$failed) {
                foreach ($users as $user) {
                    try {
                        Notification::sendNow($user, new PromoPublishedNotification($promo), ['database']);
                        $sent++;
                    } catch (\Throwable $e) {
                        $failed++;
                        Log::warning('Gagal mengirim notifikasi promo', [
                            'promo_id' => $promo->id,
                            'user_id' => $user->id,
                            'error' => $e->getMessage(),
                        ]);
                        continue;
                    }

                    if ($user->notificationPreference('email') && ! empty($user->email)) {
                        try {
                            Notification::sendNow($user, new PromoPublishedNotification($promo), ['mail']);
                            $emailed++;
                        } catch (\Throwable $e) {
                            Log::warning('Gagal mengirim email promo', [
                                'promo_id' => $promo->id,
                                'user_id' => $user->id,
                                'error' => $e->getMessage(),
                            ]);
                        }
                    }
                }
            });

        return compact('sent', 'skipped', 'emailed', 'failed');
    }
}
