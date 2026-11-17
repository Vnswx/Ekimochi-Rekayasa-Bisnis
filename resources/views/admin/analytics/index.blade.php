@extends('layouts.admin')

@section('title', 'Analitik & Statistik - Admin Ekimochi')

@section('content')
<div class="py-8">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
      <div>
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Analitik & Statistik</h1>
        <p class="text-sm text-gray-500 mt-1">Pantau performa penjualan, pesanan, dan pelanggan secara langsung</p>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-500">Total Pendapatan</p>
            <p class="text-2xl lg:text-3xl font-bold text-gray-900 mt-2">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500 mt-1">Pesanan lunas (settlement)</p>
          </div>
          <div class="w-14 h-14 bg-emerald-100 rounded-full flex items-center justify-center">
            <i class="fa-solid fa-sack-dollar text-2xl text-emerald-600"></i>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-500">Total Pesanan</p>
            <p class="text-2xl lg:text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalOrders, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500 mt-1">Semua status</p>
          </div>
          <div class="w-14 h-14 bg-indigo-100 rounded-full flex items-center justify-center">
            <i class="fa-solid fa-receipt text-2xl text-indigo-600"></i>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-500">Rata-rata / Pesanan</p>
            <p class="text-2xl lg:text-3xl font-bold text-gray-900 mt-2">Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500 mt-1">Total pendapatan / total pesanan</p>
          </div>
          <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center">
            <i class="fa-solid fa-chart-column text-2xl text-blue-600"></i>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-500">Total Pelanggan</p>
            <p class="text-2xl lg:text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalCustomers, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500 mt-1">Akun terdaftar</p>
          </div>
          <div class="w-14 h-14 bg-rose-100 rounded-full flex items-center justify-center">
            <i class="fa-solid fa-users text-2xl text-rose-600"></i>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-500">Pesanan Hari Ini</p>
            <p class="text-2xl lg:text-3xl font-bold text-gray-900 mt-2">{{ number_format($todayOrders, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ now()->format('d M Y') }}</p>
          </div>
          <div class="w-14 h-14 bg-amber-100 rounded-full flex items-center justify-center">
            <i class="fa-solid fa-clock text-2xl text-amber-600"></i>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-500">Pendapatan Hari Ini</p>
            <p class="text-2xl lg:text-3xl font-bold text-gray-900 mt-2">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500 mt-1">Pesanan lunas hari ini</p>
          </div>
          <div class="w-14 h-14 bg-teal-100 rounded-full flex items-center justify-center">
            <i class="fa-solid fa-bolt text-2xl text-teal-600"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Chart Row 1: Pendapatan 14 hari + Tipe Pesanan -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
      <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-base font-bold text-gray-900">Pendapatan 14 Hari Terakhir</h2>
          <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
            <i class="fa-solid fa-sack-dollar mr-1"></i>
            Lunas
          </span>
        </div>
        <div class="h-72 relative">
          <canvas id="revenueChart"></canvas>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <h2 class="text-base font-bold text-gray-900 mb-4">Tipe Pesanan</h2>
        <div class="h-72 relative">
          <canvas id="orderTypeChart"></canvas>
        </div>
      </div>
    </div>

    <!-- Chart Row 2: Pesanan 14 hari + Status Pesanan -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
      <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-base font-bold text-gray-900">Jumlah Pesanan 14 Hari Terakhir</h2>
          <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800">
            <i class="fa-solid fa-receipt mr-1"></i>
            Semua status
          </span>
        </div>
        <div class="h-72 relative">
          <canvas id="ordersChart"></canvas>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <h2 class="text-base font-bold text-gray-900 mb-4">Status Pesanan</h2>
        <div class="h-72 relative">
          <canvas id="orderStatusChart"></canvas>
        </div>
      </div>
    </div>

    <!-- Chart Row 3: Top Produk + Top Pelanggan + Status Pembayaran -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <h2 class="text-base font-bold text-gray-900 mb-4">Top 5 Produk Terlaris</h2>
        <div class="h-80 relative">
          <canvas id="topProductsChart"></canvas>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <h2 class="text-base font-bold text-gray-900 mb-4">Top 5 Pelanggan</h2>
        <div class="h-80 relative">
          <canvas id="topCustomersChart"></canvas>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <h2 class="text-base font-bold text-gray-900 mb-4">Status Pembayaran</h2>
        <div class="h-80 relative">
          <canvas id="paymentStatusChart"></canvas>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
const fmtRp = (v) => 'Rp ' + Number(v).toLocaleString('id-ID');
const palette = ['#D26986', '#F48FB1', '#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#14b8a6'];

// 1. Pendapatan 14 hari
new Chart(document.getElementById('revenueChart'), {
  type: 'bar',
  data: {
    labels: {!! json_encode($revenueLabels->values()) !!},
    datasets: [{ label: 'Pendapatan', data: {!! json_encode($revenueValues->values()) !!},
                 backgroundColor: '#D26986', borderRadius: 6, maxBarThickness: 32 }]
  },
  options: {
    responsive: true, maintainAspectRatio: false,
    plugins: { legend: { display: false },
      tooltip: { callbacks: { label: (c) => fmtRp(c.raw) } } },
    scales: { y: { beginAtZero: true, ticks: { callback: (v) => 'Rp ' + Number(v).toLocaleString('id-ID') } } }
  }
});

// 2. Tipe pesanan (doughnut)
new Chart(document.getElementById('orderTypeChart'), {
  type: 'doughnut',
  data: {
    labels: {!! json_encode($orderTypeLabels->values()) !!},
    datasets: [{ data: {!! json_encode($orderTypeValues->values()) !!},
                 backgroundColor: palette.slice(0, Math.max(1, {!! json_encode($orderTypeLabels->count()) !!})), borderWidth: 2 }]
  },
  options: {
    responsive: true, maintainAspectRatio: false,
    plugins: { legend: { position: 'bottom' } }
  }
});

// 3. Jumlah pesanan 14 hari
new Chart(document.getElementById('ordersChart'), {
  type: 'bar',
  data: {
    labels: {!! json_encode($revenueLabels->values()) !!},
    datasets: [{ label: 'Pesanan', data: {!! json_encode($orderValues->values()) !!},
                 backgroundColor: '#6366f1', borderRadius: 6, maxBarThickness: 32 }]
  },
  options: {
    responsive: true, maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
  }
});

// 4. Status pesanan (doughnut)
new Chart(document.getElementById('orderStatusChart'), {
  type: 'doughnut',
  data: {
    labels: {!! json_encode($orderStatusLabels->values()) !!},
    datasets: [{ data: {!! json_encode($orderStatusValues->values()) !!},
                 backgroundColor: palette, borderWidth: 2 }]
  },
  options: {
    responsive: true, maintainAspectRatio: false,
    plugins: { legend: { position: 'bottom' } }
  }
});

// 5. Top 5 produk (bar horizontal)
new Chart(document.getElementById('topProductsChart'), {
  type: 'bar',
  data: {
    labels: {!! json_encode($topProductLabels->values()) !!},
    datasets: [{ label: 'Terjual', data: {!! json_encode($topProductValues->values()) !!},
                 backgroundColor: '#F48FB1', borderRadius: 6, maxBarThickness: 22 }]
  },
  options: {
    indexAxis: 'y', responsive: true, maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } }
  }
});

// 6. Top 5 pelanggan (bar)
new Chart(document.getElementById('topCustomersChart'), {
  type: 'bar',
  data: {
    labels: {!! json_encode($topCustomerLabels->values()) !!},
    datasets: [{ label: 'Total Belanja', data: {!! json_encode($topCustomerValues->values()) !!},
                 backgroundColor: '#10b981', borderRadius: 6, maxBarThickness: 28 }]
  },
  options: {
    responsive: true, maintainAspectRatio: false,
    plugins: {
      legend: { display: false },
      tooltip: { callbacks: { label: (c) => fmtRp(c.raw) } }
    },
    scales: { y: { beginAtZero: true, ticks: { callback: (v) => 'Rp ' + Number(v).toLocaleString('id-ID') } } }
  }
});

// 7. Status pembayaran (doughnut)
new Chart(document.getElementById('paymentStatusChart'), {
  type: 'doughnut',
  data: {
    labels: {!! json_encode($paymentLabels->values()) !!},
    datasets: [{ data: {!! json_encode($paymentValues->values()) !!},
                 backgroundColor: palette, borderWidth: 2 }]
  },
  options: {
    responsive: true, maintainAspectRatio: false,
    plugins: { legend: { position: 'bottom' } }
  }
});
</script>
@endsection