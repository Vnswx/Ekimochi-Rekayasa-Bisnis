@extends('layouts.admin')

@section('title', 'CRM - Admin Ekimochi')

@section('content')
<div class="py-8">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
      <div>
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Kelola CRM</h1>
        <p class="text-sm text-gray-500 mt-1">Customer Relationship Management — kelola pelanggan dan riwayat belanja</p>
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
            <p class="text-sm font-medium text-gray-500">Total Pelanggan</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalCustomers, 0, ',', '.') }}</p>
          </div>
          <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center">
            <i class="fa-solid fa-users text-2xl text-blue-600"></i>
          </div>
        </div>
      </div>

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
            <p class="text-sm font-medium text-gray-500">Total Pendapatan</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
          </div>
          <div class="w-14 h-14 bg-emerald-100 rounded-full flex items-center justify-center">
            <i class="fa-solid fa-sack-dollar text-2xl text-emerald-600"></i>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-500">Pelanggan Setia</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($repeatCustomers, 0, ',', '.') }}</p>
          </div>
          <div class="w-14 h-14 bg-rose-100 rounded-full flex items-center justify-center">
            <i class="fa-solid fa-heart text-2xl text-rose-600"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Search -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 mb-6">
      <form action="{{ route('admin.crm.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
          <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
          <input type="text" name="search" value="{{ $search }}"
                 placeholder="Cari nama, email, atau username pelanggan..."
                 class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-[#D26986] focus:border-[#D26986] outline-none transition">
        </div>
        <div class="flex gap-2">
          <button type="submit" class="inline-flex items-center justify-center px-5 py-3 bg-[#D26986] hover:bg-[#BD5773] text-white text-sm font-semibold rounded-xl transition">
            <i class="fa-solid fa-magnifying-glass mr-2"></i>
            Cari
          </button>
          @if($search)
          <a href="{{ route('admin.crm.index') }}" class="inline-flex items-center justify-center px-5 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition">
            <i class="fa-solid fa-rotate-left mr-2"></i>
            Reset
          </a>
          @endif
        </div>
      </form>
    </div>

    <!-- Customers Table - Desktop View -->
    <div class="hidden lg:block bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Pelanggan</th>
              <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Kontak</th>
              <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Pesanan</th>
              <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Total Belanja</th>
              <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Terakhir Belanja</th>
              <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @forelse($customers as $customer)
            <tr class="hover:bg-gray-50 transition">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  @if($customer->profile_photo)
                    <img src="{{ $customer->avatar }}" alt="{{ $customer->name }}" class="w-10 h-10 rounded-full object-cover">
                  @else
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#D26986] to-[#BD5773] flex items-center justify-center text-white font-bold">
                      {{ $customer->initial }}
                    </div>
                  @endif
                  <div class="ml-4">
                    <div class="text-sm font-semibold text-gray-900">{{ $customer->name }}</div>
                    <div class="text-xs text-gray-500">@{{ $customer->username }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-700">{{ $customer->email ?? '-' }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-700 font-medium">{{ $customer->orders_count }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-bold text-gray-900">Rp {{ number_format($customer->total_spent ?? 0, 0, ',', '.') }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-700">{{ $customer->last_order_at ? \Carbon\Carbon::parse($customer->last_order_at)->format('d M Y') : '-' }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                @if($customer->orders_count >= 2)
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                    <i class="fa-solid fa-heart mr-1"></i>
                    Pelanggan Setia
                  </span>
                @elseif($customer->orders_count === 1)
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                    <i class="fa-solid fa-cart-shopping mr-1"></i>
                    Sudah Belanja
                  </span>
                @else
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                    <i class="fa-solid fa-user-plus mr-1"></i>
                    Baru
                  </span>
                @endif
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="6" class="px-6 py-12 text-center">
                <div class="flex flex-col items-center justify-center text-gray-400">
                  <i class="fa-solid fa-handshake text-5xl mb-4"></i>
                  <p class="text-lg font-medium">Tidak ada pelanggan</p>
                  <p class="text-sm mt-1">{{ $search ? 'Hasil pencarian tidak ditemukan' : 'Belum ada pelanggan yang terdaftar' }}</p>
                </div>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      @if($customers->hasPages())
      <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
        {{ $customers->links() }}
      </div>
      @endif
    </div>

    <!-- Customers Cards - Mobile View -->
    <div class="lg:hidden space-y-4">
      @forelse($customers as $customer)
      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4">
        <div class="flex items-center gap-3 mb-4">
          @if($customer->profile_photo)
            <img src="{{ $customer->avatar }}" alt="{{ $customer->name }}" class="w-14 h-14 rounded-full object-cover">
          @else
            <div class="w-14 h-14 rounded-full bg-gradient-to-br from-[#D26986] to-[#BD5773] flex items-center justify-center text-xl font-bold text-white">
              {{ $customer->initial }}
            </div>
          @endif
          <div class="flex-1">
            <h3 class="text-base font-bold text-gray-900">{{ $customer->name }}</h3>
            <p class="text-sm text-gray-500">@{{ $customer->username }}</p>
          </div>
          @if($customer->orders_count >= 2)
            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
              <i class="fa-solid fa-heart mr-1"></i>
              Setia
            </span>
          @elseif($customer->orders_count === 1)
            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
              <i class="fa-solid fa-cart-shopping mr-1"></i>
              Aktif
            </span>
          @else
            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
              <i class="fa-solid fa-user-plus mr-1"></i>
              Baru
            </span>
          @endif
        </div>

        <div class="space-y-2 mb-4 pb-4 border-b border-gray-200">
          <div class="flex items-start gap-2">
            <i class="fa-solid fa-envelope text-gray-400 text-sm mt-0.5"></i>
            <div class="flex-1">
              <p class="text-xs text-gray-500">Email</p>
              <p class="text-sm text-gray-700">{{ $customer->email ?? 'Belum diisi' }}</p>
            </div>
          </div>
          <div class="flex items-start gap-2">
            <i class="fa-solid fa-receipt text-gray-400 text-sm mt-0.5"></i>
            <div class="flex-1">
              <p class="text-xs text-gray-500">Pesanan / Total Belanja</p>
              <p class="text-sm text-gray-700">{{ $customer->orders_count }} pesanan · Rp {{ number_format($customer->total_spent ?? 0, 0, ',', '.') }}</p>
            </div>
          </div>
          <div class="flex items-start gap-2">
            <i class="fa-solid fa-calendar text-gray-400 text-sm mt-0.5"></i>
            <div class="flex-1">
              <p class="text-xs text-gray-500">Terakhir Belanja</p>
              <p class="text-sm text-gray-700">{{ $customer->last_order_at ? \Carbon\Carbon::parse($customer->last_order_at)->format('d M Y') : '-' }}</p>
            </div>
          </div>
        </div>
      </div>
      @empty
      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8">
        <div class="flex flex-col items-center justify-center text-gray-400">
          <i class="fa-solid fa-handshake text-5xl mb-4"></i>
          <p class="text-lg font-medium">Tidak ada pelanggan</p>
          <p class="text-sm mt-1">{{ $search ? 'Hasil pencarian tidak ditemukan' : 'Belum ada pelanggan yang terdaftar' }}</p>
        </div>
      </div>
      @endforelse

      <!-- Pagination Mobile -->
      @if($customers->hasPages())
      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4">
        {{ $customers->links() }}
      </div>
      @endif
    </div>

  </div>
</div>
@endsection
