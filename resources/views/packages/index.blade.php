@extends('layouts.app')

@section('title', 'Paket Box - Ekimochi')

@section('content')
<div class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
  <!-- Breadcrumb -->
  <nav class="text-xs text-gray-500 mb-6">
    <a href="{{ route('home') }}" class="hover:text-[#D26986] transition">Beranda</a>
    <span class="mx-2">/</span>
    <span class="text-gray-900 font-semibold">Paket Box</span>
  </nav>

  <!-- Header -->
  <div class="mb-8 text-center">
    <h1 class="text-3xl font-extrabold text-gray-900">Paket Box Ekimochi</h1>
    <p class="text-sm text-gray-500 mt-2">Pilih paket siap saji atau buat paket custom sesuai selera Anda</p>
  </div>

  <!-- Options Section -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
    <!-- Pre-made Packages -->
    <div class="bg-gradient-to-br from-[#D26986] to-[#BD5773] rounded-3xl p-8 text-white shadow-xl">
      <div class="flex items-center justify-between mb-4">
        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center">
          <i class="fa-solid fa-box-open text-3xl"></i>
        </div>
        <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-500 text-white text-xs font-bold">
          <i class="fa-solid fa-bolt mr-1"></i>
          Ready Stock
        </span>
      </div>
      <h2 class="text-2xl font-bold mb-2">Paket Siap Saji</h2>
      <p class="text-white/90 text-sm mb-6">Paket mochi pilihan yang sudah jadi, langsung pesan dan ambil. Cocok untuk gift atau konsumsi sendiri.</p>
      <a href="#pre-made-packages" class="inline-flex items-center px-6 py-3 bg-white text-[#D26986] font-bold rounded-xl hover:bg-gray-100 transition transform active:scale-95">
        <i class="fa-solid fa-arrow-down mr-2"></i>
        Lihat Paket
      </a>
    </div>

    <!-- Custom Package -->
    <div class="bg-white border-2 border-[#D26986] rounded-3xl p-8 shadow-xl hover:shadow-2xl transition">
      <div class="flex items-center justify-between mb-4">
        <div class="w-16 h-16 bg-[#FBE8EE] rounded-2xl flex items-center justify-center">
          <i class="fa-solid fa-wand-magic-sparkles text-3xl text-[#D26986]"></i>
        </div>
        <span class="inline-flex items-center px-3 py-1 rounded-full bg-yellow-500 text-white text-xs font-bold">
          <i class="fa-solid fa-clock mr-1"></i>
          Pre-Order
        </span>
      </div>
      <h2 class="text-2xl font-bold text-gray-900 mb-2">Paket Custom</h2>
      <p class="text-gray-600 text-sm mb-6">Buat paket sendiri sesuai selera! Pilih box dan isi dengan varian mochi favorit Anda. Butuh waktu 2-3 hari.</p>
      <a href="{{ route('packages.custom') }}" class="inline-flex items-center px-6 py-3 bg-[#D26986] text-white font-bold rounded-xl hover:bg-[#BD5773] transition transform active:scale-95">
        <i class="fa-solid fa-plus mr-2"></i>
        Buat Paket Custom
      </a>
    </div>
  </div>

  <!-- Pre-made Packages Grid -->
  <div id="pre-made-packages" class="scroll-mt-24">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Paket Siap Saji</h2>

    @if($packages->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      @foreach($packages as $package)
      <div class="bg-white rounded-3xl border border-rose-100 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group">
        <!-- Package Image -->
        <div class="aspect-square bg-gradient-to-br from-[#FBE8EE] to-white overflow-hidden relative">
          @if($package->image)
            <img src="{{ asset('storage/' . $package->image) }}" 
                 alt="{{ $package->name }}" 
                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
          @else
            <div class="w-full h-full flex items-center justify-center text-8xl">🎁</div>
          @endif
          
          <!-- Badge Stock -->
          @if($package->stock > 0)
            <span class="absolute top-3 right-3 inline-flex items-center px-3 py-1.5 rounded-full bg-green-500 text-white text-xs font-bold">
              <i class="fa-solid fa-check mr-1"></i>
              Ready Stock
            </span>
          @else
            <span class="absolute top-3 right-3 inline-flex items-center px-3 py-1.5 rounded-full bg-red-500 text-white text-xs font-bold">
              <i class="fa-solid fa-times mr-1"></i>
              Habis
            </span>
          @endif

          <!-- Category Badge -->
          <span class="absolute top-3 left-3 inline-flex items-center px-3 py-1.5 rounded-full bg-white/90 backdrop-blur text-gray-700 text-xs font-bold">
            <i class="fa-solid fa-box mr-1"></i>
            {{ $package->category->name }}
          </span>
        </div>

        <!-- Package Info -->
        <div class="p-6 space-y-4">
          <div>
            <h3 class="text-lg font-bold text-gray-900 group-hover:text-[#D26986] transition">{{ $package->name }}</h3>
            <p class="text-xs text-gray-500 mt-1">SKU: {{ $package->sku }}</p>
            @if($package->description)
              <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ $package->description }}</p>
            @endif
          </div>

          <!-- Stock Info -->
          <div class="pt-3 border-t border-gray-100">
            <p class="text-xs font-semibold text-gray-700 mb-2">Ketersediaan:</p>
            <div class="flex items-center space-x-2">
              @if($package->stock > 10)
                <span class="inline-flex items-center px-2 py-0.5 rounded bg-green-100 text-green-700 text-[10px] font-semibold">
                  <i class="fa-solid fa-circle-check mr-1"></i>
                  Stok Tersedia ({{ $package->stock }})
                </span>
              @elseif($package->stock > 0)
                <span class="inline-flex items-center px-2 py-0.5 rounded bg-yellow-100 text-yellow-700 text-[10px] font-semibold">
                  <i class="fa-solid fa-exclamation-triangle mr-1"></i>
                  Stok Terbatas ({{ $package->stock }})
                </span>
              @else
                <span class="inline-flex items-center px-2 py-0.5 rounded bg-red-100 text-red-700 text-[10px] font-semibold">
                  <i class="fa-solid fa-times-circle mr-1"></i>
                  Stok Habis
                </span>
              @endif
            </div>
          </div>

          <!-- Price & Action -->
          <div class="flex items-center justify-between pt-3 border-t border-gray-100">
            <div>
              <span class="text-xs text-gray-500 block">Harga</span>
              <span class="text-2xl font-extrabold text-[#D26986]">Rp {{ number_format($package->price, 0, ',', '.') }}</span>
            </div>
            <a href="{{ route('packages.show', $package) }}" class="inline-flex items-center px-4 py-2.5 bg-[#D26986] hover:bg-[#BD5773] text-white font-bold rounded-xl transition transform active:scale-95 text-sm">
              <i class="fa-solid fa-eye mr-2"></i>
              Detail
            </a>
          </div>
        </div>
      </div>
      @endforeach
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-white rounded-3xl border border-rose-100 shadow-sm p-16 text-center">
      <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-100 mb-4">
        <i class="fa-solid fa-box-open text-4xl text-gray-400"></i>
      </div>
      <h3 class="text-lg font-bold text-gray-900 mb-2">Belum Ada Paket Tersedia</h3>
      <p class="text-sm text-gray-500 mb-6">Paket box sedang dalam persiapan. Sementara itu, coba buat paket custom!</p>
      <a href="{{ route('packages.custom') }}" class="inline-flex items-center px-5 py-3 bg-[#D26986] hover:bg-[#BD5773] text-white font-bold rounded-xl transition">
        <i class="fa-solid fa-plus mr-2"></i>
        Buat Paket Custom
      </a>
    </div>
    @endif
  </div>

  <!-- Info Section -->
  <div class="mt-12 bg-gradient-to-r from-[#FBE8EE] to-white rounded-3xl p-8 border border-rose-100">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="flex items-start space-x-4">
        <div class="flex-shrink-0 w-12 h-12 bg-[#D26986] rounded-xl flex items-center justify-center text-white">
          <i class="fa-solid fa-gift text-xl"></i>
        </div>
        <div>
          <h4 class="font-bold text-gray-900 mb-1">Gift Box Eksklusif</h4>
          <p class="text-xs text-gray-600">Kemasan premium cocok untuk hadiah atau acara spesial</p>
        </div>
      </div>
      <div class="flex items-start space-x-4">
        <div class="flex-shrink-0 w-12 h-12 bg-[#D26986] rounded-xl flex items-center justify-center text-white">
          <i class="fa-solid fa-snowflake text-xl"></i>
        </div>
        <div>
          <h4 class="font-bold text-gray-900 mb-1">Dijaga Kesegaran</h4>
          <p class="text-xs text-gray-600">Packing rapi dengan ice gel agar mochi tetap fresh</p>
        </div>
      </div>
      <div class="flex items-start space-x-4">
        <div class="flex-shrink-0 w-12 h-12 bg-[#D26986] rounded-xl flex items-center justify-center text-white">
          <i class="fa-solid fa-heart text-xl"></i>
        </div>
        <div>
          <h4 class="font-bold text-gray-900 mb-1">Customizable</h4>
          <p class="text-xs text-gray-600">Buat paket sesuai selera atau pilih paket yang sudah ada</p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
