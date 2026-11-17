@extends('layouts.admin')

@section('title', 'Pesanan & Pengiriman - Admin Ekimochi')

@section('content')
<div class="py-8">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
      <div>
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Pesanan & Pengiriman</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola pesanan dan status pengiriman secara langsung</p>
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

    @if(session('error'))
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg" role="alert">
      <div class="flex items-center">
        <i class="fa-solid fa-circle-exclamation text-xl mr-3"></i>
        <span class="font-medium">{{ session('error') }}</span>
      </div>
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-500">Total Pesanan</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalOrders, 0, ',', '.') }}</p>
          </div>
          <div class="w-14 h-14 bg-indigo-100 rounded-full flex items-center justify-center">
            <i class="fa-solid fa-receipt text-2xl text-indigo-600"></i>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-500">Menunggu</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($pendingOrders, 0, ',', '.') }}</p>
          </div>
          <div class="w-14 h-14 bg-amber-100 rounded-full flex items-center justify-center">
            <i class="fa-solid fa-hourglass-half text-2xl text-amber-600"></i>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-500">Diproses / Siap</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($processingOrders, 0, ',', '.') }}</p>
          </div>
          <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center">
            <i class="fa-solid fa-gears text-2xl text-blue-600"></i>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-500">Pengiriman</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($deliveryOrders, 0, ',', '.') }}</p>
          </div>
          <div class="w-14 h-14 bg-rose-100 rounded-full flex items-center justify-center">
            <i class="fa-solid fa-truck-fast text-2xl text-rose-600"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 mb-6">
      <form action="{{ route('admin.orderSettings.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3">
        <div class="relative lg:col-span-4">
          <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
          <input type="text" name="search" value="{{ request('search') }}"
                 placeholder="Cari nomor pesanan atau nama pelanggan..."
                 class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-[#D26986] focus:border-[#D26986] outline-none transition">
        </div>
        <select name="status" class="lg:col-span-2 px-4 py-3 border border-gray-300 rounded-xl text-sm bg-white focus:ring-2 focus:ring-[#D26986] focus:border-[#D26986] outline-none transition">
          <option value="">Semua Status</option>
          <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
          <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Diproses</option>
          <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Siap</option>
          <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
          <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
        </select>
        <select name="order_type" class="lg:col-span-2 px-4 py-3 border border-gray-300 rounded-xl text-sm bg-white focus:ring-2 focus:ring-[#D26986] focus:border-[#D26986] outline-none transition">
          <option value="">Semua Tipe</option>
          <option value="dine_in" {{ request('order_type') == 'dine_in' ? 'selected' : '' }}>Makan di Tempat</option>
          <option value="take_away" {{ request('order_type') == 'take_away' ? 'selected' : '' }}>Bawa Pulang</option>
          <option value="delivery" {{ request('order_type') == 'delivery' ? 'selected' : '' }}>Pengiriman</option>
        </select>
        <select name="payment_status" class="lg:col-span-2 px-4 py-3 border border-gray-300 rounded-xl text-sm bg-white focus:ring-2 focus:ring-[#D26986] focus:border-[#D26986] outline-none transition">
          <option value="">Semua Pembayaran</option>
          <option value="settlement" {{ request('payment_status') == 'settlement' ? 'selected' : '' }}>Lunas</option>
          <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Belum Bayar</option>
        </select>
        <button type="submit" class="lg:col-span-2 inline-flex items-center justify-center px-5 py-3 bg-[#D26986] hover:bg-[#BD5773] text-white text-sm font-semibold rounded-xl transition">
          <i class="fa-solid fa-filter mr-2"></i>
          Terapkan
        </button>
      </form>
    </div>

    <!-- Orders Table - Desktop View -->
    <div class="hidden lg:block bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Pesanan</th>
              <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Pelanggan</th>
              <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Tipe</th>
              <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Pembayaran</th>
              <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Total</th>
              <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
              <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @forelse($orders as $order)
            <tr class="hover:bg-gray-50 transition">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-semibold text-gray-900">{{ $order->order_number }}</div>
                <div class="text-xs text-gray-500 mt-0.5">{{ $order->created_at->format('d M Y H:i') }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ $order->customer_name }}</div>
                <div class="text-xs text-gray-500 mt-0.5">{{ $order->customer_phone }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                @if($order->order_type === 'delivery')
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                    <i class="fa-solid fa-truck mr-1"></i>
                    Pengiriman
                  </span>
                @elseif($order->order_type === 'take_away')
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                    <i class="fa-solid fa-bag-shopping mr-1"></i>
                    Bawa Pulang
                  </span>
                @else
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-pink-100 text-pink-800">
                    <i class="fa-solid fa-utensils mr-1"></i>
                    Makan di Tempat
                  </span>
                @endif
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                @if($order->payment && $order->payment->status === 'settlement')
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                    <i class="fa-solid fa-circle-check mr-1"></i>
                    Lunas
                  </span>
                @elseif($order->payment && $order->payment->status === 'pending')
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                    <i class="fa-solid fa-clock mr-1"></i>
                    Menunggu Bayar
                  </span>
                @else
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                    <i class="fa-solid fa-minus mr-1"></i>
                    -
                  </span>
                @endif
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-bold text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                @php
                  $statusBadge = [
                    'pending'    => ['bg-amber-100 text-amber-800', 'fa-hourglass-half', 'Menunggu'],
                    'processing' => ['bg-blue-100 text-blue-800', 'fa-gears', 'Diproses'],
                    'ready'      => ['bg-indigo-100 text-indigo-800', 'fa-box-open', 'Siap'],
                    'completed'  => ['bg-emerald-100 text-emerald-800', 'fa-circle-check', 'Selesai'],
                    'cancelled'  => ['bg-red-100 text-red-800', 'fa-ban', 'Dibatalkan'],
                  ][$order->status] ?? ['bg-gray-100 text-gray-600', 'fa-circle-question', $order->status];
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusBadge[0] }}">
                  <i class="fa-solid {{ $statusBadge[1] }} mr-1"></i>
                  {{ $statusBadge[2] }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('admin.sales.show', $order) }}" class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-lg transition" title="Lihat detail">
                    <i class="fa-solid fa-eye mr-1"></i>
                    Detail
                  </a>
                  <form action="{{ route('admin.orderSettings.updateStatus', $order) }}" method="POST" class="flex items-center gap-1">
                    @csrf
                    @method('PUT')
                    <select name="status" class="text-xs border border-gray-300 rounded-lg py-2 px-2 bg-white focus:ring-1 focus:ring-[#D26986] outline-none transition">
                      @foreach(['pending' => 'Menunggu', 'processing' => 'Diproses', 'ready' => 'Siap', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'] as $val => $label)
                      <option value="{{ $val }}" {{ $order->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                      @endforeach
                    </select>
                    <button type="submit" class="p-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-xs transition" title="Simpan status">
                      <i class="fa-solid fa-check"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="7" class="px-6 py-12 text-center">
                <div class="flex flex-col items-center justify-center text-gray-400">
                  <i class="fa-solid fa-truck-fast text-5xl mb-4"></i>
                  <p class="text-lg font-medium">Tidak ada pesanan</p>
                  <p class="text-sm mt-1">Belum ada pesanan yang sesuai filter</p>
                </div>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      @if($orders->hasPages())
      <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
        {{ $orders->links() }}
      </div>
      @endif
    </div>

    <!-- Orders Cards - Mobile View -->
    <div class="lg:hidden space-y-4">
      @forelse($orders as $order)
      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4">
        <div class="flex items-start justify-between gap-3 mb-3">
          <div>
            <div class="text-sm font-bold text-gray-900">{{ $order->order_number }}</div>
            <div class="text-xs text-gray-500 mt-0.5">{{ $order->created_at->format('d M Y H:i') }}</div>
          </div>
          @php
            $statusBadge = [
              'pending'    => ['bg-amber-100 text-amber-800', 'fa-hourglass-half', 'Menunggu'],
              'processing' => ['bg-blue-100 text-blue-800', 'fa-gears', 'Diproses'],
              'ready'      => ['bg-indigo-100 text-indigo-800', 'fa-box-open', 'Siap'],
              'completed'  => ['bg-emerald-100 text-emerald-800', 'fa-circle-check', 'Selesai'],
              'cancelled'  => ['bg-red-100 text-red-800', 'fa-ban', 'Dibatalkan'],
            ][$order->status] ?? ['bg-gray-100 text-gray-600', 'fa-circle-question', $order->status];
          @endphp
          <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusBadge[0] }}">
            <i class="fa-solid {{ $statusBadge[1] }} mr-1"></i>
            {{ $statusBadge[2] }}
          </span>
        </div>

        <div class="space-y-2 mb-4 pb-4 border-b border-gray-200">
          <div class="flex items-start gap-2">
            <i class="fa-solid fa-user text-gray-400 text-sm mt-0.5"></i>
            <div class="flex-1">
              <p class="text-sm font-medium text-gray-900">{{ $order->customer_name }}</p>
              <p class="text-xs text-gray-500">{{ $order->customer_phone }}</p>
            </div>
          </div>
          <div class="flex items-start gap-2">
            <i class="fa-solid fa-tag text-gray-400 text-sm mt-0.5"></i>
            <div class="flex-1 flex items-center gap-2">
              @if($order->order_type === 'delivery')
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                  <i class="fa-solid fa-truck mr-1"></i>
                  Pengiriman
                </span>
              @elseif($order->order_type === 'take_away')
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                  <i class="fa-solid fa-bag-shopping mr-1"></i>
                  Bawa Pulang
                </span>
              @else
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-pink-100 text-pink-800">
                  <i class="fa-solid fa-utensils mr-1"></i>
                  Makan di Tempat
                </span>
              @endif
              <span class="text-xs text-gray-500">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
          </div>
          <div class="flex items-start gap-2">
            <i class="fa-solid fa-credit-card text-gray-400 text-sm mt-0.5"></i>
            <div class="flex-1">
              @if($order->payment && $order->payment->status === 'settlement')
                <span class="text-xs font-semibold text-emerald-700">Pembayaran Lunas</span>
              @elseif($order->payment && $order->payment->status === 'pending')
                <span class="text-xs font-semibold text-amber-700">Menunggu Pembayaran</span>
              @else
                <span class="text-xs text-gray-500">Belum ada pembayaran</span>
              @endif
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-2">
          <a href="{{ route('admin.sales.show', $order) }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition">
            <i class="fa-solid fa-eye mr-2"></i>
            Detail
          </a>
          <form action="{{ route('admin.orderSettings.updateStatus', $order) }}" method="POST" class="flex-1 flex items-center gap-2">
            @csrf
            @method('PUT')
            <select name="status" class="flex-1 text-sm border border-gray-300 rounded-xl py-2.5 px-3 bg-white focus:ring-1 focus:ring-[#D26986] outline-none transition">
              @foreach(['pending' => 'Menunggu', 'processing' => 'Diproses', 'ready' => 'Siap', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'] as $val => $label)
              <option value="{{ $val }}" {{ $order->status === $val ? 'selected' : '' }}>{{ $label }}</option>
              @endforeach
            </select>
            <button type="submit" class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold rounded-xl transition">
              <i class="fa-solid fa-check"></i>
            </button>
          </form>
        </div>
      </div>
      @empty
      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8">
        <div class="flex flex-col items-center justify-center text-gray-400">
          <i class="fa-solid fa-truck-fast text-5xl mb-4"></i>
          <p class="text-lg font-medium">Tidak ada pesanan</p>
          <p class="text-sm mt-1">Belum ada pesanan yang sesuai filter</p>
        </div>
      </div>
      @endforelse

      <!-- Pagination Mobile -->
      @if($orders->hasPages())
      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4">
        {{ $orders->links() }}
      </div>
      @endif
    </div>

  </div>
</div>
@endsection