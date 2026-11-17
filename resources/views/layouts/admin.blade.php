<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Admin Panel - Ekimochi')</title>
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

<body class="bg-gray-50 text-gray-900 antialiased min-h-screen flex flex-col" x-data="{ sidebarOpen: false }">

  <!-- Admin Navbar (tetap) -->
  <header class="sticky top-0 z-40 bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <!-- Left: Hamburger (mobile) + Logo -->
      <div class="flex items-center space-x-2 sm:space-x-4">
        <button @click="sidebarOpen = true" class="lg:hidden p-2 text-gray-600 hover:text-[#D26986]" aria-label="Buka menu">
          <i class="fa-solid fa-bars text-xl"></i>
        </button>
        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
          <img width="180" src="{{ asset('images\homepage\logo perusahaan.png') }}" alt="">
        </a>
      </div>

      <!-- Right Actions -->
      <div class="flex items-center space-x-3">
        <!-- View Site -->
        <a href="{{ route('home') }}" target="_blank" class="hidden sm:flex items-center space-x-2 px-3 py-2 text-gray-600 hover:text-[#D26986] text-sm font-medium transition">
          <i class="fa-solid fa-external-link-alt"></i>
          <span>Lihat Situs</span>
        </a>

        <!-- Admin Profile Dropdown -->
        <div class="relative" x-data="{ open: false }">
          <button @click="open = !open" class="flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-gray-100 transition">
            <div class="w-8 h-8 rounded-full bg-[#D26986] flex items-center justify-center text-white text-xs font-bold">
              {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <span class="hidden sm:inline text-sm font-medium text-gray-700">{{ Str::limit(Auth::user()->name, 15) }}</span>
            <i class="fa-solid fa-chevron-down text-xs text-gray-500"></i>
          </button>

          <!-- Dropdown Menu -->
          <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-200 py-2 z-50">
            <div class="px-4 py-3 border-b border-gray-100">
              <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
              <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
              <span class="inline-block mt-1 px-2 py-0.5 bg-[#D26986] text-white text-[10px] font-bold rounded-full">ADMIN</span>
            </div>
            <a href="{{ route('profile.show') }}" class="flex items-center space-x-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
              <i class="fa-solid fa-user w-5"></i>
              <span>Profil Saya</span>
            </a>
            <a href="{{ route('notification.index') }}" class="flex items-center space-x-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
              <i class="fa-solid fa-bell w-5"></i>
              <span>Preferensi Notifikasi</span>
            </a>
            <a href="{{ route('home') }}" class="flex items-center space-x-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
              <i class="fa-solid fa-home w-5"></i>
              <span>Homepage</span>
            </a>
            <div class="border-t border-gray-100 mt-2 pt-2">
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
      </div>
    </div>
  </header>

  <!-- Mobile Overlay -->
  <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 z-40 bg-gray-900/50 lg:hidden"></div>

  <!-- Mobile Off-canvas Sidebar -->
  <aside class="fixed inset-y-0 left-0 z-50 w-72 max-w-[85vw] bg-white shadow-xl flex flex-col transform transition-transform duration-300 -translate-x-full lg:hidden"
         :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
    @include('admin.partials.sidebar')
  </aside>

  <!-- Body: Sidebar + Content -->
  <div class="flex flex-1 items-stretch">
    <!-- Desktop Sidebar -->
    <aside class="hidden lg:block w-64 shrink-0 bg-white border-r border-gray-200">
      <div class="sticky top-16 h-[calc(100vh-4rem)] overflow-y-auto">
        @include('admin.partials.sidebar')
      </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 min-w-0">
      @yield('content')
    </main>
  </div>

  <!-- Footer -->
  <footer class="bg-white border-t border-gray-200 py-6 mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex flex-col sm:flex-row items-center justify-between text-xs text-gray-500">
        <p>&copy; 2026 Ekimochi Indonesia. Admin Panel v1.0</p>
        <div class="flex space-x-4 mt-2 sm:mt-0">
          <span>Laravel {{ app()->version() }}</span>
          <span>•</span>
          <span>PHP {{ phpversion() }}</span>
        </div>
      </div>
    </div>
  </footer>

  @yield('scripts')

</body>
</html>