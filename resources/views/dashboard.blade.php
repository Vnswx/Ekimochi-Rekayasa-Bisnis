<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ekimochi - Sensasi Mochi Lembut, Kenyal, & Meleleh</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: {
              pink: '#FBE8EE',
              darkpink: '#D26986',
              hoverpink: '#BD5773',
              accent: '#F48FB1',
              light: '#FFF5F8',
              textdark: '#2D2D2D'
            }
          },
          fontFamily: {
            sans: ['"Plus Jakarta Sans"', 'sans-serif'],
          }
        }
      }
    }
  </script>
  <style>
    /* Custom scrollbar & smoothness */
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background-color: #FFF9FA;
      color: #2D2D2D;
    }

    ::-webkit-scrollbar {
      width: 8px;
    }

    ::-webkit-scrollbar-track {
      background: #FBE8EE;
    }

    ::-webkit-scrollbar-thumb {
      background: #D26986;
      border-radius: 4px;
    }
  </style>
</head>

<body class="bg-[#FFF9FA] text-[#2D2D2D] antialiased">

  <!-- Navbar -->
  <header class="sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-rose-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
      <!-- Logo -->
      <div class="flex items-center space-x-8">
        <a href="#" class="flex items-center space-x-2">
          <img src="{{ asset('images/homepage/logo perusahaan.png') }}" alt="Logo Ekimochi" style="width: 200px;">
        </a>
        <!-- Navbar Utama Tanpa Dropdown -->
        <nav class="bg-white/90 backdrop-blur-md sticky top-0 z-50 border-b border-rose-100 shadow-sm">
          <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
              <!-- Navlist Navigasi -->
              <div class="md:flex items-center space-x-1 text-sm font-medium text-gray-600">
                <a href="/" class="nav-link px-3 py-2 rounded-lg hover:text-white hover:bg-[#D26986] transition">Beranda</a>
                <a href="{{ route('catalog.index') }}" class="nav-link px-3 py-2 rounded-lg hover:text-white hover:bg-[#D26986] transition">Katalog</a>
                <a href="#paket" class="nav-link px-3 py-2 rounded-lg hover:text-white hover:bg-[#D26986] transition">Paket Box</a>
                <a href="#tentang" class="nav-link px-3 py-2 rounded-lg hover:text-white hover:bg-[#D26986] transition">Tentang Kami</a>
                <a href="#outlet" class="nav-link px-3 py-2 rounded-lg hover:text-white hover:bg-[#D26986] transition">Outlet</a>
                <a href="#faq" class="nav-link px-3 py-2 rounded-lg hover:text-white hover:bg-[#D26986] transition">FAQ</a>
              </div>
            </div>
          </div>
        </nav>
      </div>

      <!-- Right Actions -->
      <div class="flex items-center space-x-4">
        <!-- Search Button -->
        <button onclick="openSearchModal()" class="p-2 text-gray-600 hover:text-[#D26986] transition rounded-full hover:bg-rose-50">
          <i class="fa-solid fa-magnifying-glass text-lg"></i>
        </button>
        <!-- Cart Button with Badge -->
        <button onclick="toggleCartDrawer()" class="relative p-2 text-gray-600 hover:text-[#D26986] transition rounded-full hover:bg-rose-50">
          <i class="fa-solid fa-cart-shopping text-lg"></i>
          <span id="cart-badge" class="absolute top-0 right-0 bg-[#D26986] text-white text-xs w-5 h-5 flex items-center justify-center rounded-full font-bold">0</span>
        </button>
        
        @auth
        <!-- Profile Dropdown (Authenticated) -->
        <div class="relative" x-data="{ open: false }">
          <button @click="open = !open" class="flex items-center space-x-2 bg-[#D26986] hover:bg-[#BD5773] text-white px-4 py-2.5 rounded-full font-semibold text-sm shadow-md transition">
            <div class="w-7 h-7 rounded-full bg-white/20 flex items-center justify-center text-xs font-bold">
              {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <span class="hidden sm:inline">{{ Str::limit(Auth::user()->name, 15) }}</span>
            <i class="fa-solid fa-chevron-down text-xs"></i>
          </button>
          
          <!-- Dropdown Menu -->
          <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-rose-100 py-2 z-50">
            <div class="px-4 py-3 border-b border-rose-100">
              <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
              <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
              @if(Auth::user()->isAdmin())
              <span class="inline-block mt-1 px-2 py-0.5 bg-[#D26986] text-white text-[10px] font-bold rounded-full">ADMIN</span>
              @endif
            </div>
            <a href="{{ route('profile.show') }}" class="flex items-center space-x-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-rose-50 transition">
              <i class="fa-solid fa-user w-5"></i>
              <span>Profile Saya</span>
            </a>
            <a href="{{ route('profile.edit') }}" class="flex items-center space-x-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-rose-50 transition">
              <i class="fa-solid fa-pen-to-square w-5"></i>
              <span>Edit Profile</span>
            </a>
            @if(Auth::user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-rose-50 transition">
              <i class="fa-solid fa-gauge w-5"></i>
              <span>Admin Dashboard</span>
            </a>
            @endif
            <div class="border-t border-rose-100 mt-2 pt-2">
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center space-x-2 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition w-full text-left">
                  <i class="fa-solid fa-right-from-bracket w-5"></i>
                  <span>Logout</span>
                </button>
              </form>
            </div>
          </div>
        </div>
        @else
        <!-- Login Button (Guest) -->
        <a href="{{ route('login') }}" class="hidden sm:flex items-center space-x-2 bg-[#D26986] hover:bg-[#BD5773] text-white px-5 py-2.5 rounded-full font-semibold text-sm shadow-md transition transform active:scale-95">
          <i class="fa-solid fa-right-to-bracket"></i>
          <span>Login</span>
        </a>
        @endauth
        <!-- Mobile Menu Toggle -->
        <button onclick="toggleMobileMenu()" class="md:hidden p-2 text-gray-600 hover:text-[#D26986]">
          <i class="fa-solid fa-bars text-xl"></i>
        </button>
      </div>
    </div>

    <!-- Mobile Menu Dropdown -->
    <div id="mobile-menu" class="hidden md:hidden bg-white border-b border-rose-100 px-6 py-4 space-y-3 shadow-lg">
      <a href="#beranda" class="block text-gray-700 font-medium hover:text-[#D26986]">Beranda</a>
      <a href="katalog.html" class="block text-gray-700 font-medium hover:text-[#D26986]">Katalog</a>
      <a href="#varian" class="block text-gray-700 font-medium hover:text-[#D26986]">Varian Rasa</a>
      <a href="#paket" class="block text-gray-700 font-medium hover:text-[#D26986]">Paket Box</a>
      <a href="#tentang" class="block text-gray-700 font-medium hover:text-[#D26986]">Tentang Kami</a>
      <a href="#outlet" class="block text-gray-700 font-medium hover:text-[#D26986]">Outlet</a>
      <a href="#faq" class="block text-gray-700 font-medium hover:text-[#D26986]">FAQ</a>
      <button onclick="openLoginModal()" class="w-full bg-[#D26986] text-white py-2 rounded-full font-semibold mt-2">Login / Register</button>
    </div>
  </header>

  <!-- Hero Section -->
  <section id="beranda" class="relative overflow-hidden bg-gradient-to-b from-[#FBE8EE] to-[#FFF9FA] py-12 lg:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
        <!-- Hero Text -->
        <div class="lg:col-span-6 space-y-6 text-center lg:text-left">
          <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-[#2D2D2D] leading-tight">
            Sensasi Mochi Lembut, Kenyal, & <span class="text-[#D26986]">Meleleh</span> di Setiap Gigitan
          </h1>
          <p class="text-gray-600 text-base sm:text-lg max-w-xl mx-auto lg:mx-0">
            Dibuat dari setiap bahan pilihan, super kenyal di luar dan lumer di mulut. Pilihan rasa premium: Lotus Biscoff.
          </p>
          <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-4">
            <a href="#varian" class="w-full sm:w-auto bg-[#D26986] hover:bg-[#BD5773] text-white font-bold px-8 py-4 rounded-full shadow-lg shadow-rose-200 transition transform active:scale-95 text-center">
              Lihat Varian Rasa <i class="fa-solid fa-arrow-right ml-2"></i>
            </a>
            <a href="#paket" class="w-full sm:w-auto bg-white hover:bg-rose-50 text-[#D26986] border border-rose-200 font-bold px-8 py-4 rounded-full shadow-sm transition text-center">
              Paket Box Pilihan
            </a>
          </div>
          <!-- Stats Trust -->
          <div class="grid grid-cols-3 gap-4 pt-6 border-t border-rose-200/60 max-w-md mx-auto lg:mx-0 text-left">
            <div class="flex items-center space-x-2">
              <div class="w-8 h-8 rounded-full bg-rose-100 flex items-center justify-center text-[#D26986] font-bold"><i class="fa-solid fa-check"></i></div>
              <div>
                <p class="text-xs font-bold">100% Halal</p>
                <p class="text-[10px] text-gray-500">Bahan Premium</p>
              </div>
            </div>
            <div class="flex items-center space-x-2">
              <div class="w-8 h-8 rounded-full bg-rose-100 flex items-center justify-center text-[#D26986] font-bold"><i class="fa-solid fa-snowflake"></i></div>
              <div>
                <p class="text-xs font-bold">Fresh Chilled</p>
                <p class="text-[10px] text-gray-500">Selalu Segar Harian</p>
              </div>
            </div>
            <div class="flex items-center space-x-2">
              <div class="w-8 h-8 rounded-full bg-rose-100 flex items-center justify-center text-[#D26986] font-bold"><i class="fa-solid fa-star"></i></div>
              <div>
                <p class="text-xs font-bold">4.9/5.0</p>
                <p class="text-[10px] text-gray-500">50rb+ Ulasan</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Hero Image Cards -->
        <div class="lg:col-span-6 relative">
          <div class="relative mx-auto max-w-md lg:max-w-none">
            <!-- Main Card Illustration -->
            <div class="bg-white p-4 rounded-3xl shadow-xl border border-rose-100 transform rotate-1 hover:rotate-0 transition duration-300">
              <img src="{{ asset('images/homepage/strawberry.png') }}" alt="Fresh Mochi" class="rounded-2xl w-full h-72 sm:h-80 object-cover">
              <div class="mt-4 flex items-center justify-between">
                <div>
                  <h3 class="font-bold text-gray-800">Special Daifuku Mochi</h3>
                  <p class="text-xs text-gray-500">Fresh strawberry inside</p>
                </div>
                <span class="bg-rose-50 text-[#D26986] font-extrabold px-3 py-1 rounded-full text-sm">Rp 5.000</span>
              </div>
            </div>
            <!-- Floating Badge -->
            <div class="absolute -bottom-6 -left-6 bg-white p-4 rounded-2xl shadow-lg border border-rose-100 flex items-center space-x-3 animate-bounce duration-1000">
              <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 font-bold text-lg">
                <i class="fa-solid fa-award"></i>
              </div>
              <div>
                <p class="text-xs text-gray-500 font-medium">Terlaris Bulan Ini</p>
                <p class="text-sm font-bold text-gray-800">Strawberry Classic</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="varian" class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center max-w-2xl mx-auto mb-12">
      <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mt-3">Nikmati Sensasi Kelezatan dari Berbagai Varian Mochi Pilihan Kami</h2>
      <div class="flex flex-wrap justify-center gap-2 mt-6">
        <button onclick="filterCatalog('all')" class="catalog-btn px-5 py-2 rounded-full text-sm font-semibold bg-[#D26986] text-white shadow-sm transition">Semua Varian</button>
        @foreach($filterCategories as $category)
        <button onclick="filterCatalog('{{ strtolower(str_replace(' ', '-', $category->name)) }}')" class="catalog-btn px-5 py-2 rounded-full text-sm font-semibold bg-white text-gray-600 hover:bg-rose-50 border border-rose-100 transition">{{ $category->name }}</button>
        @endforeach
      </div>
    </div>

    <div id="product-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
      @php
        $displayedProducts = $products->whereNotIn('category.name', ['Paket Box'])->take(6);
      @endphp
      @foreach($products->whereNotIn('category.name', ['Paket Box']) as $index => $product)
      <div class="product-card bg-white rounded-3xl p-5 shadow-sm hover:shadow-xl transition border border-rose-100 flex flex-col justify-between" 
           data-category="{{ strtolower(str_replace(' ', '-', $product->category->name)) }}"
           style="display: {{ $index < 6 ? 'flex' : 'none' }};">
        <div>
          <div class="relative overflow-hidden rounded-2xl mb-4">
            <img src="{{ asset('images/homepage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-56 object-cover transform hover:scale-105 transition duration-500">
            @if($loop->first)
            <span class="absolute top-3 left-3 bg-[#D26986] text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">Terlaris</span>
            @endif
          </div>
          <div class="flex items-center space-x-2 mb-2">
            <span class="bg-rose-50 text-[#D26986] text-xs font-semibold px-2.5 py-0.5 rounded">{{ $product->category->name }}</span>
            <span class="bg-amber-50 text-amber-700 text-xs font-semibold px-2.5 py-0.5 rounded"><i class="fa-solid fa-star text-amber-400 mr-1"></i>4.9</span>
          </div>
          <h3 class="font-bold text-lg text-gray-800">{{ $product->name }}</h3>
          <p class="text-gray-500 text-xs mt-1 leading-relaxed">{{ Str::limit($product->description, 80) }}</p>
        </div>
        <div class="flex items-center justify-between mt-6 pt-4 border-t border-rose-50">
          <div>
            <span class="text-[10px] text-gray-400 block uppercase font-bold">Harga Satuan</span>
            <span class="text-lg font-extrabold text-[#D26986]">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
          </div>
          <button onclick="addToCart('{{ $product->name }}', {{ $product->price }}, '{{ asset('images/homepage/' . $product->image) }}')" class="w-10 h-10 rounded-full bg-[#D26986] hover:bg-[#BD5773] text-white flex items-center justify-center shadow-md transition transform active:scale-95">
            <i class="fa-solid fa-plus"></i>
          </button>
        </div>
      </div>
      @endforeach
    </div>

    <div class="text-center mt-12">
      <a href="{{ route('catalog.index') }}" class="inline-block bg-white text-[#D26986] border border-[#D26986] hover:bg-rose-50 font-bold px-8 py-3 rounded-full shadow-sm transition">
        Lihat Varian Lainnya <i class="fa-solid fa-arrow-right ml-2"></i>
      </a>
    </div>
  </section>

  <section id="paket" class="py-16 bg-gradient-to-b from-white to-[#FBE8EE]/30 border-y border-rose-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center max-w-2xl mx-auto mb-16">
        <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mt-3">Bawa Pulang Kotak Kebahagiaan</h2>
        <p class="text-gray-600 text-sm mt-2">Pilihan box estetik yang cocok untuk dinikmati bersama keluarga atau dijadikan hadiah spesial.</p>
      </div>

      <!-- Box Grid -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
        @foreach($boxPackages as $index => $box)
        <div class="bg-white rounded-3xl p-6 shadow-md border {{ $index === 1 ? 'border-2 border-[#D26986] shadow-xl transform md:-translate-y-4' : 'border-rose-100' }} flex flex-col justify-between relative">
          @if($index === 1)
          <div class="absolute -top-3 left-1/2 transform -translate-x-1/2 bg-[#D26986] text-white text-[10px] uppercase font-extrabold tracking-wider px-4 py-1 rounded-full shadow-md">
            Paling Favorit
          </div>
          @else
          <span class="absolute top-4 right-4 bg-{{ $index === 0 ? 'rose' : 'indigo' }}-50 text-{{ $index === 0 ? '[#D26986]' : 'indigo-700' }} text-xs font-bold px-3 py-1 rounded-full">{{ $index === 0 ? 'Paling Hemat' : 'Eksklusif' }}</span>
          @endif
          <div>
            <img src="{{ asset('images/homepage/' . $box->image) }}" alt="{{ $box->name }}" class="w-full h-48 object-cover rounded-2xl mb-6">
            <h3 class="text-xl font-extrabold text-gray-900">{{ $box->name }}</h3>
            <p class="text-xs text-gray-500 mt-1">{{ Str::limit($box->description, 90) }}</p>
            <ul class="mt-4 space-y-2 text-xs text-gray-600">
              @if(str_contains($box->name, '4'))
              <li><i class="fa-solid fa-check text-[#D26986] mr-2"></i> Bebas pilih 4 varian rasa mochi</li>
              <li><i class="fa-solid fa-check text-[#D26986] mr-2"></i> Termasuk greeting card mini</li>
              <li><i class="fa-solid fa-check text-[#D26986] mr-2"></i> Box pita cantik & eksklusif</li>
              @elseif(str_contains($box->name, '6'))
              <li><i class="fa-solid fa-check text-[#D26986] mr-2"></i> Bebas pilih 6 varian rasa mochi</li>
              <li><i class="fa-solid fa-check text-[#D26986] mr-2"></i> Free ice gel tahan dingin 3 jam</li>
              <li><i class="fa-solid fa-check text-[#D26986] mr-2"></i> Kartu ucapan custom & pita mewah</li>
              @else
              <li><i class="fa-solid fa-check text-[#D26986] mr-2"></i> Lengkap semua varian rasa (12 pcs)</li>
              <li><i class="fa-solid fa-check text-[#D26986] mr-2"></i> Hardbox mewah eksklusif Ekimochi</li>
              <li><i class="fa-solid fa-check text-[#D26986] mr-2"></i> Free Gift Bag & Double Ice Gel</li>
              @endif
            </ul>
          </div>
          <div class="mt-8 pt-6 border-t border-rose-50">
            <div class="flex items-center justify-between mb-4">
              <span class="text-xs text-gray-400 font-bold uppercase">Harga Paket</span>
              <span class="text-2xl font-extrabold text-[#D26986]">Rp {{ number_format($box->price, 0, ',', '.') }}</span>
            </div>
            <button onclick="addToCart('{{ $box->name }}', {{ $box->price }}, '{{ asset('images/homepage/' . $box->image) }}')" class="w-full bg-[#D26986] hover:bg-[#BD5773] text-white font-bold py-3 rounded-full shadow-md transition text-sm">
              Pilih Paket Box
            </button>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- Mengapa Ekimochi Selalu Istimewa -->
  <section id="tentang" class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center max-w-2xl mx-auto mb-12">
      <h2 class="text-3xl font-extrabold text-gray-900 mt-3">Mengapa Ekimochi Selalu Istimewa?</h2>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <div class="bg-white p-6 rounded-3xl border border-rose-100 shadow-sm hover:shadow-md transition">
        <div class="w-12 h-12 rounded-2xl bg-rose-100 text-[#D26986] flex items-center justify-center text-xl font-bold mb-4">
          <i class="fa-solid fa-wheat-awn"></i>
        </div>
        <h3 class="font-bold text-gray-900 text-lg">Tepung Ketan Mochi Pilihan</h3>
        <p class="text-xs text-gray-500 mt-2 leading-relaxed">Kenyal pas 100% dari tepung ketan premium pilihan yang diolah dengan resep tradisional Jepang modern.</p>
      </div>
      <div class="bg-white p-6 rounded-3xl border border-rose-100 shadow-sm hover:shadow-md transition">
        <div class="w-12 h-12 rounded-2xl bg-rose-100 text-[#D26986] flex items-center justify-center text-xl font-bold mb-4">
          <i class="fa-solid fa-seedling"></i>
        </div>
        <h3 class="font-bold text-gray-900 text-lg">Buah Segar Cokelat Asli</h3>
        <p class="text-xs text-gray-500 mt-2 leading-relaxed">Isian buah segar asli setiap hari dan cokelat premium tanpa pemanis buatan agar aman dikonsumsi.</p>
      </div>
      <div class="bg-white p-6 rounded-3xl border border-rose-100 shadow-sm hover:shadow-md transition">
        <div class="w-12 h-12 rounded-2xl bg-rose-100 text-[#D26986] flex items-center justify-center text-xl font-bold mb-4">
          <i class="fa-solid fa-snowflake"></i>
        </div>
        <h3 class="font-bold text-gray-900 text-lg">Fresh Chilled Daily</h3>
        <p class="text-xs text-gray-500 mt-2 leading-relaxed">Selalu dibuat fresh setiap pagi dan disimpan dengan suhu terjaga demi menjaga tekstur kenyal sempurna.</p>
      </div>
      <div class="bg-white p-6 rounded-3xl border border-rose-100 shadow-sm hover:shadow-md transition">
        <div class="w-12 h-12 rounded-2xl bg-rose-100 text-[#D26986] flex items-center justify-center text-xl font-bold mb-4">
          <i class="fa-solid fa-box-open"></i>
        </div>
        <h3 class="font-bold text-gray-900 text-lg">Safe Delivery Packaging</h3>
        <p class="text-xs text-gray-500 mt-2 leading-relaxed">Dilengkapi ice pack khusus agar mochi tetap dingin dan tidak leleh selama perjalanan kurir instant.</p>
      </div>
    </div>
  </section>

  <!-- Testimoni Pelanggan -->
  <section class="py-16 bg-white border-y border-rose-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center max-w-2xl mx-auto mb-12">
        <h2 class="text-3xl font-extrabold text-gray-900 mt-3">Dicintai Pecinta Dessert Lembut</h2>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-[#FFF9FA] p-6 rounded-3xl border border-rose-100 flex flex-col justify-between">
          <div>
            <div class="flex text-amber-400 text-sm mb-3">
              <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
            </div>
            <p class="text-gray-700 text-xs leading-relaxed italic">"Enak banget! Kulit mochinya tipis dan kenyal pas. Isian stroberinya masih segar dan manisnya nggak bikin enek. Bakalan langganan terus!"</p>
          </div>
          <div class="flex items-center space-x-3 mt-6 pt-4 border-t border-rose-100">
            <div class="w-10 h-10 rounded-full bg-rose-200 flex items-center justify-center font-bold text-[#D26986]">AS</div>
            <div>
              <p class="font-bold text-xs text-gray-800">Amanda Salsabila</p>
              <p class="text-[10px] text-gray-500">Verified Buyer - Jakarta</p>
            </div>
          </div>
        </div>

        <div class="bg-[#FFF9FA] p-6 rounded-3xl border border-rose-100 flex flex-col justify-between">
          <div>
            <div class="flex text-amber-400 text-sm mb-3">
              <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
            </div>
            <p class="text-gray-700 text-xs leading-relaxed italic">"Pesan Delight Box buat kado ultah sahabat, ternyata box-nya mewah banget udah dapet pita dan ice gel. Sahabatku suka banget!"</p>
          </div>
          <div class="flex items-center space-x-3 mt-6 pt-4 border-t border-rose-100">
            <div class="w-10 h-10 rounded-full bg-rose-200 flex items-center justify-center font-bold text-[#D26986]">RP</div>
            <div>
              <p class="font-bold text-xs text-gray-800">Rizky Pratama</p>
              <p class="text-[10px] text-gray-500">Verified Buyer - Bandung</p>
            </div>
          </div>
        </div>

        <div class="bg-[#FFF9FA] p-6 rounded-3xl border border-rose-100 flex flex-col justify-between">
          <div>
            <div class="flex text-amber-400 text-sm mb-3">
              <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
            </div>
            <p class="text-gray-700 text-xs leading-relaxed italic">"Matcha-nya berasa banget teh hijaunya, nggak cuma manis doang. Tekstur mochinya lembut di mulut. Recommended!"</p>
          </div>
          <div class="flex items-center space-x-3 mt-6 pt-4 border-t border-rose-100">
            <div class="w-10 h-10 rounded-full bg-rose-200 flex items-center justify-center font-bold text-[#D26986]">NV</div>
            <div>
              <p class="font-bold text-xs text-gray-800">Nabila Vionita</p>
              <p class="text-[10px] text-gray-500">Verified Buyer - Surabaya</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Momen Manis di Instagram -->
  <section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row items-center justify-between mb-8">
      <div>
        <h2 class="text-2xl font-extrabold text-gray-900 mt-2">Momen Manis di Instagram @Ekimochi</h2>
      </div>
      <a href="https://instagram.com" target="_blank" class="text-xs font-bold text-[#D26986] hover:underline mt-2 sm:mt-0">Ikuti @Ekimochi <i class="fa-brands fa-instagram ml-1"></i></a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
      <div class="relative group overflow-hidden rounded-2xl aspect-square">
        <img src="{{ asset('images/homepage/gambar.png') }}" alt="Instagram 1" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white">
          <i class="fa-brands fa-instagram text-2xl"></i></div>
      </div>
      <div class="relative group overflow-hidden rounded-2xl aspect-square">
        <img src="{{ asset('images/homepage/mochi strawberry.png') }}" alt="Instagram 2" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white">
          <i class="fa-brands fa-instagram text-2xl"></i></div>
      </div>
      <div class="relative group overflow-hidden rounded-2xl aspect-square">
        <img src="{{ asset('images/homepage/mango.png') }}" alt="Instagram 3" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white">
          <i class="fa-brands fa-instagram text-2xl"></i></div>
      </div>
      <div class="relative group overflow-hidden rounded-2xl aspect-square">
        <img src="{{ asset('images/homepage/lotus.png') }}" alt="Instagram 4" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white">
          <i class="fa-brands fa-instagram text-2xl"></i></div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer id="outlet" class="bg-white border-t border-rose-100 pt-16 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8 pb-12 border-b border-rose-100">
        <!-- Col 1 -->
        <div class="space-y-4">
          <img src="{{ asset('images/homepage/logo perusahaan.png') }}" alt="Logo Ekimochi" style="width: 200px;">
          <p class="text-gray-500 text-xs leading-relaxed">Mochi lembut premium dengan isian buah segar dan lelehan cokelat pilihan yang siap mencerahkan harimu.</p>
          <div class="flex space-x-3 text-gray-500">
            <a href="#" class="w-8 h-8 rounded-full bg-rose-50 flex items-center justify-center hover:text-[#D26986] transition"><i class="fa-brands fa-instagram"></i></a>
            <a href="#" class="w-8 h-8 rounded-full bg-rose-50 flex items-center justify-center hover:text-[#D26986] transition"><i class="fa-brands fa-tiktok"></i></a>
            <a href="#" class="w-8 h-8 rounded-full bg-rose-50 flex items-center justify-center hover:text-[#D26986] transition"><i class="fa-brands fa-whatsapp"></i></a>
          </div>
        </div>

        <!-- Col 2 -->
        <div>
          <h4 class="font-bold text-gray-900 text-sm mb-4">Layanan Utama</h4>
          <ul class="space-y-2 text-xs text-gray-600">
            <li><a href="#varian" class="hover:text-[#D26986]">Pilihan Rasa Mochi</a></li>
            <li><a href="#paket" class="hover:text-[#D26986]">Paket Kotak Gift Box</a></li>
            <li><a href="#" class="hover:text-[#D26986]">Voucher Diskon Spesial</a></li>
            <li><a href="#" class="hover:text-[#D26986]">Katering Acara & Pesta</a></li>
          </ul>
        </div>

        <!-- Col 3 -->
        <div>
          <h4 class="font-bold text-gray-900 text-sm mb-4">Pusat Outlet</h4>
          <ul class="space-y-2 text-xs text-gray-600">
            <li><span class="font-semibold text-gray-800">Jakarta Selatan:</span> Senayan City Lt. LG</li>
            <li><span class="font-semibold text-gray-800">Bandung:</span> Jl. Riau No. 54</li>
            <li><span class="font-semibold text-gray-800">Surabaya:</span> Tunjungan Plaza 3</li>
            <li><span class="font-semibold text-gray-800">Jam Operasional:</span> 10.00 - 21.00 WIB</li>
          </ul>
        </div>

        <!-- Col 4 -->
        <div>
          <h4 class="font-bold text-gray-900 text-sm mb-4">Newsletter Promo</h4>
          <p class="text-xs text-gray-500 mb-3">Dapatkan info produk baru dan promo menarik langsung ke emailmu.</p>
          <form onsubmit="event.preventDefault(); showNotification('Terima kasih telah berlangganan!');" class="space-y-2">
            <input type="email" placeholder="Alamat emailmu..." class="w-full px-4 py-2 text-xs rounded-full border border-rose-200 focus:outline-none focus:border-[#D26986]" required>
            <button type="submit" class="w-full bg-[#D26986] text-white text-xs font-bold py-2 rounded-full hover:bg-[#BD5773] transition">Berlangganan</button>
          </form>
        </div>
      </div>

      <div class="flex flex-col sm:flex-row items-center justify-between pt-8 text-xs text-gray-500">
        <p>&copy; 2026 Ekimochi Indonesia. All rights reserved.</p>
        <div class="flex space-x-6 mt-4 sm:mt-0">
          <a href="#" class="hover:text-[#D26986]">Kebijakan Privasi</a>
          <a href="#" class="hover:text-[#D26986]">Syarat & Ketentuan</a>
          <a href="#faq" class="hover:text-[#D26986]">FAQ</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- Shopping Cart Drawer / Modal -->
  <div id="cart-drawer" class="fixed inset-0 z-50 overflow-hidden hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="toggleCartDrawer()"></div>
    <div class="absolute inset-y-0 right-0 max-w-full flex pl-10">
      <div class="w-screen max-w-md bg-white shadow-2xl flex flex-col justify-between p-6">
        <!-- Cart Header -->
        <div>
          <div class="flex items-center justify-between pb-4 border-b border-rose-100">
            <h3 class="text-lg font-bold text-gray-900"><i class="fa-solid fa-cart-shopping text-[#D26986] mr-2"></i> Keranjang Belanja</h3>
            <button onclick="toggleCartDrawer()" class="p-2 text-gray-400 hover:text-gray-600 rounded-full"><i class="fa-solid fa-xmark text-lg"></i></button>
          </div>
          <!-- Cart Items Container -->
          <div id="cart-items-container" class="mt-6 space-y-4 max-h-[50vh] overflow-y-auto pr-2">
            <!-- Dynamic Cart Items -->
            <div class="text-center text-gray-400 py-12 text-xs">Keranjang belanja masih kosong</div>
          </div>
        </div>

        <!-- Cart Footer -->
        <div class="border-t border-rose-100 pt-4 space-y-4">
          <div class="flex justify-between items-center text-sm font-bold text-gray-800">
            <span>Total Pembayaran:</span>
            <span id="cart-total-price" class="text-lg text-[#D26986]">Rp 0</span>
          </div>
          <button onclick="checkout()" class="w-full bg-[#D26986] hover:bg-[#BD5773] text-white font-bold py-3 rounded-full shadow-lg transition text-sm">
            Checkout Sekarang (WhatsApp)
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Search Modal -->
  <div id="search-modal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-start justify-center pt-20 px-4 hidden">
    <div class="bg-white rounded-3xl w-full max-w-xl p-6 shadow-2xl space-y-4 animate-in fade-in zoom-in duration-200">
      <div class="flex items-center justify-between border-b border-rose-100 pb-3">
        <h3 class="font-bold text-gray-800 text-base"><i class="fa-solid fa-magnifying-glass text-[#D26986] mr-2"></i> Cari Varian Mochi</h3>
        <button onclick="closeSearchModal()" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-lg"></i></button>
      </div>
      <input type="text" id="search-input" oninput="handleSearch(this.value)" placeholder="Ketik nama mochi atau box..." class="w-full px-4 py-3 rounded-2xl border border-rose-200 focus:outline-none focus:border-[#D26986] text-sm">
      <div id="search-results" class="max-h-60 overflow-y-auto space-y-2">
        <p class="text-xs text-gray-400 text-center py-4">Ketik untuk mencari produk...</p>
      </div>
    </div>
  </div>

  <!-- Login / Register Modal -->
  <div id="login-modal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center px-4 hidden">
    <div class="bg-white rounded-3xl w-full max-w-md p-6 sm:p-8 shadow-2xl relative space-y-6">
      <button onclick="closeLoginModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-lg"></i></button>
      <div class="text-center">
        <span class="text-2xl font-extrabold text-[#D26986]"><i class="fa-solid fa-cookie-bite mr-2"></i>Ekimochi</span>
        <h3 class="text-xl font-bold text-gray-900 mt-2">Selamat Datang Kembali!</h3>
        <p class="text-xs text-gray-500 mt-1">Masuk untuk kemudahan pemesanan & kumpulkan poin.</p>
      </div>
      <form onsubmit="event.preventDefault(); handleLoginSubmit();" class="space-y-4">
        <div>
          <label class="block text-xs font-bold text-gray-700 mb-1">Email atau Nomor WhatsApp</label>
          <input type="text" placeholder="cth: nama@email.com" class="w-full px-4 py-3 text-xs rounded-xl border border-rose-200 focus:outline-none focus:border-[#D26986]" required>
        </div>
        <div>
          <label class="block text-xs font-bold text-gray-700 mb-1">Kata Sandi</label>
          <input type="password" placeholder="••••••••" class="w-full px-4 py-3 text-xs rounded-xl border border-rose-200 focus:outline-none focus:border-[#D26986]" required>
        </div>
        <button type="submit" class="w-full bg-[#D26986] hover:bg-[#BD5773] text-white font-bold py-3 rounded-full text-sm shadow-md transition">Masuk Akun</button>
      </form>
    </div>
  </div>

  <!-- Floating Toast Notification -->
  <div id="toast-notification" class="fixed bottom-6 right-6 z-50 bg-gray-900 text-white text-xs px-5 py-3 rounded-2xl shadow-xl transform translate-y-20 opacity-0 transition-all duration-300 flex items-center space-x-2">
    <i class="fa-solid fa-circle-check text-emerald-400 text-sm"></i>
    <span id="toast-message">Berhasil ditambahkan ke keranjang!</span>
  </div>

  <script>
    const navLinks = document.querySelectorAll('.nav-link');

    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            navLinks.forEach(item => item.classList.remove('bg-[#D26986]', 'text-white', 'font-semibold'));
            this.classList.add('bg-[#D26986]', 'text-white', 'font-semibold');
        });
    });
  </script>

  <!-- JavaScript Application Logic -->
  <script>
    let cart = [];

    function toggleMobileMenu() {
      const menu = document.getElementById('mobile-menu');
      menu.classList.toggle('hidden');
    }

    function toggleCartDrawer() {
      const drawer = document.getElementById('cart-drawer');
      drawer.classList.toggle('hidden');
      renderCart();
    }

    function openSearchModal() {
      document.getElementById('search-modal').classList.remove('hidden');
      document.getElementById('search-input').focus();
    }

    function closeSearchModal() {
      document.getElementById('search-modal').classList.add('hidden');
    }

    function openLoginModal() {
      document.getElementById('login-modal').classList.remove('hidden');
    }

    function closeLoginModal() {
      document.getElementById('login-modal').classList.add('hidden');
    }

    function showNotification(msg) {
      const toast = document.getElementById('toast-notification');
      document.getElementById('toast-message').innerText = msg;
      toast.classList.remove('translate-y-20', 'opacity-0');
      setTimeout(() => {
        toast.classList.add('translate-y-20', 'opacity-0');
      }, 3000);
    }

    function addToCart(name, price, image) {
      const existingItem = cart.find(item => item.name === name);
      if (existingItem) {
        existingItem.qty += 1;
      } else {
        cart.push({ name, price, image, qty: 1 });
      }
      updateCartBadge();
      showNotification(`${name} berhasil ditambahkan!`);
    }

    function updateCartBadge() {
      const totalQty = cart.reduce((sum, item) => sum + item.qty, 0);
      document.getElementById('cart-badge').innerText = totalQty;
    }

    function updateQty(index, delta) {
      cart[index].qty += delta;
      if (cart[index].qty <= 0) {
        cart.splice(index, 1);
      }
      updateCartBadge();
      renderCart();
    }

    function renderCart() {
      const container = document.getElementById('cart-items-container');
      const totalPriceEl = document.getElementById('cart-total-price');

      if (cart.length === 0) {
        container.innerHTML = `<div class="text-center text-gray-400 py-12 text-xs">Keranjang belanja masih kosong</div>`;
        totalPriceEl.innerText = 'Rp 0';
        return;
      }

      let html = '';
      let total = 0;

      cart.forEach((item, index) => {
        total += item.price * item.qty;
        html += `
            <div class="flex items-center justify-between bg-rose-50/50 p-3 rounded-2xl border border-rose-100">
                <div class="flex items-center space-x-3">
                    <img src="${item.image}" alt="${item.name}" class="w-12 h-12 rounded-xl object-cover">
                    <div>
                        <h4 class="font-bold text-xs text-gray-800">${item.name}</h4>
                        <p class="text-[11px] text-[#D26986] font-semibold">Rp ${item.price.toLocaleString('id-ID')}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <button onclick="updateQty(${index}, -1)" class="w-6 h-6 rounded-full bg-white border border-rose-200 text-gray-600 flex items-center justify-center text-xs hover:bg-rose-100">-</button>
                    <span class="text-xs font-bold w-4 text-center">${item.qty}</span>
                    <button onclick="updateQty(${index}, 1)" class="w-6 h-6 rounded-full bg-white border border-rose-200 text-gray-600 flex items-center justify-center text-xs hover:bg-rose-100">+</button>
                </div>
            </div>
        `;
      });

      container.innerHTML = html;
      totalPriceEl.innerText = `Rp ${total.toLocaleString('id-ID')}`;
    }

    function checkout() {
      if (cart.length === 0) {
        showNotification('Keranjang belanja masih kosong!');
        return;
      }
      let message = "Halo Ekimochi, saya ingin memesan:\n\n";
      let total = 0;
      cart.forEach(item => {
        message += `- ${item.name} (${item.qty}x) @ Rp ${item.price.toLocaleString('id-ID')}\n`;
        total += item.price * item.qty;
      });
      message += `\nTotal: Rp ${total.toLocaleString('id-ID')}\n\nMohon informasi ketersediaan dan pengirimannya. Terima kasih!`;
      const encoded = encodeURIComponent(message);
      window.open(`https://wa.me/6281234567890?text=${encoded}`, '_blank');
    }

    function filterCatalog(category) {
      // Mengubah warna tombol yang diklik
      document.querySelectorAll('.catalog-btn').forEach(btn => {
        btn.classList.remove('bg-[#D26986]', 'text-white', 'shadow-sm');
        btn.classList.add('bg-white', 'text-gray-600', 'border', 'border-rose-100');
      });
      if (event && event.currentTarget) {
        event.currentTarget.classList.remove('bg-white', 'text-gray-600', 'border', 'border-rose-100');
        event.currentTarget.classList.add('bg-[#D26986]', 'text-white', 'shadow-sm');
      }

      // Menampilkan produk (maksimal 6 item per kategori)
      const cards = document.querySelectorAll('#product-grid .product-card');
      let visibleCount = 0;
      
      cards.forEach(card => {
        if ((category === 'all' || card.dataset.category === category) && visibleCount < 6) {
          card.style.display = 'flex';
          visibleCount++;
        } else {
          card.style.display = 'none';
        }
      });
    }

    function handleSearch(query) {
      const resultsContainer = document.getElementById('search-results');
      if (!query.trim()) {
        resultsContainer.innerHTML = `<p class="text-xs text-gray-400 text-center py-4">Ketik untuk mencari produk...</p>`;
        return;
      }

      const products = [
        @foreach($products as $product)
        { name: '{{ $product->name }}', price: {{ $product->price }}, img: '{{ asset('images/homepage/' . $product->image) }}' },
        @endforeach
      ];

      const filtered = products.filter(p => p.name.toLowerCase().includes(query.toLowerCase()));

      if (filtered.length === 0) {
        resultsContainer.innerHTML = `<p class="text-xs text-gray-400 text-center py-4">Produk tidak ditemukan.</p>`;
        return;
      }

      let html = '';
      filtered.forEach(item => {
        html += `
            <div class="flex items-center justify-between p-3 rounded-xl hover:bg-rose-50 transition cursor-pointer" onclick="addToCart('${item.name}', ${item.price}, '${item.img}'); closeSearchModal();">
                <div class="flex items-center space-x-3">
                    <img src="${item.img}" class="w-10 h-10 rounded-lg object-cover">
                    <div>
                        <h4 class="font-bold text-xs text-gray-800">${item.name}</h4>
                        <p class="text-[10px] text-[#D26986] font-semibold">Rp ${item.price.toLocaleString('id-ID')}</p>
                    </div>
                </div>
                <span class="text-xs text-[#D26986] font-bold"><i class="fa-solid fa-plus"></i> Tambah</span>
            </div>
        `;
      });
      resultsContainer.innerHTML = html;
    }

    function handleLoginSubmit() {
      closeLoginModal();
      showNotification('Berhasil masuk akun!');
    }
  </script>
</body>

</html>