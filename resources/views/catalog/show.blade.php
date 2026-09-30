@extends('layouts.app')

@section('title', $product->name . ' - Ekimochi')

@section('content')
<div class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
  <!-- Breadcrumb -->
  <nav class="text-xs text-gray-500 mb-6">
    <a href="{{ route('home') }}" class="hover:text-[#D26986] transition">Beranda</a>
    <span class="mx-2">/</span>
    <a href="{{ route('catalog.index') }}" class="hover:text-[#D26986] transition">Katalog</a>
    <span class="mx-2">/</span>
    <span class="text-gray-900 font-semibold">{{ $product->name }}</span>
  </nav>

  <!-- Product Detail Card -->
  <div class="bg-white rounded-3xl border border-rose-100 shadow-sm overflow-hidden mb-12">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-6 sm:p-10">
      
      <!-- Product Image Section -->
      <div class="space-y-4">
        <div class="aspect-square w-full bg-gradient-to-br from-[#FBE8EE] to-white rounded-2xl overflow-hidden border border-rose-100 shadow-inner">
          @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" 
                 alt="{{ $product->name }}" 
                 class="w-full h-full object-cover">
          @else
            <div class="w-full h-full flex items-center justify-center text-9xl">
              🍡
            </div>
          @endif
        </div>
        
        <!-- Quick Info Badges -->
        <div class="flex flex-wrap gap-2">
          <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-[#FBE8EE] text-[#D26986]">
            <i class="fa-solid fa-tag mr-1.5"></i>
            {{ $product->category->name }}
          </span>
          <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
            <i class="fa-solid fa-barcode mr-1.5"></i>
            SKU: {{ $product->sku }}
          </span>
          @if($product->status === 'active')
            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">
              <i class="fa-solid fa-check-circle mr-1.5"></i>
              Aktif
            </span>
          @endif
        </div>
      </div>

      <!-- Product Info Section -->
      <div class="flex flex-col justify-between space-y-6">
        <div class="space-y-4">
          <!-- Product Name & Price -->
          <div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 leading-tight mb-3">
              {{ $product->name }}
            </h1>
            <div class="flex items-baseline space-x-2">
              <span class="text-4xl font-extrabold text-[#D26986]">
                Rp {{ number_format($product->price, 0, ',', '.') }}
              </span>
              <span class="text-sm text-gray-500">/ {{ $product->unit }}</span>
            </div>
          </div>

          <!-- Stock Status -->
          <div class="p-4 rounded-2xl border-2 {{ $product->stock > 10 ? 'bg-green-50 border-green-200' : ($product->stock > 0 ? 'bg-yellow-50 border-yellow-200' : 'bg-red-50 border-red-200') }}">
            @if($product->stock > 10)
              <div class="flex items-center space-x-2 text-green-700">
                <i class="fa-solid fa-circle-check text-xl"></i>
                <span class="font-bold text-sm">Stok Tersedia</span>
              </div>
              <p class="text-xs text-green-600 mt-1">{{ $product->stock }} {{ $product->unit }} siap dikirim</p>
            @elseif($product->stock > 0)
              <div class="flex items-center space-x-2 text-yellow-700">
                <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                <span class="font-bold text-sm">Stok Terbatas</span>
              </div>
              <p class="text-xs text-yellow-600 mt-1">Tersisa {{ $product->stock }} {{ $product->unit }}</p>
            @else
              <div class="flex items-center space-x-2 text-red-700">
                <i class="fa-solid fa-circle-xmark text-xl"></i>
                <span class="font-bold text-sm">Stok Habis</span>
              </div>
              <p class="text-xs text-red-600 mt-1">Produk sedang tidak tersedia</p>
            @endif
          </div>

          <!-- Description -->
          @if($product->description)
          <div class="pt-4 border-t border-rose-100">
            <h3 class="text-sm font-bold text-gray-900 mb-2 flex items-center">
              <i class="fa-solid fa-align-left text-[#D26986] mr-2"></i>
              Deskripsi Produk
            </h3>
            <p class="text-sm text-gray-600 leading-relaxed">
              {{ $product->description }}
            </p>
          </div>
          @endif

          <!-- Product Meta -->
          <div class="grid grid-cols-2 gap-4 pt-4 border-t border-rose-100">
            <div class="bg-[#FFF9FA] p-3 rounded-xl">
              <p class="text-xs text-gray-500 mb-1">Kategori</p>
              <p class="text-sm font-bold text-gray-900">{{ $product->category->name }}</p>
            </div>
            <div class="bg-[#FFF9FA] p-3 rounded-xl">
              <p class="text-xs text-gray-500 mb-1">Satuan</p>
              <p class="text-sm font-bold text-gray-900">{{ $product->unit }}</p>
            </div>
            <div class="bg-[#FFF9FA] p-3 rounded-xl">
              <p class="text-xs text-gray-500 mb-1">Ditambahkan</p>
              <p class="text-sm font-bold text-gray-900">{{ $product->created_at->format('d M Y') }}</p>
            </div>
            <div class="bg-[#FFF9FA] p-3 rounded-xl">
              <p class="text-xs text-gray-500 mb-1">Terakhir Update</p>
              <p class="text-sm font-bold text-gray-900">{{ $product->updated_at->format('d M Y') }}</p>
            </div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-3 pt-4">
          @if($product->stock > 0)
          <!-- Add to Cart Form -->
          <form method="POST" action="{{ route('cart.add', $product) }}" class="space-y-3">
            @csrf
            
            <!-- Quantity Selector -->
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">
                <i class="fa-solid fa-calculator mr-1"></i>
                Jumlah
              </label>
              <div class="flex items-center space-x-3">
                <button type="button" onclick="decrementQty()" class="w-12 h-12 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-xl transition">
                  <i class="fa-solid fa-minus"></i>
                </button>
                <input type="number" 
                       id="quantity" 
                       name="quantity" 
                       value="1" 
                       min="1" 
                       max="{{ $product->stock }}"
                       class="flex-1 text-center px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-[#D26986] focus:ring-2 focus:ring-[#FBE8EE] text-lg font-bold"
                       readonly>
                <button type="button" onclick="incrementQty()" class="w-12 h-12 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-xl transition">
                  <i class="fa-solid fa-plus"></i>
                </button>
              </div>
              <p class="text-xs text-gray-500 mt-2">Maksimal: {{ $product->stock }} {{ $product->unit }}</p>
            </div>

            <button type="submit" class="w-full bg-[#D26986] hover:bg-[#BD5773] text-white font-bold py-4 rounded-2xl transition transform active:scale-95 shadow-lg flex items-center justify-center space-x-2">
              <i class="fa-solid fa-cart-plus text-lg"></i>
              <span>Tambah ke Keranjang</span>
            </button>
          </form>
          @else
          <button disabled class="w-full bg-gray-300 text-gray-500 font-bold py-4 rounded-2xl cursor-not-allowed flex items-center justify-center space-x-2">
            <i class="fa-solid fa-ban text-lg"></i>
            <span>Stok Habis</span>
          </button>
          @endif
          
          <a href="{{ route('catalog.index') }}" class="block w-full bg-white hover:bg-gray-50 text-[#D26986] font-bold py-4 rounded-2xl transition border-2 border-[#D26986] text-center">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Kembali ke Katalog
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Related Products -->
  @if($relatedProducts->count() > 0)
  <div class="mt-16">
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-2xl font-extrabold text-gray-900">Produk Terkait</h2>
      <a href="{{ route('catalog.index', ['category' => $product->category_id]) }}" class="text-xs font-semibold text-[#D26986] hover:text-[#BD5773] transition">
        Lihat Semua <i class="fa-solid fa-arrow-right ml-1"></i>
      </a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
      @foreach($relatedProducts as $related)
      <a href="{{ route('catalog.show', $related) }}" class="group bg-white rounded-2xl border border-rose-100 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1">
        <!-- Product Image -->
        <div class="aspect-square bg-gradient-to-br from-[#FBE8EE] to-white overflow-hidden">
          @if($related->image)
            <img src="{{ asset('storage/' . $related->image) }}" 
                 alt="{{ $related->name }}" 
                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
          @else
            <div class="w-full h-full flex items-center justify-center text-6xl">
              🍡
            </div>
          @endif
        </div>
        
        <!-- Product Info -->
        <div class="p-4 space-y-2">
          <h3 class="text-sm font-bold text-gray-900 line-clamp-2 group-hover:text-[#D26986] transition">
            {{ $related->name }}
          </h3>
          <div class="flex items-center justify-between">
            <span class="text-lg font-extrabold text-[#D26986]">
              Rp {{ number_format($related->price, 0, ',', '.') }}
            </span>
            @if($related->stock > 0)
              <span class="text-[10px] font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-full">
                <i class="fa-solid fa-check"></i> Tersedia
              </span>
            @else
              <span class="text-[10px] font-semibold text-red-600 bg-red-50 px-2 py-1 rounded-full">
                Habis
              </span>
            @endif
          </div>
        </div>
      </a>
      @endforeach
    </div>
  </div>
  @endif
</div>

<script>
  function incrementQty() {
    const input = document.getElementById('quantity');
    const max = parseInt(input.max);
    const current = parseInt(input.value);
    if (current < max) {
      input.value = current + 1;
    }
  }

  function decrementQty() {
    const input = document.getElementById('quantity');
    const min = parseInt(input.min);
    const current = parseInt(input.value);
    if (current > min) {
      input.value = current - 1;
    }
  }
</script>
@endsection
