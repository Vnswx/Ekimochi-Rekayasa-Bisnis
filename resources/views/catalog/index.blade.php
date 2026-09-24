@extends('layouts.app')

@section('title', 'Katalog Produk - Ekimochi')

@section('content')
<div class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
  <!-- Header & Breadcrumb -->
  <div class="mb-8">
    <nav class="text-xs text-gray-500 mb-3">
      <a href="{{ route('home') }}" class="hover:text-[#D26986]">Beranda</a>
      <span class="mx-2">/</span>
      <span class="text-gray-900 font-semibold">Katalog Produk</span>
    </nav>
    <h1 class="text-3xl font-extrabold text-gray-900">Katalog Produk Ekimochi</h1>
    <p class="text-sm text-gray-500 mt-1">Eksplorasi seluruh varian mochi lembut dan paket box spesial kami.</p>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <!-- Sidebar Filter -->
    <div class="lg:col-span-1 space-y-6">
      <!-- Search Box -->
      <div class="bg-white p-5 rounded-3xl border border-rose-100 shadow-sm">
        <h3 class="font-bold text-gray-900 text-sm border-b border-rose-100 pb-3 mb-4">
          <i class="fa-solid fa-magnifying-glass text-[#D26986] mr-2"></i> Cari Produk
        </h3>
        <form method="GET" action="{{ route('catalog.index') }}">
          <input 
            type="text" 
            name="search" 
            value="{{ request('search') }}"
            placeholder="Nama produk atau SKU..."
            class="w-full px-4 py-2.5 text-xs border border-rose-200 rounded-full focus:outline-none focus:border-[#D26986] bg-[#FFF9FA]"
          >
          <button type="submit" class="w-full mt-3 bg-[#D26986] hover:bg-[#BD5773] text-white text-xs font-bold py-2.5 rounded-full transition">
            <i class="fa-solid fa-search mr-1"></i> Cari
          </button>
          @if(request()->hasAny(['search', 'category', 'stock', 'sort']))
          <a href="{{ route('catalog.index') }}" class="block w-full mt-2 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold py-2.5 rounded-full transition">
            <i class="fa-solid fa-times mr-1"></i> Reset Filter
          </a>
          @endif
        </form>
      </div>

      <!-- Category Filter -->
      <div class="bg-white p-5 rounded-3xl border border-rose-100 shadow-sm">
        <h3 class="font-bold text-gray-900 text-sm border-b border-rose-100 pb-3 mb-4">
          <i class="fa-solid fa-filter text-[#D26986] mr-2"></i> Kategori Produk
        </h3>
        <div class="space-y-2">
          <a href="{{ route('catalog.index', array_merge(request()->except('category'), request()->only(['search', 'stock', 'sort']))) }}" 
             class="block w-full text-left px-4 py-2.5 rounded-xl text-xs font-semibold transition flex items-center justify-between {{ !request('category') ? 'bg-[#D26986] text-white' : 'bg-white text-gray-600 hover:bg-[#FBE8EE]' }}">
            <span>Semua Produk</span>
            <span class="text-[10px]">({{ $products->total() }})</span>
          </a>
          @foreach($categories as $category)
          <a href="{{ route('catalog.index', array_merge(request()->except('category'), ['category' => $category->id] + request()->only(['search', 'stock', 'sort']))) }}" 
             class="block w-full text-left px-4 py-2.5 rounded-xl text-xs font-semibold transition flex items-center justify-between {{ request('category') == $category->id ? 'bg-[#D26986] text-white' : 'bg-white text-gray-600 hover:bg-[#FBE8EE]' }}">
            <span>{{ $category->name }}</span>
            <span class="text-[10px]">({{ $category->products_count }})</span>
          </a>
          @endforeach
        </div>
      </div>

      <!-- Stock Filter -->
      <div class="bg-white p-5 rounded-3xl border border-rose-100 shadow-sm">
        <h3 class="font-bold text-gray-900 text-sm border-b border-rose-100 pb-3 mb-4">
          <i class="fa-solid fa-box text-[#D26986] mr-2"></i> Ketersediaan
        </h3>
        <div class="space-y-2">
          <a href="{{ route('catalog.index', array_merge(request()->except('stock'), request()->only(['search', 'category', 'sort']))) }}" 
             class="block w-full text-left px-4 py-2.5 rounded-xl text-xs font-semibold transition {{ !request('stock') ? 'bg-[#D26986] text-white' : 'bg-white text-gray-600 hover:bg-[#FBE8EE]' }}">
            Semua
          </a>
          <a href="{{ route('catalog.index', array_merge(request()->except('stock'), ['stock' => 'available'] + request()->only(['search', 'category', 'sort']))) }}" 
             class="block w-full text-left px-4 py-2.5 rounded-xl text-xs font-semibold transition {{ request('stock') == 'available' ? 'bg-[#D26986] text-white' : 'bg-white text-gray-600 hover:bg-[#FBE8EE]' }}">
            <i class="fa-solid fa-check-circle mr-1"></i> Tersedia
          </a>
          <a href="{{ route('catalog.index', array_merge(request()->except('stock'), ['stock' => 'out'] + request()->only(['search', 'category', 'sort']))) }}" 
             class="block w-full text-left px-4 py-2.5 rounded-xl text-xs font-semibold transition {{ request('stock') == 'out' ? 'bg-[#D26986] text-white' : 'bg-white text-gray-600 hover:bg-[#FBE8EE]' }}">
            <i class="fa-solid fa-times-circle mr-1"></i> Habis
          </a>
        </div>
      </div>
    </div>

    <!-- Product Area -->
    <div class="lg:col-span-3 space-y-6">
      <!-- Sorting Bar -->
      <div class="bg-white p-4 rounded-2xl border border-rose-100 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <span class="text-xs text-gray-500 font-medium">
          Menampilkan <strong class="text-gray-900">{{ $products->count() }}</strong> dari <strong class="text-gray-900">{{ $products->total() }}</strong> produk
        </span>
        <form method="GET" action="{{ route('catalog.index') }}" class="flex items-center space-x-2 w-full sm:w-auto">
          @foreach(request()->except('sort') as $key => $value)
          <input type="hidden" name="{{ $key }}" value="{{ $value }}">
          @endforeach
          <span class="text-xs text-gray-500 whitespace-nowrap">Urutkan:</span>
          <select name="sort" onchange="this.form.submit()" class="text-xs border border-rose-200 rounded-xl px-3 py-2 focus:outline-none focus:border-[#D26986] bg-[#FFF9FA] text-gray-700 w-full sm:w-auto">
            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama A-Z</option>
            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama Z-A</option>
            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga Terendah</option>
            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
          </select>
        </form>
      </div>

      <!-- Search/Filter Info -->
      @if(request()->hasAny(['search', 'category', 'stock']))
      <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 text-xs text-blue-800">
        <div class="flex items-start gap-2">
          <i class="fa-solid fa-info-circle text-sm mt-0.5"></i>
          <div class="flex-1">
            <span class="font-semibold">Filter aktif:</span>
            @if(request('search'))
            <span class="ml-2 inline-block bg-blue-200 px-2 py-1 rounded-full">Pencarian: "{{ request('search') }}"</span>
            @endif
            @if(request('category'))
            <span class="ml-2 inline-block bg-blue-200 px-2 py-1 rounded-full">Kategori: {{ $categories->find(request('category'))->name ?? 'Unknown' }}</span>
            @endif
            @if(request('stock'))
            <span class="ml-2 inline-block bg-blue-200 px-2 py-1 rounded-full">Stok: {{ request('stock') == 'available' ? 'Tersedia' : 'Habis' }}</span>
            @endif
          </div>
        </div>
      </div>
      @endif

      <!-- Product Grid -->
      @if($products->count() > 0)
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($products as $product)
        <div class="bg-white rounded-3xl p-5 shadow-sm hover:shadow-xl transition border border-rose-100 flex flex-col justify-between group">
          <a href="{{ route('catalog.show', $product->id) }}" class="cursor-pointer">
            <div class="relative overflow-hidden rounded-2xl mb-4">
              <img 
                src="{{ $product->image ? asset('images/homepage/' . $product->image) : asset('images/placeholder-product.png') }}" 
                alt="{{ $product->name }}" 
                class="w-full h-48 object-cover transform group-hover:scale-105 transition duration-500"
              >
              @if($product->stock == 0)
              <span class="absolute top-3 left-3 bg-gray-600 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">Habis</span>
              @elseif($product->stock < 10)
              <span class="absolute top-3 left-3 bg-amber-500 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">Stok Terbatas</span>
              @endif
            </div>
            <div class="flex items-center space-x-2 mb-2">
              <span class="bg-[#FBE8EE] text-[#D26986] text-[10px] font-semibold px-2 py-0.5 rounded">{{ $product->category->name }}</span>
              @if($product->unit)
              <span class="bg-gray-100 text-gray-600 text-[10px] font-semibold px-2 py-0.5 rounded">{{ $product->unit }}</span>
              @endif
            </div>
            <h3 class="font-bold text-base text-gray-800 group-hover:text-[#D26986] transition">{{ $product->name }}</h3>
            @if($product->description)
            <p class="text-gray-500 text-xs mt-1 leading-relaxed line-clamp-2">{{ Str::limit($product->description, 80) }}</p>
            @endif
          </a>
          <div class="flex items-center justify-between mt-4 pt-4 border-t border-rose-50">
            <div>
              <span class="text-[9px] text-gray-400 block uppercase font-bold">Harga</span>
              <span class="text-base font-extrabold text-[#D26986]">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
              @if($product->stock > 0)
              <span class="block text-[9px] text-gray-500 mt-0.5">Stok: {{ $product->stock }}</span>
              @endif
            </div>
            @if($product->stock > 0)
            <button onclick="addToCart('{{ $product->name }}', {{ $product->price }}, '{{ $product->image ? asset('images/homepage/' . $product->image) : asset('images/placeholder-product.png') }}')" 
                    class="w-9 h-9 rounded-full bg-[#D26986] hover:bg-[#BD5773] text-white flex items-center justify-center shadow-md transition transform active:scale-95">
              <i class="fa-solid fa-plus text-xs"></i>
            </button>
            @else
            <button disabled class="w-9 h-9 rounded-full bg-gray-300 text-gray-500 flex items-center justify-center cursor-not-allowed">
              <i class="fa-solid fa-ban text-xs"></i>
            </button>
            @endif
          </div>
        </div>
        @endforeach
      </div>

      <!-- Pagination -->
      @if($products->hasPages())
      <div class="bg-white p-4 rounded-2xl border border-rose-100 shadow-sm">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
          <div class="text-xs text-gray-500">
            Halaman {{ $products->currentPage() }} dari {{ $products->lastPage() }}
          </div>
          <div class="flex items-center gap-2">
            {{ $products->links('pagination::tailwind') }}
          </div>
        </div>
      </div>
      @endif

      @else
      <!-- Empty State -->
      <div class="bg-white rounded-3xl p-12 text-center border border-rose-100 shadow-sm">
        <i class="fa-solid fa-box-open text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-xl font-bold text-gray-800 mb-2">Produk Tidak Ditemukan</h3>
        <p class="text-sm text-gray-500 mb-6">Tidak ada produk yang sesuai dengan filter Anda.</p>
        <a href="{{ route('catalog.index') }}" class="inline-block bg-[#D26986] hover:bg-[#BD5773] text-white px-6 py-3 rounded-full font-semibold text-sm transition">
          <i class="fa-solid fa-arrow-left mr-2"></i> Lihat Semua Produk
        </a>
      </div>
      @endif
    </div>
  </div>
</div>

<!-- Cart Functions -->
<script>
function addToCart(name, price, image) {
  let cart = JSON.parse(localStorage.getItem('cart')) || [];
  const existingIndex = cart.findIndex(item => item.name === name);
  
  if (existingIndex > -1) {
    cart[existingIndex].qty += 1;
  } else {
    cart.push({ name, price, image, qty: 1 });
  }
  
  localStorage.setItem('cart', JSON.stringify(cart));
  updateCartBadge();
  alert('✅ ' + name + ' ditambahkan ke keranjang!');
}

function updateCartBadge() {
  const cart = JSON.parse(localStorage.getItem('cart')) || [];
  const totalItems = cart.reduce((sum, item) => sum + item.qty, 0);
  const badge = document.getElementById('cart-badge');
  if (badge) badge.textContent = totalItems;
}

document.addEventListener('DOMContentLoaded', updateCartBadge);
</script>
@endsection
