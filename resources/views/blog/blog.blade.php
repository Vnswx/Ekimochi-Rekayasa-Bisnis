@extends('layouts.app')

@section('title', 'Blog & Kabar Terbaru - Ekimochi')

@section('content')
@php
    $posts = [
        [
            'category' => 'Produk Baru',
            'title' => 'Lotus Biscoff & Taro Resmi Jadi Menu Tetap',
            'date' => '2026-11-14',
            'date_label' => '14 November 2026',
            'image' => 'storage/products/lotus.png',
            'excerpt' => 'Dua rasa yang sebelumnya hanya hadir sebagai menu terbatas kini masuk ke daftar menu harian di seluruh outlet Ekimochi.',
            'body' => [
                'Setelah tiga bulan hadir sebagai menu terbatas dan selalu habis sebelum sore, Lotus Biscoff dan Taro akhirnya resmi menjadi menu tetap. Keduanya bisa dipesan langsung di outlet maupun lewat katalog online.',
                'Lotus Biscoff memadukan mochi lembut dengan krim biskuit karamel dan taburan remah di bagian atas, sedangkan Taro menghadirkan rasa talas yang gurih dengan isian krim yang lebih ringan. Keduanya tersedia dalam satuan maupun paket box.',
                'Harga satuan dimulai dari Rp 5.000 dan sudah termasuk dalam semua paket box yang tersedia di website. Stok diproduksi harian, jadi disarankan memesan lebih awal untuk kebutuhan acara.',
            ],
        ],
        [
            'category' => 'Outlet',
            'title' => 'Outlet Surabaya Pindah ke Lantai 5 Tunjungan Plaza 3',
            'date' => '2026-11-06',
            'date_label' => '6 November 2026',
            'image' => 'storage/outlets/surabaya.jpg',
            'excerpt' => 'Area duduk lebih luas, meja dine-in bertambah, dan QR code baru sudah terpasang di seluruh meja.',
            'body' => [
                'Mulai 6 November 2026, outlet Ekimochi Surabaya berpindah ke lantai 5 Tunjungan Plaza 3. Lokasi baru ini punya area duduk yang lebih luas dan kapasitas meja yang bertambah.',
                'Seluruh meja di lokasi baru sudah dilengkapi QR code dine-in, jadi pelanggan bisa langsung memesan dari meja tanpa antre di kasir. Nomor meja lama tidak lagi berlaku.',
                'Alamat dan titik peta pada halaman Outlet juga sudah diperbarui, sehingga tombol rute di website langsung mengarah ke lokasi baru.',
            ],
        ],
        [
            'category' => 'Pengumuman',
            'title' => 'Pesan Dine-In Lewat QR Code Kini Tersedia di Semua Meja',
            'date' => '2026-10-28',
            'date_label' => '28 Oktober 2026',
            'image' => 'images/homepage/gambar.png',
            'excerpt' => 'Scan QR code di meja, pilih menu, dan pesanan langsung masuk ke dapur tanpa perlu menunggu pelayan.',
            'body' => [
                'Fitur dine-in lewat QR code sekarang aktif di seluruh outlet Ekimochi. Setiap meja punya QR code unik yang otomatis mendeteksi outlet dan nomor meja Anda.',
                'Cara pakainya sederhana: scan QR code di meja, pilih menu favorit, lalu konfirmasi pesanan. Pesanan akan langsung diteruskan ke dapur dan diantar ke meja Anda.',
                'Pembayaran tetap bisa dilakukan seperti biasa, baik tunai di kasir maupun non-tunai lewat halaman pembayaran. Jika QR code di meja tidak terbaca, silakan minta bantuan staf outlet untuk nomor meja Anda.',
            ],
        ],
        [
            'category' => 'Promo',
            'title' => 'Paket Box of 12 dengan Kemasan Gift Edisi Akhir Tahun',
            'date' => '2026-10-15',
            'date_label' => '15 Oktober 2026',
            'image' => 'storage/products/kiekimochi coklat.png',
            'excerpt' => 'Kemasan edisi akhir tahun tersedia untuk Paket Box of 12 tanpa tambahan biaya, selama persediaan masih ada.',
            'body' => [
                'Menyambut akhir tahun, Paket Box of 12 hadir dengan kemasan gift edisi terbatas. Kemasan ini bisa dipilih langsung saat checkout tanpa biaya tambahan.',
                'Isi paket tetap sama: 12 mochi pilihan yang bisa dikombinasikan sesuai selera. Cocok untuk hadiah, hampers kantor, maupun acara keluarga.',
                'Karena jumlah kemasan terbatas, pemesanan dalam jumlah banyak disarankan dilakukan minimal dua hari sebelum tanggal pengiriman. Paket custom juga masih bisa dipesan lewat halaman Paket Box.',
            ],
        ],
        [
            'category' => 'Produk Baru',
            'title' => 'Matcha Classic: Matcha Uji Asli dengan Isian Lebih Lembut',
            'date' => '2026-10-02',
            'date_label' => '2 Oktober 2026',
            'image' => 'storage/products/kiekimochi matcha.png',
            'excerpt' => 'Resep Matcha Classic diperbarui dengan matcha uji asli dan tekstur isian yang lebih lembut.',
            'body' => [
                'Matcha Classic sekarang memakai matcha uji asli dengan profil rasa yang lebih pekat dan sedikit pahit di akhir, berpadu dengan isian krim yang lebih lembut.',
                'Perubahan resep ini berlaku di semua outlet dan di katalog online. Bagi yang menyukai rasa lebih ringan, Matcha varian manis tetap tersedia.',
                'Harga tidak berubah. Produk bisa dipesan satuan, atau digabung dalam Paket Box of 4, 6, dan 12.',
            ],
        ],
        [
            'category' => 'Pengumuman',
            'title' => 'Jam Operasional Baru untuk Akhir Pekan',
            'date' => '2026-09-20',
            'date_label' => '20 September 2026',
            'image' => 'images/homepage/strawberry 6.png',
            'excerpt' => 'Mulai bulan ini, outlet buka lebih lama di hari Sabtu dan Minggu untuk mengakomodasi pesanan keluarga.',
            'body' => [
                'Mulai bulan ini, seluruh outlet Ekimochi buka lebih lama di akhir pekan, yaitu pukul 10.00 sampai 22.00 WIB. Hari kerja tetap 10.00 sampai 21.00 WIB.',
                'Perpanjangan jam operasional berlaku untuk semua layanan: dine-in, takeaway, dan pengambilan pesanan online.',
                'Pesanan online dengan pengiriman tetap mengikuti jam operasional outlet terdekat, dengan batas jarak pengiriman maksimal 15 km.',
            ],
        ],
    ];

    $categories = ['Semua', 'Produk Baru', 'Promo', 'Outlet', 'Pengumuman'];
    $featured = $posts[0];
    $rest = array_slice($posts, 1);
@endphp

<!-- Hero Section -->
<section class="relative overflow-hidden bg-gradient-to-b from-[#FBE8EE] to-[#FFF9FA] py-16 lg:py-20">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center max-w-3xl mx-auto">
      <span class="inline-block bg-white text-[#D26986] px-4 py-1.5 rounded-full text-xs font-bold shadow-sm mb-6">
        <i class="fa-solid fa-newspaper mr-1"></i>
        Kabar Ekimochi
      </span>
      <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-[#2D2D2D] leading-tight mb-6">
        Update Terbaru <span class="text-[#D26986]">Seputar Ekimochi</span>
      </h1>
      <p class="text-gray-600 text-lg leading-relaxed">
        Info produk baru, promo, pembukaan outlet, dan pemberitahuan penting lainnya. Semua update kami kumpulkan di satu halaman ini.
      </p>

      <!-- Quick Stats -->
      <div class="grid grid-cols-3 gap-6 mt-12 max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-rose-100">
          <div class="w-12 h-12 rounded-full bg-rose-100 text-[#D26986] flex items-center justify-center text-xl font-bold mb-3 mx-auto">
            <i class="fa-solid fa-pen-nib"></i>
          </div>
          <p class="text-2xl font-extrabold text-gray-900">{{ count($posts) }}</p>
          <p class="text-xs text-gray-500 mt-1">Artikel Terbaru</p>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-rose-100">
          <div class="w-12 h-12 rounded-full bg-rose-100 text-[#D26986] flex items-center justify-center text-xl font-bold mb-3 mx-auto">
            <i class="fa-solid fa-tags"></i>
          </div>
          <p class="text-2xl font-extrabold text-gray-900">{{ count($categories) - 1 }}</p>
          <p class="text-xs text-gray-500 mt-1">Kategori Update</p>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-rose-100">
          <div class="w-12 h-12 rounded-full bg-rose-100 text-[#D26986] flex items-center justify-center text-xl font-bold mb-3 mx-auto">
            <i class="fa-solid fa-store"></i>
          </div>
          <p class="text-2xl font-extrabold text-gray-900">{{ \App\Models\Outlet::active()->count() }}</p>
          <p class="text-xs text-gray-500 mt-1">Outlet Aktif</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Featured Post -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6">
  <div class="bg-white rounded-3xl shadow-md border border-rose-100 overflow-hidden group hover:shadow-xl transition" x-data="{ open: false }">
    <div class="grid grid-cols-1 lg:grid-cols-2">
      <div class="relative h-64 lg:h-auto bg-gradient-to-br from-[#FBE8EE] to-rose-100 overflow-hidden">
        <img src="{{ asset($featured['image']) }}" alt="{{ $featured['title'] }}" loading="lazy"
             class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
        <div class="absolute top-4 left-4">
          <span class="bg-white text-[#D26986] px-4 py-1.5 rounded-full text-xs font-bold shadow-md">
            <i class="fa-solid fa-star mr-1"></i>Sorotan
          </span>
        </div>
      </div>
      <div class="p-8 lg:p-12 flex flex-col justify-center space-y-4">
        <div class="flex items-center flex-wrap gap-3 text-xs">
          <span class="bg-rose-50 text-[#D26986] px-3 py-1 rounded-full font-semibold">{{ $featured['category'] }}</span>
          <span class="text-gray-500"><i class="fa-regular fa-calendar mr-1"></i>{{ $featured['date_label'] }}</span>
        </div>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 leading-snug group-hover:text-[#D26986] transition">
          {{ $featured['title'] }}
        </h2>
        <p class="text-gray-600 text-sm leading-relaxed">{{ $featured['excerpt'] }}</p>
        <div class="pt-2">
          <button @click="open = true" class="inline-flex items-center space-x-2 bg-[#D26986] hover:bg-[#BD5773] text-white font-bold px-6 py-3 rounded-full transition text-sm">
            <span>Baca Selengkapnya</span>
            <i class="fa-solid fa-arrow-right"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" role="dialog" aria-modal="true"
         aria-label="{{ $featured['title'] }}" @keydown.escape.window="open = false">
      <div class="absolute inset-0 bg-black/40" @click="open = false"></div>
      <div class="relative bg-white rounded-3xl max-w-2xl w-full max-h-[85vh] overflow-y-auto shadow-2xl">
        <img src="{{ asset($featured['image']) }}" alt="{{ $featured['title'] }}" class="w-full h-56 object-cover">
        <div class="p-6 sm:p-8 space-y-4">
          <div class="flex items-center justify-between gap-3">
            <span class="bg-rose-50 text-[#D26986] px-3 py-1 rounded-full text-xs font-semibold">{{ $featured['category'] }}</span>
            <button @click="open = false" class="text-gray-400 hover:text-[#D26986] transition" aria-label="Tutup">
              <i class="fa-solid fa-xmark text-xl"></i>
            </button>
          </div>
          <h3 class="text-2xl font-extrabold text-gray-900 leading-snug">{{ $featured['title'] }}</h3>
          <p class="text-xs text-gray-500"><i class="fa-regular fa-calendar mr-1"></i>{{ $featured['date_label'] }}</p>
          <div class="space-y-3 text-sm text-gray-600 leading-relaxed">
            @foreach($featured['body'] as $paragraph)
            <p>{{ $paragraph }}</p>
            @endforeach
          </div>
          <a href="{{ route('catalog.index') }}" class="inline-flex items-center space-x-2 bg-[#D26986] hover:bg-[#BD5773] text-white font-bold px-6 py-3 rounded-full transition text-sm">
            <span>Lihat Katalog</span>
            <i class="fa-solid fa-arrow-right"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

@if(!empty($promos) && $promos->isNotEmpty())
<!-- Promo Notification -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16">
  <div class="bg-gradient-to-r from-[#BD5773] to-[#D26986] rounded-3xl shadow-md overflow-hidden">
    <div class="p-6 sm:p-8">
      <div class="flex items-center gap-2 mb-5">
        <span class="inline-flex items-center bg-white/20 text-white px-3 py-1 rounded-full text-[11px] font-bold">
          <i class="fa-solid fa-bolt mr-1"></i> PEMBERITAHUAN DISKON
        </span>
        <span class="text-white/80 text-xs">Promo sedang berlangsung — jangan sampai kehabisan!</span>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($promos as $promo)
        <div class="bg-white/10 border border-white/20 rounded-2xl p-5 backdrop-blur">
          <div class="flex items-start justify-between gap-3">
            <div>
              <h3 class="text-white font-extrabold text-lg">{{ $promo->name }}</h3>
              @if($promo->description)
              <p class="text-white/85 text-sm mt-1 leading-relaxed">{{ $promo->description }}</p>
              @endif
              @if((float) $promo->min_purchase > 0)
              <p class="text-white/70 text-xs mt-2">
                <i class="fa-solid fa-cart-shopping mr-1"></i>Min. belanja Rp {{ number_format($promo->min_purchase, 0, ',', '.') }}
              </p>
              @endif
              @if($promo->ends_at)
              <p class="text-white/70 text-xs mt-1">
                <i class="fa-regular fa-calendar mr-1"></i>Berakhir {{ $promo->ends_at->format('d M Y') }}
              </p>
              @endif
            </div>
            <span class="inline-flex items-center px-3 py-1.5 bg-white text-[#D26986] rounded-full text-sm font-extrabold whitespace-nowrap">
              {{ $promo->discountLabel() }}
            </span>
          </div>
        </div>
        @endforeach
      </div>
      <div class="mt-6">
        <a href="{{ route('catalog.index') }}" class="inline-flex items-center space-x-2 bg-white hover:bg-rose-50 text-[#D26986] font-bold px-6 py-3 rounded-full transition text-sm">
          <span>Manfaatkan Promo di Katalog</span>
          <i class="fa-solid fa-arrow-right"></i>
        </a>
      </div>
    </div>
  </div>
</section>
@endif

<!-- Post List -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16" x-data="{ filter: 'Semua' }">
  <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6 mb-10">
    <div>
      <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900">Semua Update</h2>
      <p class="text-gray-600 text-sm mt-2">Pilih kategori untuk menyaring kabar terbaru.</p>
    </div>

    <!-- Filter -->
    <div class="flex flex-wrap gap-2">
      @foreach($categories as $category)
      <button
        @click="filter = '{{ $category }}'"
        :class="filter === '{{ $category }}' ? 'bg-[#D26986] text-white border-[#D26986] shadow-md' : 'bg-white text-gray-600 border-rose-200 hover:border-[#D26986] hover:text-[#D26986]'"
        class="px-4 py-2 rounded-full text-xs font-semibold border transition">
        {{ $category }}
      </button>
      @endforeach
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    @foreach($rest as $post)
    <article
      x-data="{ open: false }"
      x-show="filter === 'Semua' || filter === '{{ $post['category'] }}'"
      class="bg-white rounded-3xl shadow-md border border-rose-100 overflow-hidden hover:shadow-xl transition group flex flex-col">
      <!-- Thumbnail -->
      <div class="relative h-48 bg-gradient-to-br from-[#FBE8EE] to-rose-100 overflow-hidden">
        <img src="{{ asset($post['image']) }}" alt="{{ $post['title'] }}" loading="lazy"
             class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
        <div class="absolute top-4 left-4">
          <span class="bg-white text-[#D26986] px-3 py-1 rounded-full text-[11px] font-bold shadow-md">
            {{ $post['category'] }}
          </span>
        </div>
      </div>

      <!-- Content -->
      <div class="p-6 flex flex-col flex-1 space-y-3">
        <div class="flex items-center text-xs text-gray-500">
          <i class="fa-regular fa-calendar mr-2 text-[#D26986]"></i>
          <span>{{ $post['date_label'] }}</span>
        </div>
        <h3 class="text-lg font-extrabold text-gray-900 leading-snug group-hover:text-[#D26986] transition">
          {{ $post['title'] }}
        </h3>
        <p class="text-sm text-gray-600 leading-relaxed flex-1">{{ $post['excerpt'] }}</p>

        <button @click="open = true" class="inline-flex items-center space-x-2 text-[#D26986] hover:text-[#BD5773] font-bold text-sm mt-2">
          <span>Baca Selengkapnya</span>
          <i class="fa-solid fa-arrow-right text-xs"></i>
        </button>
      </div>

      <!-- Modal -->
      <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" role="dialog" aria-modal="true"
             aria-label="{{ $post['title'] }}" @keydown.escape.window="open = false">
        <div class="absolute inset-0 bg-black/40" @click="open = false"></div>
        <div class="relative bg-white rounded-3xl max-w-2xl w-full max-h-[85vh] overflow-y-auto shadow-2xl">
          <img src="{{ asset($post['image']) }}" alt="{{ $post['title'] }}" class="w-full h-56 object-cover">
          <div class="p-6 sm:p-8 space-y-4">
            <div class="flex items-center justify-between gap-3">
              <span class="bg-rose-50 text-[#D26986] px-3 py-1 rounded-full text-xs font-semibold">{{ $post['category'] }}</span>
              <button @click="open = false" class="text-gray-400 hover:text-[#D26986] transition" aria-label="Tutup">
                <i class="fa-solid fa-xmark text-xl"></i>
              </button>
            </div>
            <h3 class="text-2xl font-extrabold text-gray-900 leading-snug">{{ $post['title'] }}</h3>
            <p class="text-xs text-gray-500"><i class="fa-regular fa-calendar mr-1"></i>{{ $post['date_label'] }}</p>
            <div class="space-y-3 text-sm text-gray-600 leading-relaxed">
              @foreach($post['body'] as $paragraph)
              <p>{{ $paragraph }}</p>
              @endforeach
            </div>
            <a href="{{ route('catalog.index') }}" class="inline-flex items-center space-x-2 bg-[#D26986] hover:bg-[#BD5773] text-white font-bold px-6 py-3 rounded-full transition text-sm">
              <span>Lihat Katalog</span>
              <i class="fa-solid fa-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>
    </article>
    @endforeach
  </div>

  <p class="text-center text-xs text-gray-500 mt-10">
    <i class="fa-solid fa-circle-info mr-1 text-[#D26986]"></i>
    Itu semua kabar terbaru dari kami. Update berikutnya akan muncul di halaman ini juga.
  </p>
</section>

<!-- CTA -->
<section class="py-16 bg-gradient-to-b from-[#FBE8EE] to-[#FFF9FA] border-t border-rose-100">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
    <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-4">Sudah Baca Kabarnya? Sekarang Coba Rasanya</h2>
    <p class="text-gray-600 mb-8">Pesan langsung dari katalog online, atau datang ke outlet terdekat dan coba dine-in dengan QR code.</p>
    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
      <a href="{{ route('catalog.index') }}" class="w-full sm:w-auto bg-[#D26986] hover:bg-[#BD5773] text-white font-bold px-8 py-4 rounded-full shadow-lg shadow-rose-200 transition transform active:scale-95 text-center">
        Lihat Katalog <i class="fa-solid fa-arrow-right ml-2"></i>
      </a>
      <a href="{{ route('outlets.index') }}" class="w-full sm:w-auto bg-white hover:bg-rose-50 text-[#D26986] border border-rose-200 font-bold px-8 py-4 rounded-full shadow-sm transition text-center">
        Cari Outlet
      </a>
    </div>
  </div>
</section>

<style>
  [x-cloak] { display: none !important; }
</style>
@endsection
