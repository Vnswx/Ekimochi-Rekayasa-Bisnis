@extends('layouts.app')

@section('title', $package->name . ' - Paket Box')

@section('content')
<div class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
  <!-- Breadcrumb -->
  <nav class="text-xs text-gray-500 mb-6">
    <a href="{{ route('home') }}" class="hover:text-[#D26986] transition">Beranda</a>
    <span class="mx-2">/</span>
    <a href="{{ route('packages.index') }}" class="hover:text-[#D26986] transition">Paket Box</a>
    <span class="mx-2">/</span>
    <span class="text-gray-900 font-semibold">{{ $package->name }}</span>
  </nav>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Package Image -->
    <div class="space-y-4">
      <div class="aspect-square bg-gradient-to-br from-[#FBE8EE] to-white rounded-3xl border border-rose-100 overflow-hidden">
        @if($package->image)
          <img src="{{ asset('storage/' . $package->image) }}" 
               alt="{{ $package->name }}" 
               class="w-full h-full object-cover">
        @else
          <div class="w-full h-full flex items-center justify-center">
            <span class="text-9xl">🎁</span>
          </div>
        @endif
      </div>

      <!-- Package Features -->
      <div class="grid grid-cols-3 gap-3">
        <div class="bg-white rounded-2xl border border-rose-100 p-4 text-center">
          <div class="text-2xl mb-1"></div>
          <p class="text-xs text-gray-500">Kategori</p>
          <p class="font-bold text-sm text-gray-900">{{ $package->category->name }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-rose-100 p-4 text-center">
          <div class="text-2xl mb-1"></div>
          <p class="text-xs text-gray-500">SKU</p>
          <p class="font-bold text-sm text-gray-900">{{ $package->sku }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-rose-100 p-4 text-center">
          <div class="text-2xl mb-1"></div>
          <p class="text-xs text-gray-500">Stok</p>
          <p class="font-bold text-sm text-gray-900">{{ $package->stock }} pcs</p>
        </div>
      </div>
    </div>

    <!-- Package Details -->
    <div class="space-y-6">
      <!-- Header -->
      <div>
        <div class="flex items-center gap-2 mb-3">
          @if($package->stock > 0)
            <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-500 text-white text-xs font-bold">
              <i class="fa-solid fa-check mr-1"></i>
              Ready Stock
            </span>
          @else
            <span class="inline-flex items-center px-3 py-1 rounded-full bg-red-500 text-white text-xs font-bold">
              <i class="fa-solid fa-times mr-1"></i>
              Stok Habis
            </span>
          @endif
          <span class="inline-flex items-center px-3 py-1 rounded-full bg-[#FBE8EE] text-[#D26986] text-xs font-bold">
            <i class="fa-solid fa-box mr-1"></i>
            {{ $package->category->name }}
          </span>
        </div>
        <h1 class="text-3xl font-extrabold text-gray-900 mb-2">{{ $package->name }}</h1>
        <p class="text-gray-600 text-sm">{{ $package->description }}</p>
      </div>

      <!-- Price -->
      <div class="bg-gradient-to-br from-[#FBE8EE] to-white rounded-2xl border border-rose-100 p-6">
        <p class="text-sm text-gray-600 mb-1">Harga Paket</p>
        <p class="text-4xl font-extrabold text-[#D26986]">Rp {{ number_format($package->price, 0, ',', '.') }}</p>
        <p class="text-xs text-gray-500 mt-2">
          <i class="fa-solid fa-tag mr-1"></i>
          SKU: {{ $package->sku }}
        </p>
      </div>

      <!-- Package Contents -->
      <div class="bg-white rounded-2xl border border-rose-100 p-6">
        <h3 class="font-bold text-gray-900 mb-4 flex items-center">
          <i class="fa-solid fa-info-circle mr-2 text-[#D26986]"></i>
          Deskripsi Paket
        </h3>
        <div class="prose prose-sm text-gray-600">
          <p>{{ $package->description ?? 'Paket box berisi mochi pilihan dengan kualitas terbaik.' }}</p>
          
          <div class="mt-4 grid grid-cols-2 gap-4 not-prose">
            <div class="p-3 bg-gray-50 rounded-xl">
              <p class="text-xs text-gray-500 mb-1">Kategori</p>
              <p class="font-bold text-gray-900">{{ $package->category->name }}</p>
            </div>
            <div class="p-3 bg-gray-50 rounded-xl">
              <p class="text-xs text-gray-500 mb-1">Stok Tersedia</p>
              <p class="font-bold text-gray-900">{{ $package->stock }} box</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Add to Cart Form -->
      <form action="{{ route('packages.addToCart', $package) }}" method="POST" class="space-y-4">
        @csrf
        <div class="bg-white rounded-2xl border border-rose-100 p-6">
          <label class="block text-sm font-semibold text-gray-700 mb-3">Jumlah Paket</label>
          <div class="flex items-center space-x-4">
            <button type="button" onclick="decrementPackageQty()" 
                    class="w-12 h-12 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold transition flex items-center justify-center">
              <i class="fa-solid fa-minus"></i>
            </button>
            <input type="number" 
                   id="package-quantity" 
                   name="quantity" 
                   value="1" 
                   min="1" 
                   max="{{ $package->stock }}"
                   class="w-20 text-center text-xl font-bold border-2 border-gray-200 rounded-xl py-3 focus:border-[#D26986] focus:ring-2 focus:ring-[#FBE8EE] outline-none transition">
            <button type="button" onclick="incrementPackageQty()" 
                    class="w-12 h-12 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold transition flex items-center justify-center">
              <i class="fa-solid fa-plus"></i>
            </button>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="grid grid-cols-2 gap-4">
          <a href="{{ route('packages.index') }}" 
             class="inline-flex items-center justify-center px-6 py-4 bg-white border-2 border-gray-200 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Kembali
          </a>
          <button type="submit" 
                  @if($package->stock == 0) disabled @endif
                  class="inline-flex items-center justify-center px-6 py-4 bg-[#D26986] hover:bg-[#BD5773] text-white font-bold rounded-xl transition transform active:scale-95 shadow-lg shadow-pink-200 disabled:opacity-50 disabled:cursor-not-allowed">
            <i class="fa-solid fa-cart-plus mr-2"></i>
            @if($package->stock > 0)
              Tambah ke Keranjang
            @else
              Stok Habis
            @endif
          </button>
        </div>
      </form>

      <!-- Info Alert -->
      @if($package->stock <= 5 && $package->stock > 0)
      <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-4">
        <div class="flex items-start space-x-3">
          <i class="fa-solid fa-exclamation-triangle text-yellow-600 text-xl mt-0.5"></i>
          <div>
            <p class="font-bold text-yellow-900 text-sm mb-1">Stok Terbatas</p>
            <p class="text-xs text-yellow-800">
              Tersisa {{ $package->stock }} paket. Segera pesan sebelum kehabisan!
            </p>
          </div>
        </div>
      </div>
      @endif
    </div>
  </div>
</div>

<script>
function incrementPackageQty() {
  const input = document.getElementById('package-quantity');
  const max = parseInt(input.max);
  const current = parseInt(input.value);
  if (current < max) {
    input.value = current + 1;
  }
}

function decrementPackageQty() {
  const input = document.getElementById('package-quantity');
  const min = parseInt(input.min);
  const current = parseInt(input.value);
  if (current > min) {
    input.value = current - 1;
  }
}
</script>
@endsection
