@extends('layouts.app')

@section('title', 'Buat Paket Custom - Ekimochi')

@section('content')
<div class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="customPackageBuilder()">
  <!-- Breadcrumb -->
  <nav class="text-xs text-gray-500 mb-6">
    <a href="{{ route('home') }}" class="hover:text-[#D26986] transition">Beranda</a>
    <span class="mx-2">/</span>
    <a href="{{ route('packages.index') }}" class="hover:text-[#D26986] transition">Paket Box</a>
    <span class="mx-2">/</span>
    <span class="text-gray-900 font-semibold">Buat Paket Custom</span>
  </nav>

  <!-- Header -->
  <div class="mb-8 text-center">
    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-[#FBE8EE] mb-4">
      <i class="fa-solid fa-wand-magic-sparkles text-3xl text-[#D26986]"></i>
    </div>
    <h1 class="text-3xl font-extrabold text-gray-900">Buat Paket Custom Anda</h1>
    <p class="text-sm text-gray-500 mt-2">Pilih box dan isi dengan mochi favorit Anda. Pre-order 2-3 hari kerja.</p>
  </div>

  <form action="{{ route('packages.addCustomToCart') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    @csrf
    
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
      <!-- Step 1: Choose Box Size -->
      <div class="bg-white rounded-3xl border border-rose-100 shadow-sm p-6">
        <div class="flex items-center mb-4">
          <div class="w-8 h-8 rounded-full bg-[#D26986] text-white flex items-center justify-center font-bold text-sm mr-3">1</div>
          <h2 class="text-xl font-bold text-gray-900">Pilih Ukuran Box</h2>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          @foreach($boxSizes as $key => $box)
          <label class="relative cursor-pointer group">
            <input type="radio" 
                   name="box_size" 
                   value="{{ $key }}" 
                   x-model="selectedBox"
                   @change="updateBoxCapacity('{{ $key }}', {{ $box['capacity'] }}, {{ $box['price'] }})"
                   class="peer sr-only" 
                   required>
            <div class="border-2 border-gray-200 rounded-2xl p-5 text-center transition-all peer-checked:border-[#D26986] peer-checked:bg-[#FBE8EE] peer-checked:shadow-lg">
              <div class="text-4xl mb-2">
                @if($key == 'small') 📦
                @elseif($key == 'medium') 📦📦
                @else 📦📦📦
                @endif
              </div>
              <p class="font-bold text-gray-900 mb-1">{{ $box['name'] }}</p>
              <p class="text-xs text-gray-500 mb-2">Kapasitas: {{ $box['capacity'] }} pcs</p>
              <p class="text-sm font-bold text-[#D26986]">+ Rp {{ number_format($box['price'], 0, ',', '.') }}</p>
            </div>
            <div class="absolute top-3 right-3 w-6 h-6 rounded-full border-2 border-gray-300 bg-white transition-all peer-checked:border-[#D26986] peer-checked:bg-[#D26986] flex items-center justify-center">
              <i class="fa-solid fa-check text-white text-xs opacity-0 peer-checked:opacity-100"></i>
            </div>
          </label>
          @endforeach
        </div>

        <div x-show="selectedBox" class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-xl">
          <p class="text-xs text-blue-800">
            <i class="fa-solid fa-info-circle mr-1"></i>
            Box <strong x-text="boxName"></strong> dapat menampung maksimal <strong x-text="boxCapacity"></strong> mochi. 
            Silakan pilih produk di bawah.
          </p>
        </div>
      </div>

      <!-- Step 2: Choose Products by Category -->
      <div class="bg-white rounded-3xl border border-rose-100 shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
          <div class="flex items-center">
            <div class="w-8 h-8 rounded-full bg-[#D26986] text-white flex items-center justify-center font-bold text-sm mr-3">2</div>
            <h2 class="text-xl font-bold text-gray-900">Pilih Produk Mochi</h2>
          </div>
          <div class="text-sm">
            <span class="text-gray-500">Dipilih:</span>
            <span class="font-bold" :class="totalItems > boxCapacity ? 'text-red-600' : 'text-[#D26986]'" x-text="totalItems"></span>
            <span class="text-gray-500">/ <span x-text="boxCapacity || '0'"></span></span>
          </div>
        </div>

        <!-- Category Filter Tabs -->
        <div class="flex flex-wrap gap-2 mb-6 pb-4 border-b border-gray-200">
          <button type="button" 
                  @click="filterCategory = 'all'"
                  :class="filterCategory === 'all' ? 'bg-[#D26986] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                  class="px-4 py-2 rounded-xl font-semibold text-sm transition">
            <i class="fa-solid fa-border-all mr-1"></i>
            Semua ({{ $products->count() }})
          </button>
          @foreach($products->groupBy('category.name') as $categoryName => $categoryProducts)
          <button type="button" 
                  @click="filterCategory = '{{ $categoryName }}'"
                  :class="filterCategory === '{{ $categoryName }}' ? 'bg-[#D26986] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                  class="px-4 py-2 rounded-xl font-semibold text-sm transition">
            <i class="fa-solid fa-tag mr-1"></i>
            {{ $categoryName }} ({{ $categoryProducts->count() }})
          </button>
          @endforeach
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          @foreach($products as $product)
          <div x-show="filterCategory === 'all' || filterCategory === '{{ $product->category->name }}'"
               class="border border-gray-200 rounded-2xl p-4 hover:border-[#D26986] transition"
               :class="{'bg-[#FBE8EE] border-[#D26986]': selectedProducts[{{ $product->id }}] && selectedProducts[{{ $product->id }}] > 0}">
            <div class="flex items-center space-x-3">
              <!-- Product Image -->
              @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" 
                     alt="{{ $product->name }}" 
                     class="w-16 h-16 rounded-xl object-cover flex-shrink-0">
              @else
                <div class="w-16 h-16 rounded-xl bg-gray-200 flex items-center justify-center flex-shrink-0">
                  <span class="text-2xl">🍡</span>
                </div>
              @endif

              <!-- Product Info -->
              <div class="flex-1 min-w-0">
                <p class="font-bold text-sm text-gray-900 truncate">{{ $product->name }}</p>
                <p class="text-xs text-gray-500">{{ $product->category->name ?? 'Uncategorized' }}</p>
                <p class="text-xs text-[#D26986] font-bold mt-1">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                <p class="text-[10px] text-gray-400 mt-0.5">Stock: {{ $product->stock }}</p>
              </div>

              <!-- Quantity Controls -->
              <div class="flex items-center space-x-1">
                <button type="button" 
                        @click="decrementProduct({{ $product->id }})"
                        class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold transition flex items-center justify-center"
                        :disabled="!selectedProducts[{{ $product->id }}] || selectedProducts[{{ $product->id }}] <= 0">
                  <i class="fa-solid fa-minus text-xs"></i>
                </button>
                <input type="number" 
                       x-model.number="selectedProducts[{{ $product->id }}]"
                       name="products[{{ $loop->index }}][quantity]"
                       min="0"
                       :max="Math.min(boxCapacity, {{ $product->stock }})"
                       class="w-12 text-center text-sm font-bold border border-gray-200 rounded-lg py-1 focus:border-[#D26986] focus:ring-1 focus:ring-[#D26986] outline-none"
                       @input="updateTotal()">
                <input type="hidden" 
                       x-show="selectedProducts[{{ $product->id }}] && selectedProducts[{{ $product->id }}] > 0"
                       name="products[{{ $loop->index }}][id]" 
                       value="{{ $product->id }}">
                <button type="button" 
                        @click="incrementProduct({{ $product->id }}, {{ $product->stock }})"
                        class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold transition flex items-center justify-center"
                        :disabled="totalItems >= boxCapacity || selectedProducts[{{ $product->id }}] >= {{ $product->stock }}">
                  <i class="fa-solid fa-plus text-xs"></i>
                </button>
              </div>
            </div>
          </div>
          @endforeach
        </div>

        <!-- Warning if over capacity -->
        <div x-show="totalItems > boxCapacity" class="mt-4 p-4 bg-red-50 border border-red-200 rounded-xl">
          <p class="text-xs text-red-800">
            <i class="fa-solid fa-exclamation-triangle mr-1"></i>
            Total produk (<strong x-text="totalItems"></strong>) melebihi kapasitas box (<strong x-text="boxCapacity"></strong>). 
            Kurangi jumlah produk atau pilih box yang lebih besar.
          </p>
        </div>
      </div>

      <!-- Step 3: Add Notes -->
      <div class="bg-white rounded-3xl border border-rose-100 shadow-sm p-6">
        <div class="flex items-center mb-4">
          <div class="w-8 h-8 rounded-full bg-[#D26986] text-white flex items-center justify-center font-bold text-sm mr-3">3</div>
          <h2 class="text-xl font-bold text-gray-900">Catatan Tambahan (Opsional)</h2>
        </div>
        <textarea name="notes" 
                  rows="4" 
                  maxlength="500"
                  class="w-full border border-gray-200 rounded-xl p-4 focus:border-[#D26986] focus:ring-2 focus:ring-[#FBE8EE] outline-none transition text-sm"
                  placeholder="Contoh: Tolong pisahkan untuk hadiah terpisah, atau request varian tertentu..."></textarea>
        <p class="text-xs text-gray-500 mt-2">Maksimal 500 karakter</p>
      </div>
    </div>

    <!-- Sidebar Summary -->
    <div class="lg:col-span-1">
      <div class="bg-white rounded-3xl border border-rose-100 shadow-lg p-6 sticky top-24 space-y-4">
        <h3 class="text-lg font-bold text-gray-900 flex items-center">
          <i class="fa-solid fa-receipt mr-2 text-[#D26986]"></i>
          Ringkasan Paket
        </h3>

        <!-- Box Info -->
        <div class="pb-4 border-b border-gray-200">
          <div class="flex justify-between text-sm mb-2">
            <span class="text-gray-600">Ukuran Box:</span>
            <span class="font-bold text-gray-900" x-text="boxName || '-'"></span>
          </div>
          <div class="flex justify-between text-sm mb-2">
            <span class="text-gray-600">Kapasitas:</span>
            <span class="font-bold text-gray-900"><span x-text="boxCapacity || '0'"></span> pcs</span>
          </div>
          <div class="flex justify-between text-sm">
            <span class="text-gray-600">Biaya Box:</span>
            <span class="font-bold text-[#D26986]">Rp <span x-text="formatNumber(boxPrice)"></span></span>
          </div>
        </div>

        <!-- Products Summary -->
        <div class="pb-4 border-b border-gray-200">
          <p class="text-sm font-semibold text-gray-700 mb-2">Produk Dipilih:</p>
          <div class="space-y-2 max-h-48 overflow-y-auto">
            <template x-for="(qty, productId) in selectedProducts" :key="productId">
              <div x-show="qty > 0" class="flex justify-between text-xs bg-[#FBE8EE] rounded-lg px-3 py-2">
                <span class="text-gray-700 font-medium">
                  <span x-text="getProductName(productId)"></span>
                </span>
                <span class="font-bold text-[#D26986]"><span x-text="qty"></span>x</span>
              </div>
            </template>
            <div x-show="totalItems === 0" class="text-xs text-gray-400 italic text-center py-2">Belum ada produk dipilih</div>
          </div>
          <div class="mt-3 pt-2 border-t border-gray-100">
            <div class="flex justify-between text-sm font-bold">
              <span class="text-gray-700">Total Items:</span>
              <span :class="totalItems > boxCapacity ? 'text-red-600' : 'text-[#D26986]'" x-text="totalItems"></span>
            </div>
          </div>
        </div>

        <!-- Total Price Estimate -->
        <div class="bg-gradient-to-br from-[#FBE8EE] to-white rounded-2xl p-4">
          <p class="text-xs text-gray-600 mb-1">Estimasi Biaya Box</p>
          <p class="text-2xl font-extrabold text-[#D26986]">Rp <span x-text="formatNumber(boxPrice)"></span></p>
          <p class="text-xs text-gray-500 mt-2">
            <i class="fa-solid fa-info-circle mr-1"></i>
            Harga produk akan dihitung saat checkout
          </p>
        </div>

        <!-- Pre-order Info -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-3">
          <p class="text-xs text-yellow-800 flex items-start">
            <i class="fa-solid fa-clock mr-2 mt-0.5 flex-shrink-0"></i>
            <span>Paket custom adalah <strong>Pre-Order</strong> dengan waktu persiapan 2-3 hari kerja</span>
          </p>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-3">
          <button type="submit" 
                  :disabled="!selectedBox || totalItems === 0 || totalItems > boxCapacity"
                  :class="{'opacity-50 cursor-not-allowed': !selectedBox || totalItems === 0 || totalItems > boxCapacity}"
                  class="w-full px-6 py-4 bg-[#D26986] hover:bg-[#BD5773] text-white font-bold rounded-xl transition transform active:scale-95 shadow-lg">
            <i class="fa-solid fa-cart-plus mr-2"></i>
            Tambah ke Keranjang
          </button>
          <a href="{{ route('packages.index') }}" 
             class="block w-full px-6 py-3 bg-white border-2 border-gray-200 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition text-center">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Kembali
          </a>
        </div>
      </div>
    </div>
  </form>
</div>

<script>
function customPackageBuilder() {
  const productNames = @json($products->pluck('name', 'id'));
  
  return {
    selectedBox: '',
    boxCapacity: 0,
    boxPrice: 0,
    boxName: '',
    selectedProducts: {},
    totalItems: 0,
    filterCategory: 'all',

    updateBoxCapacity(key, capacity, price) {
      this.boxCapacity = capacity;
      this.boxPrice = price;
      this.boxName = key.charAt(0).toUpperCase() + key.slice(1);
      this.updateTotal();
    },

    incrementProduct(productId, maxStock) {
      if (!this.selectedProducts[productId]) {
        this.selectedProducts[productId] = 0;
      }
      if (this.totalItems < this.boxCapacity && this.selectedProducts[productId] < maxStock) {
        this.selectedProducts[productId]++;
        this.updateTotal();
      }
    },

    decrementProduct(productId) {
      if (this.selectedProducts[productId] && this.selectedProducts[productId] > 0) {
        this.selectedProducts[productId]--;
        this.updateTotal();
      }
    },

    updateTotal() {
      this.totalItems = Object.values(this.selectedProducts).reduce((sum, qty) => sum + (qty || 0), 0);
    },

    formatNumber(num) {
      return new Intl.NumberFormat('id-ID').format(num || 0);
    },

    getProductName(productId) {
      return productNames[productId] || 'Produk #' + productId;
    }
  }
}
</script>
@endsection
