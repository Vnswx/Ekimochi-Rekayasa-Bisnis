@extends('layouts.admin')

@section('title', 'Promo & Diskon - Admin Ekimochi')

@section('content')
<div class="py-8">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
      <div>
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Promo & Diskon</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola promo dan diskon yang tampil di katalog & blog</p>
      </div>
      <a href="{{ route('admin.promo.create') }}" class="mt-4 sm:mt-0 inline-flex items-center justify-center px-5 py-3 bg-[#D26986] hover:bg-[#BD5773] text-white font-bold rounded-xl transition transform active:scale-95 shadow-lg text-sm">
        <i class="fa-solid fa-plus mr-2"></i>
        Tambah Promo
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
            <p class="text-sm font-medium text-gray-500">Total Promo</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalPromos, 0, ',', '.') }}</p>
          </div>
          <div class="w-14 h-14 bg-indigo-100 rounded-full flex items-center justify-center">
            <i class="fa-solid fa-percent text-2xl text-indigo-600"></i>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-500">Sedang Berjalan</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($activePromos, 0, ',', '.') }}</p>
          </div>
          <div class="w-14 h-14 bg-emerald-100 rounded-full flex items-center justify-center">
            <i class="fa-solid fa-bolt text-2xl text-emerald-600"></i>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-500">Akan Datang</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($upcomingPromos, 0, ',', '.') }}</p>
          </div>
          <div class="w-14 h-14 bg-amber-100 rounded-full flex items-center justify-center">
            <i class="fa-solid fa-calendar-plus text-2xl text-amber-600"></i>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-500">Berakhir</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($expiredPromos, 0, ',', '.') }}</p>
          </div>
          <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center">
            <i class="fa-solid fa-calendar-xmark text-2xl text-gray-500"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Promos Table - Desktop View -->
    <div class="hidden lg:block bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Promo</th>
              <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Diskon</th>
              <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Periode</th>
              <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
              <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @forelse($promos as $promo)
            <tr class="hover:bg-gray-50 transition">
              <td class="px-6 py-4">
                <div class="text-sm font-semibold text-gray-900">{{ $promo->name }}</div>
                @if($promo->description)
                <div class="text-xs text-gray-500 mt-0.5">{{ Str::limit($promo->description, 60) }}</div>
                @endif
                @if((float) $promo->min_purchase > 0)
                <div class="text-[11px] text-gray-400 mt-1">Min. belanja Rp {{ number_format($promo->min_purchase, 0, ',', '.') }}</div>
                @endif
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700">
                  <i class="fa-solid fa-tag mr-1"></i>
                  {{ $promo->discountLabel() }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-xs text-gray-700">
                  @if($promo->starts_at && $promo->ends_at)
                    {{ $promo->starts_at->format('d M Y') }} — {{ $promo->ends_at->format('d M Y') }}
                  @elseif($promo->starts_at)
                    Mulai {{ $promo->starts_at->format('d M Y') }}
                  @elseif($promo->ends_at)
                    s/d {{ $promo->ends_at->format('d M Y') }}
                  @else
                    <span class="text-gray-400">Tanpa batas</span>
                  @endif
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                @if($promo->isCurrentlyActive())
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                    <i class="fa-solid fa-bolt mr-1"></i>
                    Berjalan
                  </span>
                @elseif($promo->is_active)
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                    <i class="fa-solid fa-calendar-plus mr-1"></i>
                    @if($promo->starts_at && $promo->starts_at->isFuture()) Akan Datang @else Berakhir @endif
                  </span>
                @else
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                    <i class="fa-solid fa-pause mr-1"></i>
                    Nonaktif
                  </span>
                @endif
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('admin.promo.edit', $promo) }}" class="inline-flex items-center px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold rounded-lg transition">
                    <i class="fa-solid fa-pen mr-1"></i>
                    Edit
                  </a>
                  <form action="{{ route('admin.promo.toggle', $promo) }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-3 py-2 rounded-lg text-xs font-semibold transition {{ $promo->is_active ? 'bg-gray-100 hover:bg-gray-200 text-gray-700' : 'bg-emerald-500 hover:bg-emerald-600 text-white' }}">
                      <i class="fa-solid {{ $promo->is_active ? 'fa-pause' : 'fa-play' }} mr-1"></i>
                      {{ $promo->is_active ? 'Nonaktif' : 'Aktif' }}
                    </button>
                  </form>
                  <form action="{{ route('admin.promo.destroy', $promo) }}" method="POST" onsubmit="return confirm('Hapus promo {{ $promo->name }}?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded-lg transition">
                      <i class="fa-solid fa-trash mr-1"></i>
                      Hapus
                    </button>
                  </form>
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="5" class="px-6 py-12 text-center">
                <div class="flex flex-col items-center justify-center text-gray-400">
                  <i class="fa-solid fa-percent text-5xl mb-4"></i>
                  <p class="text-lg font-medium">Tidak ada promo</p>
                  <p class="text-sm mt-1">Belum ada promo yang dibuat</p>
                </div>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- Promos Cards - Mobile View -->
    <div class="lg:hidden space-y-4">
      @forelse($promos as $promo)
      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4">
        <div class="flex items-start justify-between gap-3 mb-3">
          <div>
            <h3 class="text-base font-bold text-gray-900">{{ $promo->name }}</h3>
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700 mt-2">
              <i class="fa-solid fa-tag mr-1"></i>
              {{ $promo->discountLabel() }}
            </span>
          </div>
          @if($promo->isCurrentlyActive())
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
              <i class="fa-solid fa-bolt mr-1"></i>
              Berjalan
            </span>
          @else
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $promo->is_active ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-600' }}">
              {{ $promo->is_active ? 'Belum / Berakhir' : 'Nonaktif' }}
            </span>
          @endif
        </div>

        <div class="space-y-2 mb-4 pb-4 border-b border-gray-200">
          @if($promo->description)
          <div class="flex items-start gap-2">
            <i class="fa-solid fa-align-left text-gray-400 text-sm mt-0.5"></i>
            <p class="text-sm text-gray-700 flex-1">{{ $promo->description }}</p>
          </div>
          @endif
          <div class="flex items-start gap-2">
            <i class="fa-solid fa-calendar text-gray-400 text-sm mt-0.5"></i>
            <div class="flex-1">
              <p class="text-xs text-gray-500">Periode</p>
              <p class="text-sm text-gray-700">
                @if($promo->starts_at && $promo->ends_at)
                  {{ $promo->starts_at->format('d M Y') }} — {{ $promo->ends_at->format('d M Y') }}
                @else
                  Tanpa batas
                @endif
              </p>
            </div>
          </div>
        </div>

        <div class="flex gap-2">
          <a href="{{ route('admin.promo.edit', $promo) }}" class="flex-1 inline-flex items-center justify-center px-4 py-2.5 bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold rounded-xl transition">
            <i class="fa-solid fa-pen mr-2"></i>
            Edit
          </a>
          <form action="{{ route('admin.promo.toggle', $promo) }}" method="POST" class="flex-1">
            @csrf
            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-sm font-semibold transition {{ $promo->is_active ? 'bg-gray-100 hover:bg-gray-200 text-gray-700' : 'bg-emerald-500 hover:bg-emerald-600 text-white' }}">
              <i class="fa-solid {{ $promo->is_active ? 'fa-pause' : 'fa-play' }} mr-2"></i>
              {{ $promo->is_active ? 'Nonaktif' : 'Aktif' }}
            </button>
          </form>
          <form action="{{ route('admin.promo.destroy', $promo) }}" method="POST" onsubmit="return confirm('Hapus promo {{ $promo->name }}?');" class="flex-1">
            @csrf
            @method('DELETE')
            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded-xl transition">
              <i class="fa-solid fa-trash mr-2"></i>
              Hapus
            </button>
          </form>
        </div>
      </div>
      @empty
      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8">
        <div class="flex flex-col items-center justify-center text-gray-400">
          <i class="fa-solid fa-percent text-5xl mb-4"></i>
          <p class="text-lg font-medium">Tidak ada promo</p>
          <p class="text-sm mt-1">Belum ada promo yang dibuat</p>
        </div>
      </div>
      @endforelse
    </div>

  </div>
</div>
@endsection