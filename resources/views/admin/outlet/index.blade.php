@extends('layouts.admin')

@section('title', 'Kelola Outlet & QR - Admin Ekimochi')

@section('content')
<div class="py-8">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
      <div>
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Kelola Outlet & QR</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola outlet dan QR code meja secara langsung</p>
      </div>
      <a href="{{ route('admin.outlet.create') }}" class="mt-4 sm:mt-0 inline-flex items-center justify-center px-5 py-3 bg-[#D26986] hover:bg-[#BD5773] text-white font-bold rounded-xl transition transform active:scale-95 shadow-lg text-sm">
        <i class="fa-solid fa-plus mr-2"></i>
        Tambah Outlet
      </a>
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
            <p class="text-sm font-medium text-gray-500">Total Outlet</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalOutlets, 0, ',', '.') }}</p>
          </div>
          <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center">
            <i class="fa-solid fa-store text-2xl text-blue-600"></i>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-500">Outlet Aktif</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($activeOutlets, 0, ',', '.') }}</p>
          </div>
          <div class="w-14 h-14 bg-emerald-100 rounded-full flex items-center justify-center">
            <i class="fa-solid fa-circle-check text-2xl text-emerald-600"></i>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-500">Total Meja</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalTables, 0, ',', '.') }}</p>
          </div>
          <div class="w-14 h-14 bg-indigo-100 rounded-full flex items-center justify-center">
            <i class="fa-solid fa-chair text-2xl text-indigo-600"></i>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-500">QR Meja Aktif</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($activeTables, 0, ',', '.') }}</p>
          </div>
          <div class="w-14 h-14 bg-rose-100 rounded-full flex items-center justify-center">
            <i class="fa-solid fa-qrcode text-2xl text-rose-600"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Outlets List -->
    @forelse($outlets as $outlet)
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-6">
      <div class="p-6 border-b border-gray-200">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
          <div class="flex items-center gap-4 min-w-0">
            @if($outlet->image_url)
            <img src="{{ $outlet->image_url }}" alt="Outlet {{ $outlet->name }}"
                 class="w-20 h-20 rounded-lg object-cover border border-gray-200 flex-shrink-0">
            @else
            <div class="w-20 h-20 rounded-lg bg-rose-50 border border-gray-200 flex items-center justify-center flex-shrink-0">
              <i class="fa-solid fa-store text-[#D26986]"></i>
            </div>
            @endif
            <div class="min-w-0">
              <h2 class="text-xl font-bold text-gray-900">{{ $outlet->name }}</h2>
              <p class="text-sm text-gray-600 mt-1">{{ $outlet->address }}, {{ $outlet->city }}</p>
              <p class="text-sm text-gray-500">{{ $outlet->phone }}</p>
            </div>
          </div>
          <div class="flex items-center gap-2 flex-shrink-0 flex-wrap">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $outlet->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600' }}">
              <i class="fa-solid {{ $outlet->is_active ? 'fa-circle-check' : 'fa-circle-xmark' }} mr-1"></i>
              {{ $outlet->is_active ? 'Aktif' : 'Tidak Aktif' }}
            </span>
            <a href="{{ route('admin.outlet.edit', $outlet) }}" class="inline-flex items-center px-3 py-2 rounded-lg text-xs font-semibold bg-blue-50 hover:bg-blue-100 text-blue-700 transition">
              <i class="fa-solid fa-pen mr-1"></i>
              Edit
            </a>
            <form action="{{ route('admin.outlet.destroy', $outlet) }}" method="POST" onsubmit="return confirm('Hapus outlet {{ $outlet->name }}? Semua meja & QR code-nya ikut terhapus.');">
              @csrf
              @method('DELETE')
              <button type="submit" class="inline-flex items-center px-3 py-2 rounded-lg text-xs font-semibold bg-red-50 hover:bg-red-100 text-red-600 transition">
                <i class="fa-solid fa-trash mr-1"></i>
                Hapus
              </button>
            </form>
            <form action="{{ route('admin.outlet.toggle', $outlet) }}" method="POST">
              @csrf
              <button type="submit" class="inline-flex items-center px-3 py-2 rounded-lg text-xs font-semibold transition {{ $outlet->is_active ? 'bg-gray-100 hover:bg-gray-200 text-gray-700' : 'bg-emerald-500 hover:bg-emerald-600 text-white' }}">
                <i class="fa-solid {{ $outlet->is_active ? 'fa-pause' : 'fa-play' }} mr-1"></i>
                {{ $outlet->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
              </button>
            </form>
          </div>
        </div>
      </div>

      <div class="p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
          <h3 class="text-lg font-semibold text-gray-900">
            <i class="fa-solid fa-qrcode mr-2 text-[#D26986]"></i>
            Meja & QR Code
            <span class="text-sm font-normal text-gray-500">({{ $outlet->tables->count() }} meja)</span>
          </h3>

          <!-- Tambah Meja -->
          <form action="{{ route('admin.outlet.tables.store', $outlet) }}" method="POST" class="flex items-center gap-2">
            @csrf
            <input type="text" name="table_number" placeholder="No. meja (cth: 01)" maxlength="20"
                   class="w-36 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#D26986] focus:border-[#D26986] outline-none transition">
            <button type="submit" class="inline-flex items-center px-3 py-2 bg-[#D26986] hover:bg-[#BD5773] text-white text-xs font-bold rounded-lg transition">
              <i class="fa-solid fa-plus mr-1"></i>
              Tambah Meja
            </button>
          </form>
        </div>
        @error('table_number')
        <p class="text-xs text-red-600 mb-3"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
        @enderror

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
          @forelse($outlet->tables as $table)
          <div class="border-2 {{ $table->is_active ? 'border-gray-200' : 'border-red-200 bg-gray-50' }} rounded-lg p-4 text-center hover:border-[#D26986] transition">
            <div class="bg-white p-2 rounded-lg mb-3">
              <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(route('scan.table', $table->qr_token)) }}"
                   alt="QR Meja {{ $table->table_number }}"
                   class="w-full h-auto">
            </div>
            <p class="font-bold text-gray-900">Meja {{ $table->table_number }}</p>
            <p class="text-xs {{ $table->is_active ? 'text-emerald-600' : 'text-red-500' }} mt-1 font-semibold">
              <i class="fa-solid {{ $table->is_active ? 'fa-circle-check' : 'fa-circle-xmark' }} mr-1"></i>
              {{ $table->is_active ? 'Aktif' : 'Tidak Aktif' }}
            </p>

            <!-- Aksi QR -->
            <div class="mt-3 flex items-center justify-center gap-2 flex-wrap">
              <a href="https://api.qrserver.com/v1/create-qr-code/?size=500x500&data={{ urlencode(route('scan.table', $table->qr_token)) }}"
                 target="_blank"
                 download="QR_{{ $outlet->name }}_Meja_{{ $table->table_number }}.png"
                 class="inline-flex items-center px-2.5 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-semibold rounded-lg transition">
                <i class="fa-solid fa-download mr-1"></i>
                Download
              </a>
              <form action="{{ route('admin.outlet.tables.toggle', $table) }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center px-2.5 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-lg transition">
                  <i class="fa-solid {{ $table->is_active ? 'fa-pause' : 'fa-play' }} mr-1"></i>
                  {{ $table->is_active ? 'Nonaktif' : 'Aktif' }}
                </button>
              </form>
              <form action="{{ route('admin.outlet.tables.destroy', $table) }}" method="POST" onsubmit="return confirm('Hapus Meja {{ $table->table_number }} beserta QR code-nya?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-2.5 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold rounded-lg transition">
                  <i class="fa-solid fa-trash mr-1"></i>
                  Hapus
                </button>
              </form>
            </div>
          </div>
          @empty
          <div class="col-span-full py-8 text-center text-gray-400">
            <i class="fa-solid fa-chair text-4xl mb-3"></i>
            <p class="text-sm font-medium">Belum ada meja untuk outlet ini</p>
            <p class="text-xs mt-1">Gunakan form "Tambah Meja" untuk membuat QR code baru</p>
          </div>
          @endforelse
        </div>
      </div>
    </div>
    @empty
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-12">
      <div class="flex flex-col items-center justify-center text-gray-400">
        <i class="fa-solid fa-store text-5xl mb-4"></i>
        <p class="text-lg font-medium">Tidak ada outlet</p>
        <p class="text-sm mt-1">Belum ada outlet yang terdaftar</p>
      </div>
    </div>
    @endforelse

    <!-- Cara Penggunaan -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg mt-8">
      <div class="flex items-start">
        <i class="fa-solid fa-info-circle text-blue-600 text-xl mr-3 mt-0.5"></i>
        <div>
          <h3 class="font-semibold text-blue-900 mb-1">Cara Menggunakan QR Code</h3>
          <ol class="text-sm text-blue-800 space-y-1 list-decimal list-inside">
            <li>Download QR code untuk setiap meja</li>
            <li>Print dan tempelkan QR code di setiap meja</li>
            <li>Customer scan QR code dengan smartphone mereka</li>
            <li>Sistem otomatis mendeteksi outlet dan nomor meja</li>
            <li>Customer bisa langsung pesan dengan mode Dine In</li>
          </ol>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection