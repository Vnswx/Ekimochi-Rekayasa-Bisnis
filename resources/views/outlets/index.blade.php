@extends('layouts.app')

@section('title', 'Lokasi Outlet - Ekimochi')

@section('content')
<!-- Hero Section -->
<section class="relative overflow-hidden bg-gradient-to-b from-[#FBE8EE] to-[#FFF9FA] py-16 lg:py-24">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center max-w-3xl mx-auto">
      <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-[#2D2D2D] leading-tight mb-6">
        Kunjungi <span class="text-[#D26986]">Outlet Kami</span>
      </h1>
      <p class="text-gray-600 text-lg leading-relaxed">
        Temukan outlet Ekimochi terdekat dari lokasi Anda dan nikmati kelezatan mochi premium langsung di tempat atau takeaway.
      </p>
      
      <!-- Quick Stats -->
      <div class="grid grid-cols-3 gap-6 mt-12 max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-rose-100">
          <div class="w-12 h-12 rounded-full bg-rose-100 text-[#D26986] flex items-center justify-center text-xl font-bold mb-3 mx-auto">
            <i class="fa-solid fa-store"></i>
          </div>
          <p class="text-2xl font-extrabold text-gray-900">{{ $outlets->count() }}</p>
          <p class="text-xs text-gray-500 mt-1">Outlet Aktif</p>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-rose-100">
          <div class="w-12 h-12 rounded-full bg-rose-100 text-[#D26986] flex items-center justify-center text-xl font-bold mb-3 mx-auto">
            <i class="fa-solid fa-clock"></i>
          </div>
          <p class="text-2xl font-extrabold text-gray-900">10-21</p>
          <p class="text-xs text-gray-500 mt-1">Jam Buka (WIB)</p>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-rose-100">
          <div class="w-12 h-12 rounded-full bg-rose-100 text-[#D26986] flex items-center justify-center text-xl font-bold mb-3 mx-auto">
            <i class="fa-solid fa-qrcode"></i>
          </div>
          <p class="text-2xl font-extrabold text-gray-900">Dine-In</p>
          <p class="text-xs text-gray-500 mt-1">Scan QR Ready</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Outlets List -->
<section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
  <div class="text-center max-w-2xl mx-auto mb-12">
    <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mt-3">Pilih Outlet Terdekat</h2>
    <p class="text-gray-600 text-sm mt-2">Semua outlet kami siap melayani Anda dengan produk berkualitas premium</p>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    @foreach($outlets as $outlet)
    <div class="bg-white rounded-3xl shadow-md border border-rose-100 overflow-hidden hover:shadow-xl transition group">
      <!-- Map Thumbnail -->
      <div class="relative h-48 bg-gradient-to-br from-[#FBE8EE] to-rose-100 overflow-hidden">
       
        
        <!-- City Badge -->
        <div class="absolute top-4 left-4">
          <span class="bg-white text-[#D26986] px-4 py-1.5 rounded-full text-xs font-bold shadow-md">
            <i class="fa-solid fa-location-dot mr-1"></i>
            {{ $outlet->city }}
          </span>
        </div>
        
        <!-- Status Badge -->
        @if($outlet->is_active)
        <div class="absolute top-4 right-4">
          <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow-md flex items-center">
            <span class="w-2 h-2 bg-white rounded-full mr-2 animate-pulse"></span>
            Buka Sekarang
          </span>
        </div>
        @endif
      </div>

      <!-- Content -->
      <div class="p-6 space-y-4">
        <div>
          <h3 class="text-xl font-extrabold text-gray-900 mb-2 group-hover:text-[#D26986] transition">
            {{ $outlet->name }}
          </h3>
          
          <!-- Address -->
          <div class="flex items-start space-x-3 text-sm text-gray-600 mb-2">
            <i class="fa-solid fa-map-marker-alt text-[#D26986] mt-1 flex-shrink-0"></i>
            <p class="leading-relaxed">{{ $outlet->address }}, {{ $outlet->city }}</p>
          </div>
          
          <!-- Phone -->
          <div class="flex items-center space-x-3 text-sm text-gray-600 mb-2">
            <i class="fa-solid fa-phone text-[#D26986]"></i>
            <a href="tel:{{ $outlet->phone }}" class="hover:text-[#D26986] transition">{{ $outlet->phone }}</a>
          </div>
          
          <!-- Operating Hours -->
          <div class="flex items-center space-x-3 text-sm text-gray-600">
            <i class="fa-solid fa-clock text-[#D26986]"></i>
            <p>Setiap Hari: <span class="font-semibold text-gray-800">10.00 - 21.00 WIB</span></p>
          </div>
        </div>

        <!-- Features -->
        <div class="flex flex-wrap gap-2 pt-4 border-t border-rose-100">
          <span class="bg-rose-50 text-[#D26986] px-3 py-1 rounded-full text-xs font-semibold">
            <i class="fa-solid fa-utensils mr-1"></i>Dine In
          </span>
          <span class="bg-rose-50 text-[#D26986] px-3 py-1 rounded-full text-xs font-semibold">
            <i class="fa-solid fa-bag-shopping mr-1"></i>Takeaway
          </span>
          <span class="bg-rose-50 text-[#D26986] px-3 py-1 rounded-full text-xs font-semibold">
            <i class="fa-solid fa-qrcode mr-1"></i>QR Code
          </span>
        </div>

        <!-- Actions -->
        <div class="grid grid-cols-2 gap-3 pt-4">
          @if($outlet->latitude && $outlet->longitude)
          <a href="https://www.google.com/maps/dir/?api=1&destination={{ $outlet->latitude }},{{ $outlet->longitude }}" 
             target="_blank"
             class="flex items-center justify-center space-x-2 bg-white border-2 border-[#D26986] text-[#D26986] hover:bg-rose-50 font-bold py-3 rounded-full transition text-sm">
            <i class="fa-solid fa-directions"></i>
            <span>Rute</span>
          </a>
          @else
          <button disabled class="flex items-center justify-center space-x-2 bg-gray-100 text-gray-400 font-bold py-3 rounded-full text-sm cursor-not-allowed">
            <i class="fa-solid fa-directions"></i>
            <span>Rute</span>
          </button>
          @endif
          
          <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $outlet->phone) }}?text=Halo%20Ekimochi,%20saya%20ingin%20bertanya%20tentang%20outlet%20{{ urlencode($outlet->city) }}" 
             target="_blank"
             class="flex items-center justify-center space-x-2 bg-[#D26986] hover:bg-[#BD5773] text-white font-bold py-3 rounded-full transition text-sm">
            <i class="fa-brands fa-whatsapp"></i>
            <span>Chat</span>
          </a>
        </div>
      </div>
    </div>
    @endforeach
  </div>
</section>

<!-- Dine-In Feature -->
<section class="py-16 bg-gradient-to-b from-white to-[#FBE8EE]/30 border-y border-rose-100">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
      <!-- Content -->
      <div class="space-y-6">
        <div>
          <span class="inline-block bg-[#D26986] text-white px-4 py-1.5 rounded-full text-xs font-bold mb-4">
            <i class="fa-solid fa-star mr-1"></i>
            FITUR TERBARU
          </span>
          <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-4">
            Pesan Lebih Mudah dengan <span class="text-[#D26986]">QR Code</span>
          </h2>
          <p class="text-gray-600 leading-relaxed">
            Cukup scan QR code di meja Anda, pilih menu favorit, dan pesanan langsung masuk ke dapur. Tidak perlu antre atau menunggu pelayan!
          </p>
        </div>

        <!-- Steps -->
        <div class="space-y-4">
          <div class="flex items-start space-x-4">
            <div class="w-10 h-10 rounded-full bg-rose-100 text-[#D26986] flex items-center justify-center font-bold text-lg flex-shrink-0">
              1
            </div>
            <div>
              <h3 class="font-bold text-gray-900 mb-1">Scan QR Code di Meja</h3>
              <p class="text-sm text-gray-600">Gunakan kamera smartphone Anda untuk scan QR code yang ada di setiap meja</p>
            </div>
          </div>

          <div class="flex items-start space-x-4">
            <div class="w-10 h-10 rounded-full bg-rose-100 text-[#D26986] flex items-center justify-center font-bold text-lg flex-shrink-0">
              2
            </div>
            <div>
              <h3 class="font-bold text-gray-900 mb-1">Pilih Menu Favorit</h3>
              <p class="text-sm text-gray-600">Browse katalog lengkap kami dan pilih mochi kesukaan Anda</p>
            </div>
          </div>

          <div class="flex items-start space-x-4">
            <div class="w-10 h-10 rounded-full bg-rose-100 text-[#D26986] flex items-center justify-center font-bold text-lg flex-shrink-0">
              3
            </div>
            <div>
              <h3 class="font-bold text-gray-900 mb-1">Konfirmasi & Bayar</h3>
              <p class="text-sm text-gray-600">Pesanan langsung diproses dan pesanan Anda akan segera diantar ke meja</p>
            </div>
          </div>
        </div>

        <div class="pt-4">
          <a href="{{ route('catalog.index') }}" class="inline-flex items-center space-x-2 bg-[#D26986] hover:bg-[#BD5773] text-white font-bold px-8 py-4 rounded-full shadow-lg shadow-rose-200 transition transform active:scale-95">
            <span>Lihat Menu</span>
            <i class="fa-solid fa-arrow-right"></i>
          </a>
        </div>
      </div>

      <!-- Image/Illustration -->
      <div class="relative">
        <div class="bg-white p-6 rounded-3xl shadow-xl border border-rose-100">
          <div class="aspect-square bg-gradient-to-br from-[#FBE8EE] to-rose-100 rounded-2xl flex items-center justify-center">
            <div class="text-center space-y-4">
              <div class="w-48 h-48 bg-white rounded-2xl shadow-lg mx-auto flex items-center justify-center">
                <i class="fa-solid fa-qrcode text-8xl text-[#D26986]"></i>
              </div>
              <p class="text-sm font-bold text-gray-700">Scan untuk Pesan</p>
            </div>
          </div>
        </div>
        
        <!-- Floating Elements -->
        <div class="absolute -top-4 -right-4 bg-white p-4 rounded-2xl shadow-lg border border-rose-100 animate-bounce">
          <div class="flex items-center space-x-2">
            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
              <i class="fa-solid fa-check text-green-600"></i>
            </div>
            <div>
              <p class="text-xs text-gray-500">Pesanan Diterima</p>
              <p class="text-sm font-bold text-gray-800">Meja 05</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- FAQ Section -->
<section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
  <div class="text-center max-w-2xl mx-auto mb-12">
    <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mt-3">Pertanyaan Seputar Outlet</h2>
  </div>

  <div class="max-w-3xl mx-auto space-y-4">
    <div class="bg-white rounded-2xl border border-rose-100 p-6">
      <h3 class="font-bold text-gray-900 mb-2 flex items-center">
        <i class="fa-solid fa-circle-question text-[#D26986] mr-3"></i>
        Apakah semua outlet menerima pembayaran non-tunai?
      </h3>
      <p class="text-sm text-gray-600 ml-9">
        Ya, semua outlet Ekimochi menerima pembayaran tunai, debit, kartu kredit, dan e-wallet (GoPay, OVO, DANA, ShopeePay).
      </p>
    </div>

    <div class="bg-white rounded-2xl border border-rose-100 p-6">
      <h3 class="font-bold text-gray-900 mb-2 flex items-center">
        <i class="fa-solid fa-circle-question text-[#D26986] mr-3"></i>
        Bisakah reservasi meja sebelum datang?
      </h3>
      <p class="text-sm text-gray-600 ml-9">
        Untuk saat ini kami belum menerima reservasi. Sistem kami adalah first come first served. Namun di jam-jam ramai, kami sarankan datang lebih awal.
      </p>
    </div>

    <div class="bg-white rounded-2xl border border-rose-100 p-6">
      <h3 class="font-bold text-gray-900 mb-2 flex items-center">
        <i class="fa-solid fa-circle-question text-[#D26986] mr-3"></i>
        Apakah tersedia paket box di outlet?
      </h3>
      <p class="text-sm text-gray-600 ml-9">
        Ya, semua paket box yang tersedia di website juga tersedia di outlet. Anda bisa langsung beli atau custom sesuai keinginan Anda.
      </p>
    </div>

    <div class="bg-white rounded-2xl border border-rose-100 p-6">
      <h3 class="font-bold text-gray-900 mb-2 flex items-center">
        <i class="fa-solid fa-circle-question text-[#D26986] mr-3"></i>
        Bagaimana jika produk yang saya inginkan habis?
      </h3>
      <p class="text-sm text-gray-600 ml-9">
        Kami membuat produk fresh setiap hari dengan stok terbatas. Jika produk favorit Anda habis, tim kami akan menawarkan alternatif rasa lain atau Anda bisa pre-order untuk keesokan harinya.
      </p>
    </div>
  </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-b from-[#FBE8EE] to-[#FFF9FA]">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
    <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-4">Belum Bisa Datang ke Outlet?</h2>
    <p class="text-gray-600 mb-8">Pesan online sekarang dan kami kirim langsung ke rumah Anda dengan packaging aman!</p>
    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
      <a href="{{ route('catalog.index') }}" class="w-full sm:w-auto bg-[#D26986] hover:bg-[#BD5773] text-white font-bold px-8 py-4 rounded-full shadow-lg shadow-rose-200 transition transform active:scale-95 text-center">
        Pesan Online <i class="fa-solid fa-arrow-right ml-2"></i>
      </a>
      <a href="{{ route('packages.index') }}" class="w-full sm:w-auto bg-white hover:bg-rose-50 text-[#D26986] border border-rose-200 font-bold px-8 py-4 rounded-full shadow-sm transition text-center">
        Lihat Paket Box
      </a>
    </div>
  </div>
</section>

<script>
  function toggleMobileMenu() {
    const menu = document.getElementById('mobile-menu');
    menu.classList.toggle('hidden');
  }

  function openSearchModal() {
    // Implement search modal
  }
</script>
@endsection
