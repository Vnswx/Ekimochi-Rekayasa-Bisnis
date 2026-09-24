@extends('layouts.admin')

@section('title', 'Admin Dashboard - Ekimochi')

@section('content')
<div class="py-8">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
      <p class="text-sm text-gray-500 mt-1">Selamat datang kembali, {{ Auth::user()->name }}! Berikut adalah situasi terkini toko Anda.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between mb-4">
          <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
            <i class="fa-solid fa-box text-blue-600 text-xl"></i>
          </div>
          <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">Total</span>
        </div>
        <h3 class="text-3xl font-bold text-gray-900">{{ $totalProducts }}</h3>
        <p class="text-sm text-gray-500 mt-1">Total Produk</p>
        <div class="mt-4 flex items-center gap-4 text-xs">
          <span class="text-green-600"><i class="fa-solid fa-circle mr-1"></i> Aktif: {{ $activeProducts }}</span>
          <span class="text-gray-400"><i class="fa-solid fa-circle mr-1"></i> Tidak Aktif: {{ $inactiveProducts }}</span>
        </div>
      </div>

      <!-- Categories -->
      <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between mb-4">
          <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
            <i class="fa-solid fa-tags text-purple-600 text-xl"></i>
          </div>
          <span class="text-xs font-semibold text-purple-600 bg-purple-50 px-2 py-1 rounded-full">Kategori</span>
        </div>
        <h3 class="text-3xl font-bold text-gray-900">{{ $totalCategories }}</h3>
        <p class="text-sm text-gray-500 mt-1">Kategori Produk</p>
        <a href="{{ route('admin.categories.index') }}" class="mt-4 inline-flex items-center text-xs font-semibold text-purple-600 hover:text-purple-700">
          Kelola Kategori<i class="fa-solid fa-arrow-right ml-1"></i>
        </a>
      </div>

      <!-- Low Stock Alert -->
      <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between mb-4">
          <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
            <i class="fa-solid fa-triangle-exclamation text-amber-600 text-xl"></i>
          </div>
          <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2 py-1 rounded-full">Peringatan</span>
        </div>
        <h3 class="text-3xl font-bold text-gray-900">{{ $lowStockProducts }}</h3>
        <p class="text-sm text-gray-500 mt-1">Produk dengan Stok Terbatas</p>
        <p class="text-xs text-amber-600 mt-4">Stok &lt; 10 unit</p>
      </div>

      <!-- Out of Stock -->
      <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between mb-4">
          <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
            <i class="fa-solid fa-ban text-red-600 text-xl"></i>
          </div>
          <span class="text-xs font-semibold text-red-600 bg-red-50 px-2 py-1 rounded-full">Peringatan</span>
        </div>
        <h3 class="text-3xl font-bold text-gray-900">{{ $outOfStockProducts }}</h3>
        <p class="text-sm text-gray-500 mt-1">Stok Habis</p>
        <p class="text-xs text-red-600 mt-4">Perlu restok</p>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Categories Overview -->
      <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-lg font-bold text-gray-900">Gambaran Umum Kategori</h2>
          <a href="{{ route('admin.categories.index') }}" class="text-xs font-semibold text-[#D26986] hover:text-[#BD5773]">
            Lihat semua <i class="fa-solid fa-arrow-right ml-1"></i>
          </a>
        </div>
        
        <div class="space-y-4">
          @forelse($categories as $category)
          <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 bg-[#FBE8EE] rounded-lg flex items-center justify-center">
                <i class="fa-solid fa-tag text-[#D26986]"></i>
              </div>
              <div>
                <h3 class="text-sm font-semibold text-gray-900">{{ $category->name }}</h3>
                <p class="text-xs text-gray-500">{{ $category->products_count }} produk</p>
              </div>
            </div>
            <a href="{{ route('admin.products.index', ['category' => $category->id]) }}" class="text-xs font-medium text-gray-600 hover:text-[#D26986]">
              <i class="fa-solid fa-arrow-right"></i>
            </a>
          </div>
          @empty
          <div class="text-center py-8 text-gray-500">
            <i class="fa-solid fa-inbox text-4xl mb-2"></i>
            <p class="text-sm">Tidak ada kategori yang ditemukan</p>
          </div>
          @endforelse
        </div>
      </div>

      <!-- Recent Products -->
      <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-lg font-bold text-gray-900">Produk Terkini</h2>
          <a href="{{ route('admin.products.index') }}" class="text-xs font-semibold text-[#D26986] hover:text-[#BD5773]">
            Lihat semua <i class="fa-solid fa-arrow-right ml-1"></i>
          </a>
        </div>
        
        <div class="space-y-4">
          @forelse($recentProducts as $product)
          <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
            <div class="flex items-center gap-3">
              <img 
                src="{{ $product->image ? asset('images/homepage/' . $product->image) : asset('images/placeholder-product.png') }}" 
                alt="{{ $product->name }}" 
                class="w-12 h-12 rounded-lg object-cover"
              >
              <div>
                <h3 class="text-sm font-semibold text-gray-900">{{ $product->name }}</h3>
                <div class="flex items-center gap-2 mt-1">
                  <span class="text-xs text-gray-500">{{ $product->category->name }}</span>
                  <span class="text-xs text-gray-400">•</span>
                  <span class="text-xs font-semibold text-[#D26986]">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                </div>
              </div>
            </div>
            <div class="text-right">
              <span class="text-xs font-medium {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                Stock: {{ $product->stock }}
              </span>
              <div class="mt-1">
                <a href="{{ route('admin.products.edit', $product->id) }}" class="text-xs font-medium text-gray-600 hover:text-[#D26986]">
                  <i class="fa-solid fa-pen"></i>
                </a>
              </div>
            </div>
          </div>
          @empty
          <div class="text-center py-8 text-gray-500">
            <i class="fa-solid fa-inbox text-4xl mb-2"></i>
            <p class="text-sm">Tidak ada kategori yang ditemukan</p>
          </div>
          @endforelse
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-6 bg-gradient-to-r from-[#D26986] to-[#BD5773] rounded-2xl p-6 text-white">
      <h2 class="text-lg font-bold mb-4">Aksi Cepat</h2>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('admin.products.create') }}" class="bg-white/20 hover:bg-white/30 rounded-xl p-4 text-center transition">
          <i class="fa-solid fa-plus text-2xl mb-2"></i>
          <p class="text-sm font-semibold">Tambah Produk</p>
        </a>
        <a href="{{ route('admin.categories.index') }}" class="bg-white/20 hover:bg-white/30 rounded-xl p-4 text-center transition">
          <i class="fa-solid fa-tags text-2xl mb-2"></i>
          <p class="text-sm font-semibold">Atur Kategori</p>
        </a>
        <a href="{{ route('admin.products.index') }}" class="bg-white/20 hover:bg-white/30 rounded-xl p-4 text-center transition">
          <i class="fa-solid fa-boxes-stacked text-2xl mb-2"></i>
          <p class="text-sm font-semibold">Lihat Produk</p>
        </a>
        <a href="{{ route('home') }}" target="_blank" class="bg-white/20 hover:bg-white/30 rounded-xl p-4 text-center transition">
          <i class="fa-solid fa-external-link-alt text-2xl mb-2"></i>
          <p class="text-sm font-semibold">Lihat Situs</p>
        </a>
      </div>
    </div>
  </div>
</div>
@endsection
