@extends('layouts.app')

@section('title', 'Keranjang Belanja - Ekimochi')

@section('content')
<div class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
  <!-- Breadcrumb -->
  <nav class="text-xs text-gray-500 mb-6">
    <a href="{{ route('home') }}" class="hover:text-[#D26986] transition">Beranda</a>
    <span class="mx-2">/</span>
    <span class="text-gray-900 font-semibold">Keranjang Belanja</span>
  </nav>

  <!-- Header -->
  <div class="mb-8">
    <h1 class="text-3xl font-extrabold text-gray-900 flex items-center">
      <i class="fa-solid fa-cart-shopping text-[#D26986] mr-3"></i>
      Keranjang Belanja
    </h1>
    <p class="text-sm text-gray-500 mt-1">Review dan kelola produk yang akan dibeli</p>
  </div>

  <!-- Alert Messages -->
  @if(session('success'))
  <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg" role="alert">
    <div class="flex items-center">
      <i class="fa-solid fa-circle-check text-xl mr-3"></i>
      <span class="font-medium">{{ session('success') }}</span>
    </div>
  </div>
  @endif

  @if(session('error'))
  <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg" role="alert">
    <div class="flex items-center">
      <i class="fa-solid fa-circle-exclamation text-xl mr-3"></i>
      <span class="font-medium">{{ session('error') }}</span>
    </div>
  </div>
  @endif

  @if(empty($cartItems) && empty($customPackages))
  <!-- Empty Cart State -->
  <div class="bg-white rounded-3xl border border-rose-100 shadow-sm p-16 text-center">
    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gray-100 mb-6">
      <i class="fa-solid fa-cart-shopping text-5xl text-gray-400"></i>
    </div>
    <h2 class="text-2xl font-bold text-gray-900 mb-3">Keranjang Anda Kosong</h2>
    <p class="text-gray-500 mb-8">Belum ada produk di keranjang belanja. Yuk mulai belanja sekarang!</p>
    <a href="{{ route('catalog.index') }}" class="inline-flex items-center px-6 py-3 bg-[#D26986] hover:bg-[#BD5773] text-white font-bold rounded-xl transition transform active:scale-95 shadow-lg">
      <i class="fa-solid fa-store mr-2"></i>
      Mulai Belanja
    </a>
  </div>
  @else
  
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Cart Items -->
    <div class="lg:col-span-2 space-y-4">
      @foreach($cartItems as $item)
      <div class="bg-white rounded-2xl border border-rose-100 shadow-sm p-6 hover:shadow-md transition">
        <div class="flex flex-col sm:flex-row gap-6">
          <!-- Product Image -->
          <div class="flex-shrink-0">
            <div class="w-32 h-32 rounded-xl overflow-hidden bg-gradient-to-br from-[#FBE8EE] to-white border border-rose-100">
              @if($item['product']->image)
                <img src="{{ asset('storage/' . $item['product']->image) }}" 
                     alt="{{ $item['product']->name }}" 
                     class="w-full h-full object-cover">
              @else
                <div class="w-full h-full flex items-center justify-center text-4xl">🍡</div>
              @endif
            </div>
          </div>

          <!-- Product Info -->
          <div class="flex-1 space-y-3">
            <div>
              <a href="{{ route('catalog.show', $item['product']) }}" class="text-lg font-bold text-gray-900 hover:text-[#D26986] transition">
                {{ $item['product']->name }}
              </a>
              <p class="text-xs text-gray-500 mt-1">SKU: {{ $item['product']->sku }}</p>
            </div>

            <div class="flex items-center space-x-2">
              <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-[#FBE8EE] text-[#D26986] text-xs font-semibold">
                <i class="fa-solid fa-tag mr-1"></i>
                {{ $item['product']->category->name }}
              </span>
              @if($item['product']->stock < 10)
              <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">
                <i class="fa-solid fa-triangle-exclamation mr-1"></i>
                Stok Terbatas
              </span>
              @endif
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-3 border-t border-gray-100">
              <!-- Price -->
              <div>
                <span class="text-xs text-gray-500 block">Harga Satuan</span>
                <span class="text-lg font-bold text-[#D26986]">Rp {{ number_format($item['product']->price, 0, ',', '.') }}</span>
              </div>

              <!-- Quantity Control -->
              <form method="POST" action="{{ route('cart.update', $item['product']) }}" class="flex items-center space-x-3">
                @csrf
                @method('PUT')
                <div class="flex items-center border-2 border-gray-300 rounded-xl overflow-hidden">
                  <button type="button" onclick="this.nextElementSibling.stepDown(); this.parentElement.parentElement.submit();" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold transition">
                    <i class="fa-solid fa-minus text-xs"></i>
                  </button>
                  <input type="number" 
                         name="quantity" 
                         value="{{ $item['quantity'] }}" 
                         min="1" 
                         max="{{ $item['product']->stock }}"
                         class="w-16 text-center border-x-2 border-gray-300 py-2 text-sm font-bold focus:outline-none"
                         onchange="this.form.submit()">
                  <button type="button" onclick="this.previousElementSibling.stepUp(); this.parentElement.parentElement.submit();" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold transition">
                    <i class="fa-solid fa-plus text-xs"></i>
                  </button>
                </div>
              </form>

              <!-- Subtotal -->
              <div class="text-right">
                <span class="text-xs text-gray-500 block">Subtotal</span>
                <span class="text-xl font-extrabold text-gray-900">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
              </div>
            </div>
          </div>

          <!-- Remove Button -->
          <div class="flex sm:flex-col items-start justify-between sm:justify-start">
            <form method="POST" action="{{ route('cart.remove', $item['product']) }}" onsubmit="return confirm('Hapus produk ini dari keranjang?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus">
                <i class="fa-solid fa-trash"></i>
              </button>
            </form>
          </div>
        </div>
      </div>
      @endforeach

      <!-- Custom Packages Section -->
      @if(!empty($customPackages))
      <div class="mt-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
          <i class="fa-solid fa-wand-magic-sparkles text-[#D26986] mr-2"></i>
          Paket Custom (Pre-Order)
        </h3>
        
        @foreach($customPackages as $index => $package)
        <div class="bg-gradient-to-br from-yellow-50 to-white rounded-2xl border-2 border-yellow-200 shadow-sm p-6 hover:shadow-md transition mb-4">
          <!-- PO Badge -->
          <div class="flex items-center justify-between mb-4">
            <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-yellow-500 text-white text-xs font-bold">
              <i class="fa-solid fa-clock mr-1"></i>
              Pre-Order {{ $package['preorder_days'] }} Hari Kerja
            </span>
            <span class="text-xs text-gray-500">
              {{ \Carbon\Carbon::parse($package['created_at'])->format('d M Y, H:i') }}
            </span>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Box Info -->
            <div class="space-y-3">
              <div>
                <p class="text-xs text-gray-500 mb-1">Box Dipilih</p>
                <p class="font-bold text-gray-900 text-lg">{{ $package['box_name'] }}</p>
                <p class="text-xs text-gray-500">Kapasitas: {{ $package['box_capacity'] }} pcs</p>
              </div>
              <div class="bg-white rounded-lg p-3 border border-yellow-200">
                <p class="text-xs text-gray-500 mb-1">Biaya Box</p>
                <p class="font-bold text-[#D26986]">Rp {{ number_format($package['box_price'], 0, ',', '.') }}</p>
              </div>
            </div>

            <!-- Products List -->
            <div class="md:col-span-2 space-y-2">
              <p class="text-sm font-semibold text-gray-700 mb-3">Isi Paket ({{ $package['total_items'] }} pcs):</p>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-48 overflow-y-auto">
                @foreach($package['products'] as $item)
                  @if(isset($package['product_details'][$item['id']]))
                  @php
                    $product = (object) $package['product_details'][$item['id']];
                  @endphp
                  <div class="flex items-center space-x-2 bg-white rounded-lg p-2 border border-gray-200">
                    @if($product->image)
                      <img src="{{ asset('storage/' . $product->image) }}" 
                           alt="{{ $product->name }}" 
                           class="w-10 h-10 rounded object-cover flex-shrink-0">
                    @else
                      <div class="w-10 h-10 rounded bg-gray-100 flex items-center justify-center flex-shrink-0">
                        <span class="text-lg">🍡</span>
                      </div>
                    @endif
                    <div class="flex-1 min-w-0">
                      <p class="text-xs font-semibold text-gray-900 truncate">{{ $product->name }}</p>
                      <p class="text-[10px] text-gray-500">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-[#D26986] text-white text-xs font-bold">
                      {{ $item['quantity'] }}x
                    </span>
                  </div>
                  @endif
                @endforeach
              </div>

              @if($package['notes'])
              <div class="mt-3 bg-blue-50 border border-blue-200 rounded-lg p-3">
                <p class="text-xs text-gray-600 mb-1"><i class="fa-solid fa-note-sticky mr-1"></i> Catatan:</p>
                <p class="text-xs text-gray-900">{{ $package['notes'] }}</p>
              </div>
              @endif
            </div>
          </div>

          <!-- Total & Actions -->
          <div class="flex items-center justify-between mt-6 pt-4 border-t border-yellow-200">
            <div>
              <p class="text-xs text-gray-500 mb-1">Total Harga Paket</p>
              <p class="text-2xl font-extrabold text-[#D26986]">Rp {{ number_format($package['total_price'], 0, ',', '.') }}</p>
            </div>
            <form method="POST" action="{{ route('cart.removeCustom', $index) }}" onsubmit="return confirm('Hapus paket custom ini dari keranjang?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-bold rounded-lg transition">
                <i class="fa-solid fa-trash mr-2"></i>
                Hapus Paket
              </button>
            </form>
          </div>
        </div>
        @endforeach
      </div>
      @endif
    </div>

    <!-- Order Summary -->
    <div class="lg:col-span-1">
      <div class="bg-white rounded-2xl border border-rose-100 shadow-sm p-6 sticky top-24">
        <h3 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b border-gray-200">
          <i class="fa-solid fa-receipt text-[#D26986] mr-2"></i>
          Ringkasan Belanja
        </h3>

        <div class="space-y-4 mb-6">
          <div class="flex items-center justify-between text-sm">
            <span class="text-gray-600">Total Item</span>
            <span class="font-semibold text-gray-900">{{ count($cartItems) + count($customPackages) }} item</span>
          </div>
          
          <div class="flex items-center justify-between text-sm">
            <span class="text-gray-600">Produk Reguler</span>
            <span class="font-semibold text-gray-900">{{ array_sum(array_column($cartItems, 'quantity')) }} pcs</span>
          </div>

          @if(!empty($customPackages))
          <div class="flex items-center justify-between text-sm">
            <span class="text-gray-600">Paket Custom</span>
            <span class="font-semibold text-yellow-600">{{ count($customPackages) }} paket (PO)</span>
          </div>
          @endif

          <div class="pt-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
              <span class="text-base font-semibold text-gray-900">Total Belanja</span>
              <span class="text-2xl font-extrabold text-[#D26986]">
                Rp {{ number_format($subtotal + array_sum(array_column($customPackages, 'total_price')), 0, ',', '.') }}
              </span>
            </div>
            @if(!empty($customPackages))
            <p class="text-xs text-yellow-600 mt-2">
              <i class="fa-solid fa-info-circle mr-1"></i>
              Termasuk {{ count($customPackages) }} paket custom (Pre-Order)
            </p>
            @endif
          </div>
        </div>

        <div class="space-y-3">
          <a href="{{ route('checkout.index') }}" class="block w-full bg-[#D26986] hover:bg-[#BD5773] text-white font-bold py-4 rounded-xl transition transform active:scale-95 shadow-lg text-center">
            <i class="fa-solid fa-credit-card mr-2"></i>
            Lanjut ke Checkout
          </a>

          <a href="{{ route('catalog.index') }}" class="block w-full bg-white hover:bg-gray-50 text-[#D26986] font-semibold py-3 rounded-xl transition border-2 border-[#D26986] text-center">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Lanjut Belanja
          </a>

          <form method="POST" action="{{ route('cart.clear') }}" onsubmit="return confirm('Kosongkan seluruh keranjang?')" class="pt-3 border-t border-gray-200">
            @csrf
            @method('DELETE')
            <button type="submit" class="w-full text-red-600 hover:text-red-700 font-semibold py-2 text-sm transition">
              <i class="fa-solid fa-trash-can mr-2"></i>
              Kosongkan Keranjang
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
  @endif
</div>
@endsection
