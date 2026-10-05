@extends('layouts.admin')

@section('title', 'Kelola Penjualan - Admin Ekimochi')

@section('content')
<div class="py-8">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- Header & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Kelola Penjualan</h1>
        <p class="text-sm text-gray-500 mt-1">Manajemen pesanan dan transaksi penjualan</p>
      </div>
      <div class="mt-4 sm:mt-0 flex items-center gap-3">
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition">
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

    <!-- Statistics Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
      <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-5 text-white shadow-lg">
        <div class="flex items-center justify-between mb-3">
          <i class="fa-solid fa-money-bill-wave text-3xl opacity-80"></i>
          <span class="text-xs font-bold bg-white/20 px-2 py-1 rounded-full">Total</span>
        </div>
        <h3 class="text-2xl font-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
        <p class="text-xs text-white/80 mt-1">Total Pendapatan</p>
      </div>

      <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between mb-3">
          <i class="fa-solid fa-shopping-cart text-3xl text-blue-600"></i>
          <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">Pesanan</span>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ $totalOrders }}</h3>
        <p class="text-xs text-gray-500 mt-1">Total Pesanan</p>
      </div>

      <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between mb-3">
          <i class="fa-solid fa-clock text-3xl text-amber-600"></i>
          <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2 py-1 rounded-full">Pending</span>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ $pendingOrders }}</h3>
        <p class="text-xs text-gray-500 mt-1">Pesanan Pending</p>
      </div>

      <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between mb-3">
          <i class="fa-solid fa-check-circle text-3xl text-green-600"></i>
          <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-full">Selesai</span>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ $completedOrders }}</h3>
        <p class="text-xs text-gray-500 mt-1">Pesanan Selesai</p>
      </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 mb-6">
      <form method="GET" action="{{ route('admin.sales.index') }}">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
          <!-- Search -->
          <div class="md:col-span-2">
            <label class="block text-xs font-semibold text-gray-700 mb-2">
              <i class="fa-solid fa-magnifying-glass mr-1"></i> Cari Pesanan
            </label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor order, nama, email..." 
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:border-[#D26986] focus:ring-2 focus:ring-[#FBE8EE] text-sm">
          </div>

          <!-- Date From -->
          <div>
            <label class="block text-xs font-semibold text-gray-700 mb-2">
              <i class="fa-solid fa-calendar mr-1"></i> Dari Tanggal
            </label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" 
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:border-[#D26986] focus:ring-2 focus:ring-[#FBE8EE] text-sm">
          </div>

          <!-- Date To -->
          <div>
            <label class="block text-xs font-semibold text-gray-700 mb-2">
              <i class="fa-solid fa-calendar mr-1"></i> Sampai Tanggal
            </label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" 
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:border-[#D26986] focus:ring-2 focus:ring-[#FBE8EE] text-sm">
          </div>

          <!-- Filter Buttons -->
          <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 px-4 py-2.5 bg-[#D26986] hover:bg-[#BD5773] text-white font-semibold rounded-xl transition text-sm">
              <i class="fa-solid fa-filter mr-1"></i> Filter
            </button>
            <a href="{{ route('admin.sales.index') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition text-sm">
              <i class="fa-solid fa-rotate-right"></i>
            </a>
          </div>
        </div>

        <!-- Secondary Filters -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
          <!-- Status Filter -->
          <div>
            <label class="block text-xs font-semibold text-gray-700 mb-2">
              <i class="fa-solid fa-list-check mr-1"></i> Status Pesanan
            </label>
            <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:border-[#D26986] focus:ring-2 focus:ring-[#FBE8EE] text-sm">
              <option value="">Semua Status</option>
              <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
              <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
              <option value="ready" {{ request('status') === 'ready' ? 'selected' : '' }}>Ready</option>
              <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
              <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
          </div>

          <!-- Payment Status Filter -->
          <div>
            <label class="block text-xs font-semibold text-gray-700 mb-2">
              <i class="fa-solid fa-credit-card mr-1"></i> Status Pembayaran
            </label>
            <select name="payment_status" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:border-[#D26986] focus:ring-2 focus:ring-[#FBE8EE] text-sm">
              <option value="">Semua Status</option>
              <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
              <option value="settlement" {{ request('payment_status') === 'settlement' ? 'selected' : '' }}>Paid</option>
              <option value="expire" {{ request('payment_status') === 'expire' ? 'selected' : '' }}>Expired</option>
              <option value="cancel" {{ request('payment_status') === 'cancel' ? 'selected' : '' }}>Cancelled</option>
            </select>
          </div>

          <!-- Order Type Filter -->
          <div>
            <label class="block text-xs font-semibold text-gray-700 mb-2">
              <i class="fa-solid fa-truck mr-1"></i> Tipe Pesanan
            </label>
            <select name="order_type" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:border-[#D26986] focus:ring-2 focus:ring-[#FBE8EE] text-sm">
              <option value="">Semua Tipe</option>
              <option value="dine_in" {{ request('order_type') === 'dine_in' ? 'selected' : '' }}>Dine In</option>
              <option value="take_away" {{ request('order_type') === 'take_away' ? 'selected' : '' }}>Take Away</option>
              <option value="delivery" {{ request('order_type') === 'delivery' ? 'selected' : '' }}>Delivery</option>
            </select>
          </div>
        </div>
      </form>
    </div>

    <!-- Orders Table Card -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
      
      <!-- Table Header Info -->
      <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <div class="flex items-center justify-between">
          <span class="text-sm text-gray-600">
            Menampilkan <strong class="text-gray-900">{{ $orders->count() }}</strong> dari <strong class="text-gray-900">{{ $orders->total() }}</strong> pesanan
          </span>
          <span class="text-xs text-gray-500">
            <i class="fa-solid fa-clock mr-1"></i> Terakhir diperbarui: {{ now()->format('d M Y H:i') }}
          </span>
        </div>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
              <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Order</th>
              <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Customer</th>
              <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Tipe</th>
              <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Items</th>
              <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Total</th>
              <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Pembayaran</th>
              <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
              <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Tanggal</th>
              <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @forelse($orders as $order)
            <tr class="hover:bg-gray-50 transition">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-bold text-gray-900">{{ $order->order_number }}</div>
                @if($order->outlet)
                  <div class="text-xs text-gray-500 mt-1">
                    <i class="fa-solid fa-store mr-1"></i>{{ $order->outlet->name }}
                  </div>
                @endif
              </td>
              <td class="px-6 py-4">
                <div class="text-sm font-semibold text-gray-900">{{ $order->customer_name }}</div>
                <div class="text-xs text-gray-500">{{ $order->customer_phone }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
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
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                <i class="fa-solid fa-box mr-1 text-gray-400"></i>{{ $order->items->count() }} item
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-bold text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                @if($order->shipping_cost > 0)
                  <div class="text-xs text-gray-500">+ Rp {{ number_format($order->shipping_cost, 0, ',', '.') }} ongkir</div>
                @endif
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                @if($order->payment)
                  @if($order->payment->status === 'settlement')
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                      <i class="fa-solid fa-circle-check mr-1"></i> Dibayar
                    </span>
                  @elseif($order->payment->status === 'pending')
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                      <i class="fa-solid fa-clock mr-1"></i> Pending
                    </span>
                  @elseif($order->payment->status === 'expire')
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                      <i class="fa-solid fa-times-circle mr-1"></i> Expired
                    </span>
                  @else
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                      {{ ucfirst($order->payment->status) }}
                    </span>
                  @endif
                @else
                  <span class="text-xs text-gray-400">-</span>
                @endif
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                @if($order->status === 'pending')
                  <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">Pending</span>
                @elseif($order->status === 'processing')
                  <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">Processing</span>
                @elseif($order->status === 'ready')
                  <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">Ready</span>
                @elseif($order->status === 'completed')
                  <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Completed</span>
                @else
                  <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Cancelled</span>
                @endif
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ $order->created_at->format('d M Y') }}<br>
                <span class="text-xs">{{ $order->created_at->format('H:i') }}</span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-center">
                <a href="{{ route('admin.sales.show', $order->id) }}" 
                   class="inline-flex items-center px-3 py-1.5 bg-[#D26986] hover:bg-[#BD5773] text-white text-xs font-semibold rounded-lg transition">
                  <i class="fa-solid fa-eye mr-1"></i> Detail
                </a>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="9" class="px-6 py-12 text-center">
                <div class="text-gray-500">
                  <i class="fa-solid fa-inbox text-5xl mb-4 text-gray-300"></i>
                  <p class="text-lg font-semibold mb-1">Tidak ada pesanan ditemukan</p>
                  <p class="text-sm">Coba ubah filter atau parameter pencarian</p>
                </div>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      @if($orders->hasPages())
      <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        {{ $orders->links() }}
      </div>
      @endif
    </div>

  </div>
</div>
@endsection
