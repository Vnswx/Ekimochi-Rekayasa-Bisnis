@extends('layouts.app')

@section('title', 'Tentang Kami - Ekimochi')

@section('content')
<!-- Hero Section -->
<section class="relative overflow-hidden bg-gradient-to-b from-[#FBE8EE] to-[#FFF9FA] py-16 lg:py-24">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center max-w-3xl mx-auto">
      <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-[#2D2D2D] leading-tight mb-6">
        Cerita di Balik <span class="text-[#D26986]">Ekimochi</span>
      </h1>
      <p class="text-gray-600 text-lg leading-relaxed">
        Perjalanan kami dimulai dari kecintaan pada mochi Jepang yang autentik. Kini, kami hadir untuk menghadirkan sensasi kelembutan dan kenikmatan mochi premium ke setiap sudut Indonesia.
      </p>
    </div>
  </div>
</section>

<!-- Our Story Section -->
<section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
    <!-- Image -->
    <div class="relative">
      <div class="bg-white p-4 rounded-3xl shadow-xl border border-rose-100 transform hover:scale-105 transition duration-300">
        <img src="{{ asset('images/homepage/strawberry.png') }}" alt="Ekimochi Story" class="rounded-2xl w-full h-96 object-cover">
      </div>
      <!-- Floating Badge -->
      <div class="absolute -bottom-6 -right-6 bg-white p-4 rounded-2xl shadow-lg border border-rose-100 flex items-center space-x-3">
        <div class="w-12 h-12 rounded-full bg-rose-100 flex items-center justify-center text-[#D26986] font-bold text-lg">
          <i class="fa-solid fa-heart"></i>
        </div>
        <div>
          <p class="text-xs text-gray-500 font-medium">Sejak</p>
          <p class="text-sm font-bold text-gray-800">2020</p>
        </div>
      </div>
    </div>

    <!-- Content -->
    <div class="space-y-6">
      <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900">Dimulai dari Passion, Berkembang dengan Cinta</h2>
      <div class="space-y-4 text-gray-600 leading-relaxed">
        <p>
          <strong class="text-gray-800">Ekimochi</strong> lahir dari kecintaan kami terhadap kuliner Jepang, khususnya mochi yang lembut dan kenyal. Kami percaya bahwa mochi bukan sekadar dessert, tetapi sebuah pengalaman yang bisa menghadirkan kebahagiaan di setiap gigitan.
        </p>
        <p>
          Berawal dari dapur rumah di tahun 2020, kami mulai bereksperimen dengan berbagai resep tradisional Jepang dan menyesuaikannya dengan selera lokal Indonesia. Setelah ratusan kali percobaan, akhirnya kami menemukan formula sempurna: kulit mochi yang super kenyal namun tidak lengket, dengan isian buah segar dan cokelat premium yang meleleh di mulut.
        </p>
        <p>
          Kini, <strong class="text-gray-800">Ekimochi</strong> telah melayani lebih dari <strong class="text-[#D26986]">50.000+ pelanggan setia</strong> di seluruh Indonesia, dan kami terus berinovasi menghadirkan varian rasa baru yang memukau.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Vision & Mission -->
<section class="py-16 bg-gradient-to-b from-white to-[#FBE8EE]/30 border-y border-rose-100">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center max-w-2xl mx-auto mb-12">
      <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mt-3">Visi & Misi Kami</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <!-- Visi -->
      <div class="bg-white rounded-3xl p-8 shadow-md border border-rose-100 hover:shadow-xl transition">
        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#D26986] to-[#BD5773] text-white flex items-center justify-center text-2xl font-bold mb-6">
          <i class="fa-solid fa-eye"></i>
        </div>
        <h3 class="text-2xl font-extrabold text-gray-900 mb-4">Visi</h3>
        <p class="text-gray-600 leading-relaxed">
          Menjadi brand mochi premium terdepan di Indonesia yang dikenal dengan kualitas produk terbaik, inovasi rasa yang unik, dan pengalaman pelanggan yang tak terlupakan.
        </p>
      </div>

      <!-- Misi -->
      <div class="bg-white rounded-3xl p-8 shadow-md border border-rose-100 hover:shadow-xl transition">
        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#D26986] to-[#BD5773] text-white flex items-center justify-center text-2xl font-bold mb-6">
          <i class="fa-solid fa-bullseye"></i>
        </div>
        <h3 class="text-2xl font-extrabold text-gray-900 mb-4">Misi</h3>
        <ul class="space-y-3 text-gray-600">
          <li class="flex items-start space-x-2">
            <i class="fa-solid fa-check text-[#D26986] mt-1"></i>
            <span>Menghadirkan mochi berkualitas premium dengan bahan pilihan terbaik</span>
          </li>
          <li class="flex items-start space-x-2">
            <i class="fa-solid fa-check text-[#D26986] mt-1"></i>
            <span>Berinovasi menciptakan varian rasa yang unik dan memukau</span>
          </li>
          <li class="flex items-start space-x-2">
            <i class="fa-solid fa-check text-[#D26986] mt-1"></i>
            <span>Memberikan pelayanan terbaik dan pengalaman berbelanja yang menyenangkan</span>
          </li>
          <li class="flex items-start space-x-2">
            <i class="fa-solid fa-check text-[#D26986] mt-1"></i>
            <span>Menjaga standar kebersihan dan keamanan pangan tertinggi</span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</section>

<!-- Our Values -->
<section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
  <div class="text-center max-w-2xl mx-auto mb-12">
    <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mt-3">Nilai-Nilai Kami</h2>
    <p class="text-gray-600 text-sm mt-2">Prinsip yang menjadi fondasi setiap langkah Ekimochi</p>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="bg-white p-6 rounded-3xl border border-rose-100 shadow-sm hover:shadow-md transition text-center">
      <div class="w-14 h-14 rounded-2xl bg-rose-100 text-[#D26986] flex items-center justify-center text-2xl font-bold mb-4 mx-auto">
        <i class="fa-solid fa-award"></i>
      </div>
      <h3 class="font-bold text-gray-900 text-lg mb-2">Kualitas Premium</h3>
      <p class="text-xs text-gray-500 leading-relaxed">Hanya menggunakan bahan terbaik dan proses produksi yang terjaga ketat untuk menghasilkan mochi berkualitas tinggi.</p>
    </div>

    <div class="bg-white p-6 rounded-3xl border border-rose-100 shadow-sm hover:shadow-md transition text-center">
      <div class="w-14 h-14 rounded-2xl bg-rose-100 text-[#D26986] flex items-center justify-center text-2xl font-bold mb-4 mx-auto">
        <i class="fa-solid fa-lightbulb"></i>
      </div>
      <h3 class="font-bold text-gray-900 text-lg mb-2">Inovasi Berkelanjutan</h3>
      <p class="text-xs text-gray-500 leading-relaxed">Terus berinovasi menciptakan varian rasa baru yang unik dan mengikuti tren kuliner terkini.</p>
    </div>

    <div class="bg-white p-6 rounded-3xl border border-rose-100 shadow-sm hover:shadow-md transition text-center">
      <div class="w-14 h-14 rounded-2xl bg-rose-100 text-[#D26986] flex items-center justify-center text-2xl font-bold mb-4 mx-auto">
        <i class="fa-solid fa-heart"></i>
      </div>
      <h3 class="font-bold text-gray-900 text-lg mb-2">Customer First</h3>
      <p class="text-xs text-gray-500 leading-relaxed">Kepuasan pelanggan adalah prioritas utama kami dalam setiap produk dan layanan yang kami berikan.</p>
    </div>

    <div class="bg-white p-6 rounded-3xl border border-rose-100 shadow-sm hover:shadow-md transition text-center">
      <div class="w-14 h-14 rounded-2xl bg-rose-100 text-[#D26986] flex items-center justify-center text-2xl font-bold mb-4 mx-auto">
        <i class="fa-solid fa-leaf"></i>
      </div>
      <h3 class="font-bold text-gray-900 text-lg mb-2">Sustainability</h3>
      <p class="text-xs text-gray-500 leading-relaxed">Berkomitmen pada praktik bisnis yang ramah lingkungan dan berkelanjutan untuk masa depan lebih baik.</p>
    </div>
  </div>
</section>

<!-- Why Choose Us -->
<section class="py-16 bg-white border-y border-rose-100">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center max-w-2xl mx-auto mb-12">
      <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mt-3">Mengapa Memilih Ekimochi?</h2>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
      <div class="bg-[#FFF9FA] p-6 rounded-3xl border border-rose-100 flex flex-col items-center text-center">
        <div class="w-16 h-16 rounded-2xl bg-rose-100 text-[#D26986] flex items-center justify-center text-2xl font-bold mb-4">
          <i class="fa-solid fa-wheat-awn"></i>
        </div>
        <h3 class="font-bold text-gray-900 text-lg mb-2">Bahan Premium Pilihan</h3>
        <p class="text-xs text-gray-500 leading-relaxed">Tepung ketan mochi berkualitas tinggi dipadukan dengan buah segar dan cokelat asli tanpa bahan pengawet berbahaya.</p>
      </div>

      <div class="bg-[#FFF9FA] p-6 rounded-3xl border border-rose-100 flex flex-col items-center text-center">
        <div class="w-16 h-16 rounded-2xl bg-rose-100 text-[#D26986] flex items-center justify-center text-2xl font-bold mb-4">
          <i class="fa-solid fa-certificate"></i>
        </div>
        <h3 class="font-bold text-gray-900 text-lg mb-2">Sertifikasi Halal MUI</h3>
        <p class="text-xs text-gray-500 leading-relaxed">Semua produk kami telah tersertifikasi halal MUI dan diproduksi dengan standar kebersihan tertinggi.</p>
      </div>

      <div class="bg-[#FFF9FA] p-6 rounded-3xl border border-rose-100 flex flex-col items-center text-center">
        <div class="w-16 h-16 rounded-2xl bg-rose-100 text-[#D26986] flex items-center justify-center text-2xl font-bold mb-4">
          <i class="fa-solid fa-snowflake"></i>
        </div>
        <h3 class="font-bold text-gray-900 text-lg mb-2">Fresh Chilled Daily</h3>
        <p class="text-xs text-gray-500 leading-relaxed">Dibuat fresh setiap hari dan disimpan dengan suhu optimal untuk menjaga tekstur kenyal yang sempurna.</p>
      </div>

      <div class="bg-[#FFF9FA] p-6 rounded-3xl border border-rose-100 flex flex-col items-center text-center">
        <div class="w-16 h-16 rounded-2xl bg-rose-100 text-[#D26986] flex items-center justify-center text-2xl font-bold mb-4">
          <i class="fa-solid fa-users"></i>
        </div>
        <h3 class="font-bold text-gray-900 text-lg mb-2">50.000+ Pelanggan Setia</h3>
        <p class="text-xs text-gray-500 leading-relaxed">Dipercaya oleh puluhan ribu pelanggan di seluruh Indonesia dengan rating 4.9/5.0 di berbagai platform.</p>
      </div>

      <div class="bg-[#FFF9FA] p-6 rounded-3xl border border-rose-100 flex flex-col items-center text-center">
        <div class="w-16 h-16 rounded-2xl bg-rose-100 text-[#D26986] flex items-center justify-center text-2xl font-bold mb-4">
          <i class="fa-solid fa-truck-fast"></i>
        </div>
        <h3 class="font-bold text-gray-900 text-lg mb-2">Pengiriman Aman & Cepat</h3>
        <p class="text-xs text-gray-500 leading-relaxed">Dikemas dengan ice gel khusus dan box premium agar mochi tetap dingin dan tidak leleh saat dikirim.</p>
      </div>

      <div class="bg-[#FFF9FA] p-6 rounded-3xl border border-rose-100 flex flex-col items-center text-center">
        <div class="w-16 h-16 rounded-2xl bg-rose-100 text-[#D26986] flex items-center justify-center text-2xl font-bold mb-4">
          <i class="fa-solid fa-gift"></i>
        </div>
        <h3 class="font-bold text-gray-900 text-lg mb-2">Perfect Gift Choice</h3>
        <p class="text-xs text-gray-500 leading-relaxed">Packaging mewah dan eksklusif cocok untuk hadiah ulang tahun, acara spesial, atau sekadar berbagi kebahagiaan.</p>
      </div>
    </div>
  </div>
</section>

<!-- Our Journey Timeline -->
<section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
  <div class="text-center max-w-2xl mx-auto mb-12">
    <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mt-3">Perjalanan Kami</h2>
    <p class="text-gray-600 text-sm mt-2">Milestone penting dalam sejarah Ekimochi</p>
  </div>

  <div class="relative">
    <!-- Timeline Line -->
    <div class="absolute left-1/2 transform -translate-x-1/2 h-full w-1 bg-rose-200 hidden lg:block"></div>

    <!-- Timeline Items -->
    <div class="space-y-12">
      <!-- 2020 -->
      <div class="relative grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
        <div class="lg:text-right">
          <div class="bg-white p-6 rounded-3xl border border-rose-100 shadow-sm">
            <span class="inline-block bg-[#D26986] text-white text-xs font-bold px-3 py-1 rounded-full mb-3">2020</span>
            <h3 class="font-bold text-gray-900 text-xl mb-2">Awal Perjalanan</h3>
            <p class="text-sm text-gray-600">Ekimochi lahir dari dapur rumah dengan eksperimen resep tradisional Jepang yang disesuaikan dengan selera lokal Indonesia.</p>
          </div>
        </div>
        <div class="hidden lg:block">
          <div class="w-4 h-4 rounded-full bg-[#D26986] border-4 border-white shadow-lg mx-auto"></div>
        </div>
      </div>

      <!-- 2021 -->
      <div class="relative grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
        <div class="lg:col-start-2">
          <div class="bg-white p-6 rounded-3xl border border-rose-100 shadow-sm">
            <span class="inline-block bg-[#D26986] text-white text-xs font-bold px-3 py-1 rounded-full mb-3">2021</span>
            <h3 class="font-bold text-gray-900 text-xl mb-2">Outlet Pertama</h3>
            <p class="text-sm text-gray-600">Membuka outlet pertama di Bandung dan langsung mendapat sambutan luar biasa dari masyarakat dengan antrian panjang setiap hari.</p>
          </div>
        </div>
        <div class="hidden lg:block">
          <div class="w-4 h-4 rounded-full bg-[#D26986] border-4 border-white shadow-lg mx-auto"></div>
        </div>
      </div>

      <!-- 2023 -->
      <div class="relative grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
        <div class="lg:text-right">
          <div class="bg-white p-6 rounded-3xl border border-rose-100 shadow-sm">
            <span class="inline-block bg-[#D26986] text-white text-xs font-bold px-3 py-1 rounded-full mb-3">2023</span>
            <h3 class="font-bold text-gray-900 text-xl mb-2">Ekspansi Nasional</h3>
            <p class="text-sm text-gray-600">Membuka 5 outlet baru di Jakarta, Surabaya, dan Bali. Melayani ribuan pelanggan setia setiap bulan.</p>
          </div>
        </div>
        <div class="hidden lg:block">
          <div class="w-4 h-4 rounded-full bg-[#D26986] border-4 border-white shadow-lg mx-auto"></div>
        </div>
      </div>

      <!-- 2024 -->
      <div class="relative grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
        <div class="lg:col-start-2">
          <div class="bg-white p-6 rounded-3xl border border-rose-100 shadow-sm">
            <span class="inline-block bg-[#D26986] text-white text-xs font-bold px-3 py-1 rounded-full mb-3">2024</span>
            <h3 class="font-bold text-gray-900 text-xl mb-2">Sertifikasi Halal</h3>
            <p class="text-sm text-gray-600">Mendapatkan sertifikasi halal MUI dan penghargaan Best Dessert Brand dari Indonesian Food Awards 2024.</p>
          </div>
        </div>
        <div class="hidden lg:block">
          <div class="w-4 h-4 rounded-full bg-[#D26986] border-4 border-white shadow-lg mx-auto"></div>
        </div>
      </div>

      <!-- 2026 -->
      <div class="relative grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
        <div class="lg:text-right">
          <div class="bg-gradient-to-br from-[#D26986] to-[#BD5773] p-6 rounded-3xl border border-rose-100 shadow-xl text-white">
            <span class="inline-block bg-white text-[#D26986] text-xs font-bold px-3 py-1 rounded-full mb-3">2026 - Sekarang</span>
            <h3 class="font-bold text-xl mb-2">50.000+ Pelanggan</h3>
            <p class="text-sm">Terus berinovasi dengan varian rasa baru dan meluncurkan platform e-commerce untuk kemudahan pemesanan online.</p>
          </div>
        </div>
        <div class="hidden lg:block">
          <div class="w-4 h-4 rounded-full bg-[#D26986] border-4 border-white shadow-lg mx-auto ring-4 ring-rose-200"></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-b from-[#FBE8EE] to-[#FFF9FA]">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
    <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-4">Siap Merasakan Kelezatan Ekimochi?</h2>
    <p class="text-gray-600 mb-8">Jelajahi berbagai varian rasa istimewa kami dan temukan favorit Anda</p>
    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
      <a href="{{ route('catalog.index') }}" class="w-full sm:w-auto bg-[#D26986] hover:bg-[#BD5773] text-white font-bold px-8 py-4 rounded-full shadow-lg shadow-rose-200 transition transform active:scale-95 text-center">
        Lihat Semua Produk <i class="fa-solid fa-arrow-right ml-2"></i>
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
