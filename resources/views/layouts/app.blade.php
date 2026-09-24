<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Ekimochi')</title>
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
            }
          },
          fontFamily: {
            sans: ['Plus Jakarta Sans', 'system-ui', 'sans-serif'],
          }
        }
      }
    }
  </script>
</head>

<body class="bg-[#FFF9FA] text-[#2D2D2D] antialiased">

  <!-- Navbar -->
  <header class="sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-rose-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
      <!-- Logo -->
      <div class="flex items-center space-x-8">
        <a href="{{ route('home') }}" class="flex items-center space-x-2">
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

  <!-- Main Content -->
  <main>
    @yield('content')
  </main>

  <!-- Footer -->
  <footer id="outlet" class="bg-white border-t border-rose-100 pt-16 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8 pb-12 border-b border-rose-100">
        <!-- Col 1 -->
        <div class="space-y-4">
          <img src="{{ asset('images/homepage/logo perusahaan.png') }}" alt="Logo Ekimochi" style="width: 200px;">
          <p class="text-gray-500 text-xs leading-relaxed">Mochi lembut premium dengan isian buah segar dan lelehan cokelat pilihan yang siap mencerahkan harimu.</p>
          <div class="flex space-x-3 text-gray-500">
            <a href="{{ route('home') }}" class="w-8 h-8 rounded-full bg-rose-50 flex items-center justify-center hover:text-[#D26986] transition"><i class="fa-brands fa-instagram"></i></a>
            <a href="{{ route('home') }}" class="w-8 h-8 rounded-full bg-rose-50 flex items-center justify-center hover:text-[#D26986] transition"><i class="fa-brands fa-tiktok"></i></a>
            <a href="{{ route('home') }}" class="w-8 h-8 rounded-full bg-rose-50 flex items-center justify-center hover:text-[#D26986] transition"><i class="fa-brands fa-whatsapp"></i></a>
          </div>
        </div>

        <!-- Col 2 -->
        <div>
          <h4 class="font-bold text-gray-900 text-sm mb-4">Layanan Utama</h4>
          <ul class="space-y-2 text-xs text-gray-600">
            <li><a href="{{ route('home') }}#varian" class="hover:text-[#D26986]">Pilihan Rasa Mochi</a></li>
            <li><a href="{{ route('home') }}#paket" class="hover:text-[#D26986]">Paket Kotak Gift Box</a></li>
            <li><a href="{{ route('home') }}" class="hover:text-[#D26986]">Voucher Diskon Spesial</a></li>
            <li><a href="{{ route('home') }}" class="hover:text-[#D26986]">Katering Acara & Pesta</a></li>
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
          <form class="space-y-2">
            <input type="email" placeholder="Alamat emailmu..." class="w-full px-4 py-2 text-xs rounded-full border border-rose-200 focus:outline-none focus:border-[#D26986]" required>
            <button type="submit" class="w-full bg-[#D26986] text-white text-xs font-bold py-2 rounded-full hover:bg-[#BD5773] transition">Berlangganan</button>
          </form>
        </div>
      </div>

      <div class="flex flex-col sm:flex-row items-center justify-between pt-8 text-xs text-gray-500">
        <p>&copy; 2026 Ekimochi Indonesia. All rights reserved.</p>
        <div class="flex space-x-6 mt-4 sm:mt-0">
          <a href="{{ route('home') }}" class="hover:text-[#D26986]">Kebijakan Privasi</a>
          <a href="{{ route('home') }}" class="hover:text-[#D26986]">Syarat & Ketentuan</a>
          <a href="{{ route('home') }}#faq" class="hover:text-[#D26986]">FAQ</a>
        </div>
      </div>
    </div>
  </footer>

</body>
</html>
