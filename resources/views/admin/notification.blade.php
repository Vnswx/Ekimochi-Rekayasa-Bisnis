@extends('layouts.app')

@section('title', 'Preferensi Notifikasi - Ekimochi')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

  <!-- Breadcrumb -->
  <nav class="flex flex-wrap items-center gap-2 text-xs text-gray-500 mb-4" aria-label="Breadcrumb">
    <a href="{{ route('home') }}" class="hover:text-[#D26986] transition">Beranda</a>
    <i class="fa-solid fa-chevron-right text-[9px] text-gray-300"></i>
    <a href="{{ route('profile.show') }}" class="hover:text-[#D26986] transition">Profil Saya</a>
    <i class="fa-solid fa-chevron-right text-[9px] text-gray-300"></i>
    <span class="font-semibold text-[#D26986]">Notifikasi</span>
  </nav>

  <!-- Hero -->
  <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#D26986] via-[#C95F7D] to-[#A94464] text-white p-6 sm:p-8 shadow-lg mb-6">
    <div class="absolute -top-16 -right-10 w-56 h-56 rounded-full bg-white/10" aria-hidden="true"></div>
    <div class="absolute -bottom-24 -left-10 w-64 h-64 rounded-full bg-white/5" aria-hidden="true"></div>

    <div class="relative flex flex-col sm:flex-row sm:items-center gap-5">
      <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-white/15 border border-white/20 flex items-center justify-center text-2xl sm:text-3xl shrink-0">
        <i class="fa-solid fa-bell"></i>
      </div>
      <div class="flex-1 min-w-0">
        <h1 class="text-2xl sm:text-3xl font-bold">Preferensi Notifikasi</h1>
        <p class="text-sm text-white/85 mt-2 max-w-2xl leading-relaxed">
          Atur notifikasi apa saja yang ingin kamu terima dari Ekimochi. Setiap kali admin menambahkan promo baru,
          notifikasi otomatis dikirim ke pengguna yang mengaktifkan kategori <span class="font-semibold text-white">Promo &amp; Diskon</span>.
        </p>
      </div>
    </div>

    <div class="relative flex flex-wrap gap-2 mt-6">
      <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/15 border border-white/20 text-xs font-semibold">
        <i class="fa-solid fa-envelope-open-text"></i>
        {{ $unreadCount }} belum dibaca
      </span>
      <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/15 border border-white/20 text-xs font-semibold">
        <i class="fa-solid fa-inbox"></i>
        {{ $totalCount }} total notifikasi
      </span>
      <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/15 border border-white/20 text-xs font-semibold">
        <i class="fa-solid fa-tags"></i>
        {{ $activePromoCount }} promo aktif
      </span>
    </div>
  </div>

  <!-- Flash -->
  @if(session('success'))
  <div class="mb-6 p-4 rounded-2xl bg-green-50 border border-green-200 text-green-800 text-sm flex items-start gap-3" role="status">
    <i class="fa-solid fa-circle-check text-lg mt-0.5"></i>
    <span>{{ session('success') }}</span>
  </div>
  @endif

  @if($errors->any())
  <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-sm" role="alert">
    <div class="flex items-start gap-3">
      <i class="fa-solid fa-circle-exclamation text-lg mt-0.5"></i>
      <div>
        <p class="font-semibold">Preferensi gagal disimpan</p>
        <ul class="mt-1 list-disc list-inside text-xs space-y-0.5">
          @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
  @endif

  @if(!$user->email)
  <div class="mb-6 p-4 rounded-2xl bg-amber-50 border-l-4 border-amber-400 text-amber-800 text-xs flex items-start gap-3">
    <i class="fa-solid fa-triangle-exclamation text-base mt-0.5"></i>
    <span>Email akunmu belum diisi, jadi notifikasi email belum bisa dikirim. <a href="{{ route('profile.edit') }}" class="font-bold underline">Lengkapi email</a> terlebih dahulu.</span>
  </div>
  @endif

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Kolom Pengaturan -->
    <div class="lg:col-span-2 space-y-6">

      <form id="notification-form" method="POST" action="{{ route('notification.update') }}" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Kategori -->
        <section class="bg-white rounded-3xl border border-rose-100 shadow-sm p-5 sm:p-6">
          <div class="flex items-start gap-3 pb-4 border-b border-rose-100">
            <span class="w-10 h-10 rounded-xl bg-rose-50 text-[#D26986] flex items-center justify-center shrink-0">
              <i class="fa-solid fa-layer-group"></i>
            </span>
            <div class="flex-1 min-w-0">
              <h2 class="text-base font-bold text-gray-900">Kategori Notifikasi</h2>
              <p class="text-xs text-gray-500 mt-1">Pilih topik yang ingin kamu terima.</p>
            </div>
          </div>

          <div class="divide-y divide-rose-50">
            @foreach($categories as $category)
            <label for="notify_{{ $category['key'] }}" class="flex items-start gap-4 py-4 cursor-pointer group">
              <span class="w-11 h-11 rounded-xl bg-rose-50 text-[#D26986] flex items-center justify-center shrink-0 group-hover:bg-rose-100 transition">
                <i class="{{ $category['icon'] }}"></i>
              </span>
              <span class="flex-1 min-w-0">
                <span class="block text-sm font-semibold text-gray-900">{{ $category['label'] }}</span>
                <span class="block text-xs text-gray-500 mt-1 leading-relaxed">{{ $category['description'] }}</span>
                @if($category['key'] === 'promo')
                <span class="inline-flex items-center gap-1 mt-2 px-2 py-0.5 rounded-full bg-rose-50 text-[10px] font-bold text-[#D26986] uppercase tracking-wide">
                  <i class="fa-solid fa-wand-magic-sparkles"></i> Otomatis saat admin tambah promo
                </span>
                @endif
              </span>
              <span class="relative inline-flex shrink-0 mt-1">
                <input id="notify_{{ $category['key'] }}" name="notify_{{ $category['key'] }}" type="checkbox" value="1"
                       class="peer sr-only" @checked($preferences[$category['key']])>
                <span class="w-11 h-6 rounded-full bg-gray-200 peer-checked:bg-[#D26986] transition-colors peer-focus-visible:ring-2 peer-focus-visible:ring-offset-2 peer-focus-visible:ring-[#D26986]"></span>
                <span class="pointer-events-none absolute top-0.5 left-0.5 w-5 h-5 rounded-full bg-white shadow transition-transform peer-checked:translate-x-5"></span>
              </span>
            </label>
            @endforeach
          </div>
        </section>

        <!-- Channel -->
        <section class="bg-white rounded-3xl border border-rose-100 shadow-sm p-5 sm:p-6">
          <div class="flex items-start gap-3 pb-4 border-b border-rose-100">
            <span class="w-10 h-10 rounded-xl bg-rose-50 text-[#D26986] flex items-center justify-center shrink-0">
              <i class="fa-solid fa-paper-plane"></i>
            </span>
            <div class="flex-1 min-w-0">
              <h2 class="text-base font-bold text-gray-900">Cara Menerima Notifikasi</h2>
              <p class="text-xs text-gray-500 mt-1">Notifikasi in-app di lonceng navbar selalu aktif dan tidak bisa dimatikan.</p>
            </div>
          </div>

          <div class="divide-y divide-rose-50">
            @foreach($channels as $channel)
            <label for="notify_{{ $channel['key'] }}" class="flex items-start gap-4 py-4 cursor-pointer group">
              <span class="w-11 h-11 rounded-xl bg-rose-50 text-[#D26986] flex items-center justify-center shrink-0 group-hover:bg-rose-100 transition">
                <i class="{{ $channel['icon'] }}"></i>
              </span>
              <span class="flex-1 min-w-0">
                <span class="block text-sm font-semibold text-gray-900">{{ $channel['label'] }}</span>
                <span class="block text-xs text-gray-500 mt-1 leading-relaxed">{{ $channel['description'] }}</span>
                @if($channel['key'] === 'whatsapp')
                <span class="inline-block mt-2 text-[10px] font-semibold text-amber-600">
                  <i class="fa-solid fa-circle-info"></i> Nomor WhatsApp belum tersedia di profil, hubungi admin untuk mendaftarkan.
                </span>
                @endif
              </span>
              <span class="relative inline-flex shrink-0 mt-1">
                <input id="notify_{{ $channel['key'] }}" name="notify_{{ $channel['key'] }}" type="checkbox" value="1"
                       class="peer sr-only" @checked($preferences[$channel['key']])>
                <span class="w-11 h-6 rounded-full bg-gray-200 peer-checked:bg-[#D26986] transition-colors peer-focus-visible:ring-2 peer-focus-visible:ring-offset-2 peer-focus-visible:ring-[#D26986]"></span>
                <span class="pointer-events-none absolute top-0.5 left-0.5 w-5 h-5 rounded-full bg-white shadow transition-transform peer-checked:translate-x-5"></span>
              </span>
            </label>
            @endforeach
          </div>
        </section>

        <!-- Action Bar -->
        <div class="sticky bottom-3 z-20 bg-white/95 backdrop-blur border border-rose-100 rounded-2xl shadow-lg p-3 sm:p-4 flex flex-wrap items-center justify-between gap-3">
          <p class="text-xs text-gray-500">
            <i class="fa-solid fa-sliders text-[#D26986] mr-1"></i>
            <span id="pref-counter">0 dari 0</span> pengaturan aktif
          </p>
          <div class="flex flex-wrap items-center gap-2">
            <button type="button" data-pref-all="on" class="px-3 py-2 rounded-xl border border-rose-200 text-xs font-semibold text-[#D26986] hover:bg-rose-50 transition">
              Aktifkan semua
            </button>
            <button type="button" data-pref-all="off" class="px-3 py-2 rounded-xl border border-gray-200 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition">
              Matikan semua
            </button>
            <button type="submit" class="inline-flex items-center gap-2 bg-[#D26986] hover:bg-[#BD5773] text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md transition transform active:scale-95">
              <i class="fa-solid fa-floppy-disk"></i>
              Simpan Perubahan
            </button>
          </div>
        </div>
      </form>

      <!-- Riwayat Notifikasi -->
      <section class="bg-white rounded-3xl border border-rose-100 shadow-sm p-5 sm:p-6">
        <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-rose-100">
          <div class="flex items-start gap-3">
            <span class="w-10 h-10 rounded-xl bg-rose-50 text-[#D26986] flex items-center justify-center shrink-0">
              <i class="fa-solid fa-clock-rotate-left"></i>
            </span>
            <div>
              <h2 class="text-base font-bold text-gray-900">Notifikasi Terbaru</h2>
              <p class="text-xs text-gray-500 mt-1">10 notifikasi terakhir untuk akunmu.</p>
            </div>
          </div>

          @if($unreadCount > 0)
          <form method="POST" action="{{ route('notification.readAll') }}">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl border border-rose-200 text-xs font-semibold text-[#D26986] hover:bg-rose-50 transition">
              <i class="fa-solid fa-check-double"></i>
              Tandai semua dibaca
            </button>
          </form>
          @endif
        </div>

        <div class="mt-4 space-y-3">
          @forelse($notifications as $notification)
          <article class="flex items-start gap-3 p-4 rounded-2xl border {{ $notification->read_at ? 'border-rose-100 bg-white' : 'border-rose-200 bg-rose-50/70' }}">
            <span class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 {{ $notification->read_at ? 'bg-gray-100 text-gray-400' : 'bg-[#D26986] text-white' }}">
              <i class="{{ data_get($notification->data, 'icon', 'fa-solid fa-bell') }}"></i>
            </span>
            <div class="flex-1 min-w-0">
              <div class="flex items-start gap-2">
                <h3 class="text-sm font-bold text-gray-800 leading-snug">{{ data_get($notification->data, 'title', 'Notifikasi') }}</h3>
                @unless($notification->read_at)
                <span class="mt-1 w-2 h-2 rounded-full bg-[#D26986] shrink-0" aria-label="Belum dibaca"></span>
                @endunless
              </div>
              <p class="text-xs text-gray-600 mt-1 leading-relaxed">{{ data_get($notification->data, 'message', '') }}</p>
              <div class="flex flex-wrap items-center gap-3 mt-2 text-[11px] text-gray-400">
                <span><i class="fa-regular fa-clock"></i> {{ $notification->created_at->diffForHumans() }}</span>
                <a href="{{ data_get($notification->data, 'url', route('blog')) }}" class="font-semibold text-[#D26986] hover:underline">Lihat detail</a>
                @unless($notification->read_at)
                <form method="POST" action="{{ route('notification.read', $notification) }}" class="inline">
                  @csrf
                  <button type="submit" class="font-semibold text-gray-500 hover:text-[#D26986] transition">Tandai dibaca</button>
                </form>
                @endunless
              </div>
            </div>
          </article>
          @empty
          <div class="flex flex-col items-center justify-center text-center py-10 text-gray-400">
            <span class="w-16 h-16 rounded-full bg-rose-50 flex items-center justify-center mb-3">
              <i class="fa-regular fa-bell-slash text-2xl text-[#D26986]"></i>
            </span>
            <p class="text-sm font-semibold text-gray-600">Belum ada notifikasi</p>
            <p class="text-xs mt-1 max-w-xs">Aktifkan kategori Promo &amp; Diskon untuk menerima info promo terbaru dari admin.</p>
          </div>
          @endforelse
        </div>
      </section>
    </div>

    <!-- Sidebar Info -->
    <aside class="space-y-6">
      <section class="bg-white rounded-3xl border border-rose-100 shadow-sm p-5 sm:p-6">
        <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
          <i class="fa-solid fa-wand-magic-sparkles text-[#D26986]"></i>
          Bagaimana notifikasi promo bekerja?
        </h2>
        <ol class="mt-4 space-y-4">
          <li class="flex gap-3">
            <span class="w-7 h-7 rounded-full bg-rose-50 text-[#D26986] text-xs font-bold flex items-center justify-center shrink-0">1</span>
            <p class="text-xs text-gray-600 leading-relaxed">Admin menambahkan promo baru di <span class="font-semibold text-gray-800">Admin &rsaquo; Promo &amp; Diskon</span>.</p>
          </li>
          <li class="flex gap-3">
            <span class="w-7 h-7 rounded-full bg-rose-50 text-[#D26986] text-xs font-bold flex items-center justify-center shrink-0">2</span>
            <p class="text-xs text-gray-600 leading-relaxed">Sistem mengecek pengguna yang mengaktifkan kategori Promo &amp; Diskon.</p>
          </li>
          <li class="flex gap-3">
            <span class="w-7 h-7 rounded-full bg-rose-50 text-[#D26986] text-xs font-bold flex items-center justify-center shrink-0">3</span>
            <p class="text-xs text-gray-600 leading-relaxed">Notifikasi muncul di lonceng navbar, dan dikirim ke email bila channel email aktif.</p>
          </li>
        </ol>
      </section>

      <section class="bg-gradient-to-br from-rose-50 to-white rounded-3xl border border-rose-100 shadow-sm p-5 sm:p-6">
        <span class="w-10 h-10 rounded-xl bg-white text-[#D26986] flex items-center justify-center shadow-sm">
          <i class="fa-solid fa-tags"></i>
        </span>
        <h2 class="text-sm font-bold text-gray-900 mt-3">Promo sedang berjalan</h2>
        <p class="text-xs text-gray-500 mt-1">{{ $activePromoCount }} promo aktif saat ini. Jangan sampai terlewat!</p>
        <a href="{{ route('blog') }}" class="inline-flex items-center justify-center gap-2 w-full mt-4 bg-[#D26986] hover:bg-[#BD5773] text-white px-4 py-2.5 rounded-xl text-xs font-bold shadow-md transition">
          <i class="fa-solid fa-arrow-right"></i>
          Lihat Semua Promo
        </a>
      </section>

      <section class="bg-white rounded-3xl border border-rose-100 shadow-sm p-5 sm:p-6">
        <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
          <i class="fa-solid fa-headset text-[#D26986]"></i>
          Butuh bantuan?
        </h2>
        <p class="text-xs text-gray-500 mt-2 leading-relaxed">Hubungi tim Ekimochi bila notifikasi tidak masuk atau ingin mengubah data kontak.</p>
        <div class="mt-4 space-y-2">
          <a href="{{ route('faq') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl border border-rose-100 text-xs font-semibold text-gray-700 hover:border-[#D26986] hover:text-[#D26986] transition">
            <span><i class="fa-solid fa-circle-question mr-2"></i> Baca FAQ</span>
            <i class="fa-solid fa-chevron-right text-[9px]"></i>
          </a>
          <a href="{{ route('outlets.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl border border-rose-100 text-xs font-semibold text-gray-700 hover:border-[#D26986] hover:text-[#D26986] transition">
            <span><i class="fa-solid fa-store mr-2"></i> Hubungi Outlet</span>
            <i class="fa-solid fa-chevron-right text-[9px]"></i>
          </a>
          <a href="{{ route('profile.show') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl border border-rose-100 text-xs font-semibold text-gray-700 hover:border-[#D26986] hover:text-[#D26986] transition">
            <span><i class="fa-solid fa-user mr-2"></i> Kembali ke Profil</span>
            <i class="fa-solid fa-chevron-right text-[9px]"></i>
          </a>
        </div>
      </section>
    </aside>
  </div>
</div>

<script>
  (function () {
    var form = document.getElementById('notification-form');
    if (!form) return;

    var boxes = Array.prototype.slice.call(form.querySelectorAll('input[type="checkbox"][name^="notify_"]'));
    var counter = document.getElementById('pref-counter');

    function refresh() {
      if (!counter) return;
      var aktif = boxes.filter(function (box) { return box.checked; }).length;
      counter.textContent = aktif + ' dari ' + boxes.length;
    }

    boxes.forEach(function (box) { box.addEventListener('change', refresh); });
    refresh();

    Array.prototype.forEach.call(document.querySelectorAll('[data-pref-all]'), function (button) {
      button.addEventListener('click', function () {
        var aktifkan = button.getAttribute('data-pref-all') === 'on';
        boxes.forEach(function (box) { box.checked = aktifkan; });
        refresh();
      });
    });
  })();
</script>
@endsection
