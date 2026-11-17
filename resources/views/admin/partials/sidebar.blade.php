{{-- Admin Sidebar Partial — dipakai di layouts/admin.blade.php (desktop + mobile off-canvas) --}}
<div class="flex flex-col h-full">

  {{-- Header khusus mobile (tombol tutup) --}}
  <div class="lg:hidden flex items-center justify-between px-4 border-b border-gray-200 shrink-0 h-16">
    <span class="text-sm font-bold text-gray-900">Menu Admin</span>
    <button @click="sidebarOpen = false" class="p-2 text-gray-500 hover:text-[#D26986]" aria-label="Tutup menu">
      <i class="fa-solid fa-xmark text-xl"></i>
    </button>
  </div>

  {{-- Menu --}}
  <nav class="flex-1 overflow-y-auto px-4 py-4 space-y-1">
    <p class="px-3 pt-1 pb-1 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Menu Utama</p>

    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-[#D26986] text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
      <i class="fa-solid fa-house w-5 mr-3 text-center"></i>
      Dashboard
    </a>

    <a href="{{ route('admin.sales.index') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.sales.*') ? 'bg-[#D26986] text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
      <i class="fa-solid fa-chart-line w-5 mr-3 text-center"></i>
      Penjualan
    </a>

    <a href="{{ route('admin.orderSettings.index') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.orderSettings.*') ? 'bg-[#D26986] text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
      <i class="fa-solid fa-truck-fast w-5 mr-3 text-center"></i>
      Pesanan & Pengiriman
    </a>

    <a href="{{ route('admin.analytics.index') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.analytics.*') ? 'bg-[#D26986] text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
      <i class="fa-solid fa-chart-pie w-5 mr-3 text-center"></i>
      Analitik & Statistik
    </a>

    <a href="{{ route('admin.products.index') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.products.*') ? 'bg-[#D26986] text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
      <i class="fa-solid fa-box w-5 mr-3 text-center"></i>
      Produk
    </a>

    <a href="{{ route('admin.categories.index') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.categories.*') ? 'bg-[#D26986] text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
      <i class="fa-solid fa-tags w-5 mr-3 text-center"></i>
      Kategori
    </a>

    <p class="px-3 pt-6 pb-1 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Menu Lainnya</p>

    <a href="{{ route('admin.crm.index') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.crm.*') ? 'bg-[#D26986] text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
      <i class="fa-solid fa-handshake w-5 mr-3 text-center"></i>
      CRM
    </a>

    <a href="{{ route('admin.promo.index') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.promo.*') ? 'bg-[#D26986] text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
      <i class="fa-solid fa-percent w-5 mr-3 text-center"></i>
      Promo & Diskon
    </a>

    <a href="{{ route('admin.userSettings.index') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.userSettings.*') ? 'bg-[#D26986] text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
      <i class="fa-solid fa-users w-5 mr-3 text-center"></i>
      Pengguna
    </a>

    <a href="{{ route('admin.outlet.index') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.outlet.*') ? 'bg-[#D26986] text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
      <i class="fa-solid fa-store w-5 mr-3 text-center"></i>
      Outlet & QR
    </a>
  </nav>

  {{-- Footer khusus mobile (link situs) --}}
  <div class="lg:hidden shrink-0 border-t border-gray-200 p-4">
    <a href="{{ route('home') }}" target="_blank" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100 transition">
      <i class="fa-solid fa-external-link-alt w-5 mr-3 text-center"></i>
      Lihat Situs
    </a>
  </div>

</div>