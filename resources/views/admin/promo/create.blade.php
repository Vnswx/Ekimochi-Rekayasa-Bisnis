@extends('layouts.admin')

@section('title', 'Tambah Promo - Admin Ekimochi')

@section('content')
<div class="py-8">
  <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Back Button -->
    <a href="{{ route('admin.promo.index') }}" class="inline-flex items-center text-[#D26986] hover:text-[#BD5773] font-semibold text-sm mb-6">
      <i class="fa-solid fa-arrow-left mr-2"></i>
      Kembali ke Daftar Promo
    </a>

    <!-- Header -->
    <div class="mb-6 sm:mb-8">
      <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Tambah Promo Baru</h1>
      <p class="text-sm text-gray-500 mt-1">Promo aktif otomatis tampil di katalog & blog</p>
    </div>

    <!-- Alert Messages -->
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

    <!-- Form Card -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
      <form action="{{ route('admin.promo.store') }}" method="POST">
        @csrf

        <div class="p-4 sm:p-8 space-y-6">

          <!-- Name -->
          <div>
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
              Nama Promo <span class="text-red-500">*</span>
            </label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="cth: Diskon Akhir Tahun"
                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#D26986] focus:ring-2 focus:ring-[#D26986]/20 transition @error('name') border-red-500 @enderror">
            @error('name')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Description -->
          <div>
            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
            <textarea id="description" name="description" rows="3" placeholder="Penjelasan singkat promosi..."
                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#D26986] focus:ring-2 focus:ring-[#D26986]/20 transition @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
            @error('description')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Discount Type & Value -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
              <label for="discount_type" class="block text-sm font-semibold text-gray-700 mb-2">
                Jenis Diskon <span class="text-red-500">*</span>
              </label>
              <select id="discount_type" name="discount_type" required
                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#D26986] focus:ring-2 focus:ring-[#D26986]/20 transition @error('discount_type') border-red-500 @enderror">
                <option value="percentage" {{ old('discount_type', 'percentage') === 'percentage' ? 'selected' : '' }}>Persen (%)</option>
                <option value="nominal" {{ old('discount_type') === 'nominal' ? 'selected' : '' }}>Nominal (Rp)</option>
              </select>
              @error('discount_type')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
              @enderror
            </div>
            <div>
              <label for="discount_value" class="block text-sm font-semibold text-gray-700 mb-2">
                Nilai Diskon <span class="text-red-500">*</span>
              </label>
              <input type="number" id="discount_value" name="discount_value" value="{{ old('discount_value') }}" step="any" min="0.01" required placeholder="cth: 10"
                     class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#D26986] focus:ring-2 focus:ring-[#D26986]/20 transition @error('discount_value') border-red-500 @enderror">
              <p class="text-xs text-gray-500 mt-1">10 = diskon 10% (persen) atau Rp 10.000 (nominal)</p>
              @error('discount_value')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
              @enderror
            </div>
          </div>

          <!-- Min Purchase -->
          <div>
            <label for="min_purchase" class="block text-sm font-semibold text-gray-700 mb-2">Minimal Pembelian (Rp)</label>
            <input type="number" id="min_purchase" name="min_purchase" value="{{ old('min_purchase', 0) }}" step="any" min="0"
                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#D26986] focus:ring-2 focus:ring-[#D26986]/20 transition @error('min_purchase') border-red-500 @enderror">
            <p class="text-xs text-gray-500 mt-1">Opsional, 0 = tanpa syarat minimal belanja</p>
            @error('min_purchase')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Period -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
              <label for="starts_at" class="block text-sm font-semibold text-gray-700 mb-2">Mulai</label>
              <input type="datetime-local" id="starts_at" name="starts_at" value="{{ old('starts_at') }}"
                     class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#D26986] focus:ring-2 focus:ring-[#D26986]/20 transition @error('starts_at') border-red-500 @enderror">
              <p class="text-xs text-gray-500 mt-1">Kosongkan = langsung berlaku</p>
              @error('starts_at')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
              @enderror
            </div>
            <div>
              <label for="ends_at" class="block text-sm font-semibold text-gray-700 mb-2">Berakhir</label>
              <input type="datetime-local" id="ends_at" name="ends_at" value="{{ old('ends_at') }}"
                     class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#D26986] focus:ring-2 focus:ring-[#D26986]/20 transition @error('ends_at') border-red-500 @enderror">
              <p class="text-xs text-gray-500 mt-1">Kosongkan = tanpa batas waktu</p>
              @error('ends_at')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
              @enderror
            </div>
          </div>

          <!-- Active -->
          <div>
            <label class="flex items-center space-x-3 cursor-pointer">
              <input type="checkbox" id="is_active" name="is_active" value="1" checked
                     class="w-5 h-5 rounded border-gray-300 text-[#D26986] focus:ring-[#D26986]">
              <span class="text-sm font-semibold text-gray-700">Aktifkan promo (tampil di katalog & blog)</span>
            </label>
          </div>

        </div>

        <!-- Footer Actions -->
        <div class="bg-gray-50 px-4 sm:px-8 py-4 sm:py-5 border-t border-gray-200 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
          <a href="{{ route('admin.promo.index') }}" class="inline-flex items-center justify-center px-5 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition order-2 sm:order-1">
            <i class="fa-solid fa-times mr-2"></i>
            Batal
          </a>
          <button type="submit" class="inline-flex items-center justify-center px-5 py-3 bg-[#D26986] hover:bg-[#BD5773] text-white font-semibold rounded-xl transition transform active:scale-95 shadow-lg order-1 sm:order-2">
            <i class="fa-solid fa-floppy-disk mr-2"></i>
            Simpan Promo
          </button>
        </div>
      </form>
    </div>

  </div>
</div>
@endsection