@extends('layouts.app')

@section('title', 'Checkout - Ekimochi')

@section('content')
<div class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
  <!-- Breadcrumb -->
  <nav class="text-xs text-gray-500 mb-6">
    <a href="{{ route('home') }}" class="hover:text-[#D26986] transition">Beranda</a>
    <span class="mx-2">/</span>
    <a href="{{ route('cart.index') }}" class="hover:text-[#D26986] transition">Keranjang</a>
    <span class="mx-2">/</span>
    <span class="text-gray-900 font-semibold">Checkout</span>
  </nav>

  <!-- Header -->
  <div class="mb-8">
    <h1 class="text-3xl font-extrabold text-gray-900 flex items-center">
      <i class="fa-solid fa-credit-card text-[#D26986] mr-3"></i>
      Checkout
    </h1>
    <p class="text-sm text-gray-500 mt-1">Pilih metode pembelian dan lengkapi data pesanan</p>
  </div>

  <!-- Alert Messages -->
  @if(session('error'))
  <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg" role="alert">
    <div class="flex items-center">
      <i class="fa-solid fa-circle-exclamation text-xl mr-3"></i>
      <span class="font-medium">{{ session('error') }}</span>
    </div>
  </div>
  @endif

  @if($errors->any())
  <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg" role="alert">
    <div class="flex items-start">
      <i class="fa-solid fa-circle-exclamation text-xl mr-3 mt-0.5"></i>
      <div>
        <p class="font-medium mb-2">Terjadi kesalahan:</p>
        <ul class="list-disc list-inside text-sm space-y-1">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
  @endif

  <form method="POST" action="{{ route('checkout.store') }}" id="checkoutForm" x-data="checkoutData()">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Form Section -->
      <div class="lg:col-span-2 space-y-6">
        
        <!-- Order Type Selection -->
        <div class="bg-white rounded-2xl border border-rose-100 shadow-sm p-6">
          <h2 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b border-gray-200">
            <i class="fa-solid fa-store text-[#D26986] mr-2"></i>
            Metode Pembelian
          </h2>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Dine In -->
            <label class="relative cursor-pointer">
              <input type="radio" name="order_type" value="dine_in" 
                     x-model="orderType"
                     {{ $selectedTable ? 'checked' : '' }}
                     class="peer sr-only">
              <div class="border-2 border-gray-300 rounded-xl p-6 text-center transition peer-checked:border-[#D26986] peer-checked:bg-[#FBE8EE] hover:border-[#D26986]">
                <i class="fa-solid fa-utensils text-4xl text-gray-400 peer-checked:text-[#D26986] mb-3"></i>
                <h3 class="font-bold text-gray-900 mb-1">Dine In</h3>
                <p class="text-xs text-gray-500">Makan di tempat</p>
                <p class="text-xs text-[#D26986] font-semibold mt-2">Gratis Ongkir</p>
              </div>
            </label>

            <!-- Take Away -->
            <label class="relative cursor-pointer">
              <input type="radio" name="order_type" value="take_away" 
                     x-model="orderType"
                     class="peer sr-only">
              <div class="border-2 border-gray-300 rounded-xl p-6 text-center transition peer-checked:border-[#D26986] peer-checked:bg-[#FBE8EE] hover:border-[#D26986]">
                <i class="fa-solid fa-bag-shopping text-4xl text-gray-400 peer-checked:text-[#D26986] mb-3"></i>
                <h3 class="font-bold text-gray-900 mb-1">Take Away</h3>
                <p class="text-xs text-gray-500">Ambil sendiri</p>
                <p class="text-xs text-[#D26986] font-semibold mt-2">Gratis Ongkir</p>
              </div>
            </label>

            <!-- Delivery -->
            <label class="relative cursor-pointer">
              <input type="radio" name="order_type" value="delivery" 
                     x-model="orderType"
                     class="peer sr-only">
              <div class="border-2 border-gray-300 rounded-xl p-6 text-center transition peer-checked:border-[#D26986] peer-checked:bg-[#FBE8EE] hover:border-[#D26986]">
                <i class="fa-solid fa-truck text-4xl text-gray-400 peer-checked:text-[#D26986] mb-3"></i>
                <h3 class="font-bold text-gray-900 mb-1">Delivery</h3>
                <p class="text-xs text-gray-500">Antar ke alamat</p>
                <p class="text-xs text-gray-600 font-semibold mt-2">Rp 15k - 35k</p>
              </div>
            </label>
          </div>

          @error('order_type')
            <p class="mt-3 text-xs text-red-600 flex items-center">
              <i class="fa-solid fa-circle-exclamation mr-1"></i>
              {{ $message }}
            </p>
          @enderror
        </div>

        <!-- Dine In: QR Token (Hidden Input) -->
        <div x-show="orderType === 'dine_in'" x-cloak>
          @if($selectedTable)
          <input type="hidden" name="qr_token" value="{{ $selectedTable->qr_token }}">
          <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
            <div class="flex items-start">
              <i class="fa-solid fa-check-circle text-green-600 text-xl mr-3 mt-0.5"></i>
              <div>
                <p class="font-semibold text-green-900">Meja terdeteksi dari QR Code</p>
                <p class="text-sm text-green-700 mt-1">
                  <strong>{{ $selectedOutlet->name }}</strong> - Meja {{ $selectedTable->table_number }}
                </p>
              </div>
            </div>
          </div>
          @else
          <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg">
            <div class="flex items-start">
              <i class="fa-solid fa-exclamation-triangle text-yellow-600 text-xl mr-3 mt-0.5"></i>
              <div>
                <p class="font-semibold text-yellow-900">QR Code belum di-scan</p>
                <p class="text-sm text-yellow-700 mt-1">
                  Untuk Dine In, mohon scan QR code yang ada di meja Anda
                </p>
              </div>
            </div>
          </div>
          @endif
        </div>

        <!-- Take Away: Outlet Selection -->
        <div x-show="orderType === 'take_away'" x-cloak>
          <div class="bg-white rounded-2xl border border-rose-100 shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b border-gray-200">
              <i class="fa-solid fa-location-dot text-[#D26986] mr-2"></i>
              Pilih Outlet
            </h2>

            <div class="grid grid-cols-1 gap-4">
              @foreach($outlets as $outlet)
              <label class="relative cursor-pointer">
                <input type="radio" name="outlet_id" value="{{ $outlet->id }}" 
                       class="peer sr-only"
                       required>
                <div class="border-2 border-gray-300 rounded-xl p-4 transition peer-checked:border-[#D26986] peer-checked:bg-[#FBE8EE] hover:border-[#D26986]">
                  <div class="flex items-start">
                    <i class="fa-solid fa-store text-2xl text-[#D26986] mr-4 mt-1"></i>
                    <div class="flex-1">
                      <h3 class="font-bold text-gray-900">{{ $outlet->name }}</h3>
                      <p class="text-sm text-gray-600 mt-1">{{ $outlet->address }}</p>
                      <p class="text-sm text-gray-500 mt-1">{{ $outlet->city }} • {{ $outlet->phone }}</p>
                    </div>
                  </div>
                </div>
              </label>
              @endforeach
            </div>

            @error('outlet_id')
              <p class="mt-3 text-xs text-red-600 flex items-center">
                <i class="fa-solid fa-circle-exclamation mr-1"></i>
                {{ $message }}
              </p>
            @enderror
          </div>
        </div>

        <!-- Delivery: Address Input -->
        <div x-show="orderType === 'delivery'" x-cloak>
          <div class="bg-white rounded-2xl border border-rose-100 shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b border-gray-200">
              <i class="fa-solid fa-map-marker-alt text-[#D26986] mr-2"></i>
              Alamat Pengiriman
            </h2>

            <div class="space-y-4">
              <div>
                <label for="shipping_address" class="block text-sm font-bold text-gray-700 mb-2">
                  Alamat Lengkap <span class="text-red-500">*</span>
                </label>
                <textarea id="shipping_address" 
                          name="shipping_address" 
                          rows="4"
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#D26986] focus:ring-2 focus:ring-[#FBE8EE] text-sm resize-none @error('shipping_address') border-red-500 @enderror"
                          placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota, Provinsi, Kode Pos">{{ old('shipping_address') }}</textarea>
                <p class="mt-1 text-xs text-gray-500">
                  <i class="fa-solid fa-info-circle mr-1"></i>
                  Pastikan alamat lengkap dan benar. Jarak maksimal 15 km dari outlet terdekat
                </p>
                @error('shipping_address')
                  <p class="mt-2 text-xs text-red-600 flex items-center">
                    <i class="fa-solid fa-circle-exclamation mr-1"></i>
                    {{ $message }}
                  </p>
                @enderror
              </div>

              <!-- Hidden koordinat (untuk future geolocation) -->
              <input type="hidden" name="latitude" id="latitude">
              <input type="hidden" name="longitude" id="longitude">

              <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-blue-800">
                  <i class="fa-solid fa-info-circle mr-2"></i>
                  <strong>Ongkir dihitung berdasarkan jarak:</strong>
                </p>
                <ul class="text-xs text-blue-700 mt-2 ml-6 space-y-1">
                  <li>• 0-5 km = Rp 15.000</li>
                  <li>• 5-10 km = Rp 25.000</li>
                  <li>• 10-15 km = Rp 35.000</li>
                  <li>• > 15 km = Tidak tersedia</li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <!-- Customer Information -->
        <div class="bg-white rounded-2xl border border-rose-100 shadow-sm p-6">
          <h2 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b border-gray-200">
            <i class="fa-solid fa-user text-[#D26986] mr-2"></i>
            Data Pembeli
          </h2>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Customer Name -->
            <div class="md:col-span-2">
              <label for="customer_name" class="block text-sm font-bold text-gray-700 mb-2">
                Nama Lengkap <span class="text-red-500">*</span>
              </label>
              <input type="text" 
                     id="customer_name" 
                     name="customer_name" 
                     value="{{ old('customer_name', $user->name) }}" 
                     required
                     class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#D26986] focus:ring-2 focus:ring-[#FBE8EE] text-sm @error('customer_name') border-red-500 @enderror"
                     placeholder="Masukkan nama lengkap">
              @error('customer_name')
                <p class="mt-2 text-xs text-red-600 flex items-center">
                  <i class="fa-solid fa-circle-exclamation mr-1"></i>
                  {{ $message }}
                </p>
              @enderror
            </div>

            <!-- Customer Email -->
            <div>
              <label for="customer_email" class="block text-sm font-bold text-gray-700 mb-2">
                Email <span class="text-red-500">*</span>
              </label>
              <input type="email" 
                     id="customer_email" 
                     name="customer_email" 
                     value="{{ old('customer_email', $user->email) }}" 
                     required
                     class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#D26986] focus:ring-2 focus:ring-[#FBE8EE] text-sm @error('customer_email') border-red-500 @enderror"
                     placeholder="email@example.com">
              @error('customer_email')
                <p class="mt-2 text-xs text-red-600 flex items-center">
                  <i class="fa-solid fa-circle-exclamation mr-1"></i>
                  {{ $message }}
                </p>
              @enderror
            </div>

            <!-- Customer Phone -->
            <div>
              <label for="customer_phone" class="block text-sm font-bold text-gray-700 mb-2">
                Nomor HP / WhatsApp <span class="text-red-500">*</span>
              </label>
              <input type="tel" 
                     id="customer_phone" 
                     name="customer_phone" 
                     value="{{ old('customer_phone') }}" 
                     required
                     class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#D26986] focus:ring-2 focus:ring-[#FBE8EE] text-sm @error('customer_phone') border-red-500 @enderror"
                     placeholder="08123456789">
              @error('customer_phone')
                <p class="mt-2 text-xs text-red-600 flex items-center">
                  <i class="fa-solid fa-circle-exclamation mr-1"></i>
                  {{ $message }}
                </p>
              @enderror
            </div>
          </div>
        </div>

        <!-- Notes -->
        <div class="bg-white rounded-2xl border border-rose-100 shadow-sm p-6">
          <h2 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b border-gray-200">
            <i class="fa-solid fa-note-sticky text-[#D26986] mr-2"></i>
            Catatan <span class="text-gray-400 text-xs font-normal">(Opsional)</span>
          </h2>

          <textarea id="notes" 
                    name="notes" 
                    rows="3"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#D26986] focus:ring-2 focus:ring-[#FBE8EE] text-sm resize-none @error('notes') border-red-500 @enderror"
                    placeholder="Contoh: Mohon packing bubble wrap ekstra, Kirim sebelum jam 12 siang">{{ old('notes') }}</textarea>
          @error('notes')
            <p class="mt-2 text-xs text-red-600 flex items-center">
              <i class="fa-solid fa-circle-exclamation mr-1"></i>
              {{ $message }}
            </p>
          @enderror
        </div>

      </div>

      <!-- Order Summary Sidebar -->
      <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl border border-rose-100 shadow-sm p-6 sticky top-24">
          <h2 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b border-gray-200">
            <i class="fa-solid fa-receipt text-[#D26986] mr-2"></i>
            Ringkasan Pesanan
          </h2>

          <!-- Order Items -->
          <div class="space-y-4 mb-6 max-h-64 overflow-y-auto">
            @foreach($cartItems as $item)
            <div class="flex items-start space-x-3 pb-4 border-b border-gray-100">
              <div class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden bg-gradient-to-br from-[#FBE8EE] to-white border border-rose-100">
                @if($item['product']->image)
                  <img src="{{ asset('storage/' . $item['product']->image) }}" 
                       alt="{{ $item['product']->name }}" 
                       class="w-full h-full object-cover">
                @else
                  <div class="w-full h-full flex items-center justify-center text-2xl">🍡</div>
                @endif
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-gray-900 truncate">{{ $item['product']->name }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $item['quantity'] }} x Rp {{ number_format($item['product']->price, 0, ',', '.') }}</p>
                <p class="text-sm font-bold text-[#D26986] mt-1">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
              </div>
            </div>
            @endforeach

            @if(!empty($customPackages))
            @foreach($customPackages as $package)
            <div class="flex items-start space-x-3 pb-4 border-b border-gray-100 bg-yellow-50 rounded-lg p-3">
              <div class="flex-shrink-0 w-16 h-16 rounded-lg bg-[#D26986] flex items-center justify-center">
                <i class="fa-solid fa-box text-white text-2xl"></i>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-gray-900">{{ $package['box_name'] }}</p>
                <p class="text-xs text-yellow-600 mt-1">
                  <i class="fa-solid fa-clock mr-1"></i>
                  Pre-Order {{ $package['preorder_days'] }} hari | {{ $package['total_items'] }} pcs
                </p>
                <p class="text-sm font-bold text-[#D26986] mt-1">Rp {{ number_format($package['total_price'], 0, ',', '.') }}</p>
              </div>
            </div>
            @endforeach
            @endif
          </div>

          <!-- Price Summary -->
          <div class="space-y-3 mb-6">
            <div class="flex items-center justify-between text-sm">
              <span class="text-gray-600">Subtotal Produk</span>
              <span class="font-semibold text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>

            <div class="flex items-center justify-between text-sm" x-show="orderType === 'delivery'">
              <span class="text-gray-600">Biaya Pengiriman</span>
              <span class="font-semibold text-gray-900" x-text="'Dihitung saat checkout'"></span>
            </div>

            <div class="flex items-center justify-between text-sm" x-show="orderType === 'dine_in' || orderType === 'take_away'">
              <span class="text-gray-600">Biaya Pengiriman</span>
              <span class="font-semibold text-green-600">Gratis</span>
            </div>

            <div class="pt-3 border-t border-gray-200">
              <div class="flex items-center justify-between">
                <span class="text-base font-bold text-gray-900">Total Pembayaran</span>
                <span class="text-2xl font-extrabold text-[#D26986]">Rp {{ number_format($total, 0, ',', '.') }}</span>
              </div>
            </div>
          </div>

          <!-- Submit Button -->
          <button type="submit" class="w-full bg-[#D26986] hover:bg-[#BD5773] text-white font-bold py-4 rounded-xl transition transform active:scale-95 shadow-lg">
            <i class="fa-solid fa-credit-card mr-2"></i>
            Lanjut ke Pembayaran
          </button>

          <!-- Back to Cart -->
          <a href="{{ route('cart.index') }}" class="block w-full text-center text-gray-600 hover:text-[#D26986] font-semibold py-3 text-sm transition mt-3">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Kembali ke Keranjang
          </a>

          <!-- Security Notice -->
          <div class="mt-6 pt-6 border-t border-gray-200">
            <div class="flex items-start space-x-2 text-xs text-gray-500">
              <i class="fa-solid fa-shield-halved text-green-600 mt-0.5"></i>
              <p>Transaksi Anda aman dan terlindungi. Data pribadi Anda dienkripsi.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>

<script>
function checkoutData() {
  return {
    orderType: '{{ $selectedTable ? "dine_in" : (old("order_type") ?? "delivery") }}'
  }
}
</script>

<style>
[x-cloak] { display: none !important; }
</style>
@endsection
