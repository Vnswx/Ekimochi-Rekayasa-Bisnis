<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePromoRequest;
use App\Http\Requests\UpdatePromoRequest;
use App\Models\Promo;
use App\Services\PromoNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PromoController extends Controller
{
    /**
     * Display promo & discount management page.
     */
    public function index(): View
    {
        $promos = Promo::latest()->get();

        // Statistik
        $totalPromos = Promo::count();
        $activePromos = Promo::active()->count();
        $upcomingPromos = Promo::where('is_active', true)
            ->where('starts_at', '>', now())
            ->count();
        $expiredPromos = Promo::where('is_active', true)
            ->whereNotNull('ends_at')
            ->where('ends_at', '<', now())
            ->count();

        return view('admin.promo.index', compact(
            'promos',
            'totalPromos',
            'activePromos',
            'upcomingPromos',
            'expiredPromos'
        ));
    }

    /**
     * Show the form for creating a new promo.
     */
    public function create(): View
    {
        return view('admin.promo.create');
    }

    /**
     * Store a newly created promo.
     */
    public function store(StorePromoRequest $request, PromoNotificationService $notifier): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $promo = Promo::create($data);

        $pesan = 'Promo berhasil ditambahkan!';

        if ($promo->isCurrentlyActive()) {
            $hasil = $notifier->notifyPublished($promo);
            $pesan .= ' ' . $this->ringkasanNotifikasi($hasil);
        }

        return redirect()->route('admin.promo.index')->with('success', $pesan);
    }

    /**
     * Show the form for editing the specified promo.
     */
    public function edit(Promo $promo): View
    {
        return view('admin.promo.edit', compact('promo'));
    }

    /**
     * Update the specified promo.
     */
    public function update(UpdatePromoRequest $request, Promo $promo): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $promo->update($data);

        return redirect()->route('admin.promo.index')
            ->with('success', "Promo {$promo->name} berhasil diperbarui!");
    }

    /**
     * Remove the specified promo.
     */
    public function destroy(Promo $promo): RedirectResponse
    {
        $promo->delete();

        return redirect()->route('admin.promo.index')
            ->with('success', 'Promo berhasil dihapus!');
    }

    /**
     * Toggle promo active status directly.
     */
    public function toggle(Promo $promo, PromoNotificationService $notifier): RedirectResponse
    {
        $sebelumnyaAktif = $promo->isCurrentlyActive();

        $promo->update(['is_active' => !$promo->is_active]);

        $pesan = "Promo {$promo->name} berhasil " . ($promo->is_active ? 'diaktifkan' : 'dinonaktifkan') . '!';

        // Kirim notifikasi hanya saat promo berubah dari tidak berlaku menjadi berlaku.
        if (! $sebelumnyaAktif && $promo->fresh()->isCurrentlyActive()) {
            $hasil = $notifier->notifyPublished($promo->fresh());
            $pesan .= ' ' . $this->ringkasanNotifikasi($hasil);
        }

        return back()->with('success', $pesan);
    }

    /**
     * Ringkasan hasil pengiriman notifikasi promo untuk flash message.
     *
     * @param  array{sent:int, skipped:int, emailed:int, failed:int}  $hasil
     */
    private function ringkasanNotifikasi(array $hasil): string
    {
        if ($hasil['sent'] === 0) {
            return 'Belum ada pengguna dengan preferensi notifikasi promo yang aktif.';
        }

        $pesan = "{$hasil['sent']} pengguna menerima notifikasi promo";

        if ($hasil['emailed'] > 0) {
            $pesan .= " ({$hasil['emailed']} via email)";
        }

        if ($hasil['failed'] > 0) {
            $pesan .= ", {$hasil['failed']} gagal";
        }

        return $pesan . '.';
    }
}