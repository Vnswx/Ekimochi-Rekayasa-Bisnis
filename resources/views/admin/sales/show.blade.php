@extends('layouts.admin')

@section('title', 'Detail Pesanan - Admin Ekimochi')

@section('content')
<div class="py-8">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- Header & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Detail Pesanan #{{ $order->order_number }}</h1>
        <p class="text-sm text-gray-500 mt-1">Informasi lengkap pesanan dan transaksi</p>
      </div>
      <div class="mt-4 sm:mt-0 flex items-center gap-3">
        <a href="{{ route('admin.sales.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition">
          <i class="fa-solid fa-arrow-left mr-2"></i>
          Kembali
        </a>
      </div>
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

    <!-- Order Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
      <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between mb-3">
          <i class="fa-solid fa-shopping-cart text-2xl text-blue-600"></i>
          <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">Status</span>
        </div>
        <h3 class="text-lg font-bold text-gray-900 capitalize">{{ ucfirst($order->status) }}</h3>
        <p class="text-xs text-gray-500 mt-1">Status Pesanan</p>
      </div>

      <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between mb-3">
          <i class="fa-solid fa-credit-card text-2xl text-green-600"></i>
          @if($order->payment && $order->payment->status === 'settlement')
            <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-full">Paid</span>
          @else
            <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2 py-1 rounded-full">Unpaid</span>
          @endif
        </div>
        <h3 class="text-lg font-bold text-gray-900">
          {{ $order->payment ? ucfirst($order->payment->status) : 'N/A' }}
        </h3>
        <p class="text-xs text-gray-500 mt-1">Status Pembayaran</p>
      </div>

      <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between mb-3">
          <i class="fa-solid fa-box text-2xl text-purple-600"></i>
          <span class="text-xs font-semibold text-purple-600 bg-purple-50 px-2 py-1 rounded-full">Items</span>
        </div>
        <h3 class="text-lg font-bold text-gray-900">{{ $order->items->count() }}</h3>
        <p class="text-xs text-gray-500 mt-1">Total Item</p>
      </div>

      <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-5 shadow-lg text-white">
        <div class="flex items-center justify-between mb-3">
          <i class="fa-solid fa-money-bill-wave text-2xl opacity-80"></i>
          <span class="text-xs font-bold bg-white/20 px-2 py-1 rounded-full">Total</span>
        </div>
        <h3 class="text-lg font-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</h3>
        <p class="text-xs text-white/80 mt-1">Total Pembayaran</p>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Main Content -->
      <div class="lg:col-span-2 space-y-6">
        
        <!-- Order Items -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
          <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-bold text-gray-900">
              <i class="fa-solid fa-box mr-2 text-[#D26986]"></i>Item Pesanan
            </h2>
          </div>
          <div class="p-6">
            <div class="space-y-4">
              @foreach($order->items as $item)
              <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                @if($item->product && $item->product->image)
                  <img src="{{ asset('storage/' . $item->product->image) }}" 
                       alt="{{ $item->product_name }}" 
                       class="w-16 h-16 rounded-lg object-cover">
                @else
                  <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-image text-gray-400 text-xl"></i>
                  </div>
                @endif
                <div class="flex-1">
                  <h3 class="text-sm font-bold text-gray-900">{{ $item->product_name }}</h3>
                  <p class="text-xs text-gray-500 mt-1">SKU: {{ $item->product_sku }}</p>
                  <div class="flex items-center gap-3 mt-2 text-xs">
                    <span class="text-gray-600">
                      Rp {{ number_format($item->price, 0, ',', '.') }} × {{ $item->quantity }}
                    </span>
                  </div>
                </div>
                <div class="text-right">
                  <p class="text-sm font-bold text-[#D26986]">
                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                  </p>
                </div>
              </div>
              @endforeach
            </div>

            <!-- Price Summary -->
            <div class="mt-6 pt-6 border-t border-gray-200 space-y-3">
              <div class="flex items-center justify-between text-sm">
                <span class="text-gray-600">Subtotal</span>
                <span class="font-semibold text-gray-900">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
              </div>
              @if($order->shipping_cost > 0)
              <div class="flex items-center justify-between text-sm">
                <span class="text-gray-600">
                  Ongkos Kirim
                  @if($order->delivery_distance)
                    <span class="text-xs text-gray-400">({{ number_format($order->delivery_distance, 1) }} km)</span>
                  @endif
                </span>
                <span class="font-semibold text-gray-900">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
              </div>
              @endif
              <div class="flex items-center justify-between text-lg pt-3 border-t border-gray-200">
                <span class="font-bold text-gray-900">Total</span>
                <span class="font-bold text-[#D26986]">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Payment Information -->
        @if($order->payment)
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
          <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-bold text-gray-900">
              <i class="fa-solid fa-credit-card mr-2 text-[#D26986]"></i>Informasi Pembayaran
            </h2>
          </div>
          <div class="p-6">
            <div class="grid grid-cols-2 gap-4">
              <div>
                <p class="text-xs font-semibold text-gray-500 mb-1">Transaction ID</p>
                <p class="text-sm font-mono text-gray-900">{{ $order->payment->transaction_id }}</p>
              </div>
              <div>
                <p class="text-xs font-semibold text-gray-500 mb-1">Payment Type</p>
                <p class="text-sm text-gray-900 capitalize">{{ $order->payment->payment_type ?? '-' }}</p>
              </div>
              <div>
                <p class="text-xs font-semibold text-gray-500 mb-1">Status</p>
                @if($order->payment->status === 'settlement')
                  <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                    <i class="fa-solid fa-circle-check mr-1"></i> Paid
                  </span>
                @elseif($order->payment->status === 'pending')
                  <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                    <i class="fa-solid fa-clock mr-1"></i> Pending
                  </span>
                @else
                  <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                    {{ ucfirst($order->payment->status) }}
                  </span>
                @endif
              </div>
              <div>
                <p class="text-xs font-semibold text-gray-500 mb-1">Amount</p>
                <p class="text-sm font-bold text-gray-900">Rp {{ number_format($order->payment->amount, 0, ',', '.') }}</p>
              </div>
              @if($order->payment->paid_at)
              <div>
                <p class="text-xs font-semibold text-gray-500 mb-1">Paid At</p>
                <p class="text-sm text-gray-900">{{ $order->payment->paid_at->format('d M Y H:i') }}</p>
              </div>
              @endif
              @if($order->payment->expired_at)
              <div>
                <p class="text-xs font-semibold text-gray-500 mb-1">Expired At</p>
                <p class="text-sm text-gray-900">{{ $order->payment->expired_at->format('d M Y H:i') }}</p>
              </div>
              @endif
            </div>
          </div>
        </div>
        @endif

      </div>

      <!-- Sidebar -->
      <div class="lg:col-span-1 space-y-6">
        
        <!-- Update Status -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
          <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-bold text-gray-900">
              <i class="fa-solid fa-pen-to-square mr-2 text-[#D26986]"></i>Update Status
            </h2>
          </div>
          <div class="p-6">
            <form method="POST" action="{{ route('admin.sales.updateStatus', $order->id) }}">
              @csrf
              @method('PUT')
              
              <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Status Pesanan</label>
                <select name="status" required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#D26986] focus:ring-2 focus:ring-[#FBE8EE] text-sm">
                  <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                  <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                  <option value="ready" {{ $order->status === 'ready' ? 'selected' : '' }}>Ready</option>
                  <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                  <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
              </div>

              <button type="submit" 
                      class="w-full px-4 py-3 bg-[#D26986] hover:bg-[#BD5773] text-white font-bold rounded-xl transition transform active:scale-95">
                <i class="fa-solid fa-check mr-2"></i>Update Status
              </button>
            </form>

            <div class="mt-4 p-3 bg-blue-50 rounded-lg">
              <p class="text-xs text-blue-800">
                <i class="fa-solid fa-info-circle mr-1"></i>
                <strong>Status saat ini:</strong> <span class="capitalize">{{ $order->status }}</span>
              </p>
            </div>
          </div>
        </div>

        <!-- Customer Information -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
          <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-bold text-gray-900">
              <i class="fa-solid fa-user mr-2 text-[#D26986]"></i>Informasi Customer
            </h2>
          </div>
          <div class="p-6 space-y-4">
            <div>
              <p class="text-xs font-semibold text-gray-500 mb-1">Nama</p>
              <p class="text-sm font-semibold text-gray-900">{{ $order->customer_name }}</p>
            </div>
            <div>
              <p class="text-xs font-semibold text-gray-500 mb-1">Email</p>
              <p class="text-sm text-gray-900">{{ $order->customer_email }}</p>
            </div>
            <div>
              <p class="text-xs font-semibold text-gray-500 mb-1">Telepon</p>
              <p class="text-sm text-gray-900">{{ $order->customer_phone }}</p>
            </div>
            @if($order->user)
            <div>
              <p class="text-xs font-semibold text-gray-500 mb-1">User Account</p>
              <p class="text-sm text-gray-900">{{ $order->user->name }}</p>
            </div>
            @endif
          </div>
        </div>

        <!-- Order Details -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
          <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-bold text-gray-900">
              <i class="fa-solid fa-info-circle mr-2 text-[#D26986]"></i>Detail Pesanan
            </h2>
          </div>
          <div class="p-6 space-y-4">
            <div>
              <p class="text-xs font-semibold text-gray-500 mb-1">Order Number</p>
              <p class="text-sm font-mono font-bold text-gray-900">{{ $order->order_number }}</p>
            </div>
            <div>
              <p class="text-xs font-semibold text-gray-500 mb-1">Tipe Pesanan</p>
              @if($order->order_type === 'dine_in')
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                  <i class="fa-solid fa-utensils mr-1"></i> Dine In
                </span>
              @elseif($order->order_type === 'take_away')
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                  <i class="fa-solid fa-bag-shopping mr-1"></i> Take Away
                </span>
              @else
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                  <i class="fa-solid fa-truck mr-1"></i> Delivery
                </span>
              @endif
            </div>
            @if($order->outlet)
            <div>
              <p class="text-xs font-semibold text-gray-500 mb-1">Outlet</p>
              <p class="text-sm text-gray-900">{{ $order->outlet->name }}</p>
              @if($order->outletTable)
                <p class="text-xs text-gray-500 mt-1">Table: {{ $order->outletTable->table_number }}</p>
              @endif
            </div>
            @endif
            @if($order->shipping_address)
            <div>
              <p class="text-xs font-semibold text-gray-500 mb-1">Alamat Pengiriman</p>
              <p class="text-sm text-gray-900">{{ $order->shipping_address }}</p>
            </div>
            @endif
            @if($order->estimated_ready_time)
            <div>
              <p class="text-xs font-semibold text-gray-500 mb-1">Estimasi Siap</p>
              <p class="text-sm text-gray-900">{{ $order->estimated_ready_time->format('d M Y H:i') }}</p>
            </div>
            @endif
            @if($order->notes)
            <div>
              <p class="text-xs font-semibold text-gray-500 mb-1">Catatan</p>
              <p class="text-sm text-gray-900 italic">{{ $order->notes }}</p>
            </div>
            @endif
            <div>
              <p class="text-xs font-semibold text-gray-500 mb-1">Dibuat Pada</p>
              <p class="text-sm text-gray-900">{{ $order->created_at->format('d M Y H:i') }}</p>
              <p class="text-xs text-gray-500 mt-1">{{ $order->created_at->diffForHumans() }}</p>
            </div>
          </div>
        </div>

      </div>
    </div>

  </div>
</div>
@endsection
