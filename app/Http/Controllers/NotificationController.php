<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Kategori notifikasi yang bisa diatur pengguna.
     */
    private const CATEGORIES = [
        [
            'key' => 'promo',
            'label' => 'Promo & Diskon',
            'description' => 'Dapatkan notifikasi setiap kali admin menambahkan promo atau voucher baru.',
            'icon' => 'fa-solid fa-tags',
        ],
        [
            'key' => 'order',
            'label' => 'Status Pesanan',
            'description' => 'Update pembayaran, konfirmasi pesanan, dan status pengiriman.',
            'icon' => 'fa-solid fa-box-open',
        ],
        [
            'key' => 'product',
            'label' => 'Produk Baru',
            'description' => 'Varian rasa dan menu terbaru yang baru saja rilis.',
            'icon' => 'fa-solid fa-ice-cream',
        ],
        [
            'key' => 'outlet',
            'label' => 'Info Outlet',
            'description' => 'Outlet baru, perubahan jam operasional, dan event offline.',
            'icon' => 'fa-solid fa-store',
        ],
    ];

    /**
     * Channel pengiriman notifikasi.
     */
    private const CHANNELS = [
        [
            'key' => 'email',
            'label' => 'Email',
            'description' => 'Ringkasan notifikasi dikirim ke alamat email akunmu.',
            'icon' => 'fa-solid fa-envelope',
        ],
        [
            'key' => 'whatsapp',
            'label' => 'WhatsApp',
            'description' => 'Pesan singkat ke nomor WhatsApp yang terdaftar.',
            'icon' => 'fa-brands fa-whatsapp',
        ],
        [
            'key' => 'push',
            'label' => 'Push Browser',
            'description' => 'Notifikasi langsung muncul di browser saat Ekimochi dibuka.',
            'icon' => 'fa-solid fa-bell',
        ],
    ];

    /**
     * Halaman preferensi notifikasi + daftar notifikasi terbaru.
     */
    public function index(): View
    {
        $user = Auth::user();

        $notifications = $user->notifications()
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.notification', [
            'user' => $user,
            'categories' => self::CATEGORIES,
            'channels' => self::CHANNELS,
            'preferences' => $user->notificationPreferences(),
            'notifications' => $notifications,
            'unreadCount' => $user->unreadNotifications()->count(),
            'totalCount' => $user->notifications()->count(),
            'activePromoCount' => Promo::active()->count(),
        ]);
    }

    /**
     * Simpan preferensi notifikasi pengguna.
     */
    public function update(Request $request): RedirectResponse
    {
        $keys = array_keys(User::NOTIFICATION_DEFAULTS);

        $payload = [];
        foreach ($keys as $key) {
            $payload['notify_' . $key] = $request->boolean('notify_' . $key);
        }

        $validator = Validator::make($payload, array_fill_keys(
            array_map(fn ($key) => 'notify_' . $key, $keys),
            ['boolean']
        ));

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Auth::user()->update($validator->validated());

        $aktif = count(array_filter($validator->validated()));

        return back()->with('success', "Preferensi notifikasi berhasil disimpan ({$aktif} pengaturan aktif).");
    }

    /**
     * Tandai satu notifikasi sebagai sudah dibaca.
     */
    public function markAsRead(DatabaseNotification $notification): RedirectResponse
    {
        $this->authorizeNotification($notification);

        $notification->markAsRead();

        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    /**
     * Tandai semua notifikasi pengguna sebagai sudah dibaca.
     */
    public function markAllAsRead(): RedirectResponse
    {
        $user = Auth::user();
        $jumlah = $user->unreadNotifications()->count();

        $user->unreadNotifications->markAsRead();

        return back()->with('success', $jumlah > 0
            ? "{$jumlah} notifikasi ditandai sudah dibaca."
            : 'Tidak ada notifikasi yang belum dibaca.');
    }

    /**
     * Pastikan notifikasi memang milik pengguna yang sedang login.
     */
    private function authorizeNotification(DatabaseNotification $notification): void
    {
        abort_unless(
            $notification->notifiable_type === User::class
                && (int) $notification->notifiable_id === (int) Auth::id(),
            403
        );
    }
}
