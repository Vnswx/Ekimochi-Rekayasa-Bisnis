@extends('layouts.admin')

@section('title', 'Tambah Outlet - Admin Ekimochi')

@section('content')
<div class="py-8">
  <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Back Button -->
    <a href="{{ route('admin.outlet.index') }}" class="inline-flex items-center text-[#D26986] hover:text-[#BD5773] font-semibold text-sm mb-6">
      <i class="fa-solid fa-arrow-left mr-2"></i>
      Kembali ke Daftar Outlet
    </a>

    <!-- Header -->
    <div class="mb-6 sm:mb-8">
      <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Tambah Outlet Baru</h1>
      <p class="text-sm text-gray-500 mt-1">Buat outlet baru untuk sistem</p>
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
      <form action="{{ route('admin.outlet.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="p-4 sm:p-8 space-y-6">

          <!-- Name -->
          <div>
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
              Nama Outlet <span class="text-red-500">*</span>
            </label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#D26986] focus:ring-2 focus:ring-[#D26986]/20 transition @error('name') border-red-500 @enderror">
            @error('name')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Address -->
          <div>
            <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">
              Alamat <span class="text-red-500">*</span>
            </label>
            <textarea id="address" name="address" rows="3" required
                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#D26986] focus:ring-2 focus:ring-[#D26986]/20 transition @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
            @error('address')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- City & Phone -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
              <label for="city" class="block text-sm font-semibold text-gray-700 mb-2">
                Kota <span class="text-red-500">*</span>
              </label>
              <input type="text" id="city" name="city" value="{{ old('city') }}" required
                     class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#D26986] focus:ring-2 focus:ring-[#D26986]/20 transition @error('city') border-red-500 @enderror">
              @error('city')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
              @enderror
            </div>
            <div>
              <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                Telepon <span class="text-red-500">*</span>
              </label>
              <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required placeholder="cth: 081234567890"
                     class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#D26986] focus:ring-2 focus:ring-[#D26986]/20 transition @error('phone') border-red-500 @enderror">
              @error('phone')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
              @enderror
            </div>
          </div>

          <!-- Latitude & Longitude -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
              <label for="latitude" class="block text-sm font-semibold text-gray-700 mb-2">
                Latitude
              </label>
              <input type="number" id="latitude" name="latitude" value="{{ old('latitude') }}" step="any" placeholder="cth: -6.9175"
                     class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#D26986] focus:ring-2 focus:ring-[#D26986]/20 transition @error('latitude') border-red-500 @enderror">
              <p class="text-xs text-gray-500 mt-1">Opsional, dipakai untuk hitung ongkir delivery (Haversine)</p>
              @error('latitude')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
              @enderror
            </div>
            <div>
              <label for="longitude" class="block text-sm font-semibold text-gray-700 mb-2">
                Longitude
              </label>
              <input type="number" id="longitude" name="longitude" value="{{ old('longitude') }}" step="any" placeholder="cth: 107.6191"
                     class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#D26986] focus:ring-2 focus:ring-[#D26986]/20 transition @error('longitude') border-red-500 @enderror">
              <p class="text-xs text-gray-500 mt-1">Opsional, dipakai untuk hitung ongkir delivery</p>
              @error('longitude')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
              @enderror
            </div>
          </div>

          <!-- Image -->
          <div>
            <label for="image" class="block text-sm font-semibold text-gray-700 mb-2">
              Foto Outlet
            </label>
            <input type="file" id="image" name="image" accept="image/*"
                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#D26986] focus:ring-2 focus:ring-[#D26986]/20 transition @error('image') border-red-500 @enderror">
            <p class="text-xs text-gray-500 mt-1">Opsional. Format jpeg, png, jpg, webp — maksimal 2MB</p>
            <div id="imagePreview" class="hidden mt-3">
              <img id="previewImg" src="" alt="Preview" class="w-32 h-32 rounded-xl object-cover border border-gray-200">
            </div>
            @error('image')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Active -->
          <div>
            <label class="flex items-center space-x-3 cursor-pointer">
              <input type="checkbox" id="is_active" name="is_active" value="1" checked
                     class="w-5 h-5 rounded border-gray-300 text-[#D26986] focus:ring-[#D26986]">
              <span class="text-sm font-semibold text-gray-700">Outlet aktif (tampil di website)</span>
            </label>
          </div>

        </div>

        <!-- Footer Actions -->
        <div class="bg-gray-50 px-4 sm:px-8 py-4 sm:py-5 border-t border-gray-200 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
          <a href="{{ route('admin.outlet.index') }}" class="inline-flex items-center justify-center px-5 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition order-2 sm:order-1">
            <i class="fa-solid fa-times mr-2"></i>
            Batal
          </a>
          <button type="submit" class="inline-flex items-center justify-center px-5 py-3 bg-[#D26986] hover:bg-[#BD5773] text-white font-semibold rounded-xl transition transform active:scale-95 shadow-lg order-1 sm:order-2">
            <i class="fa-solid fa-floppy-disk mr-2"></i>
            Simpan Outlet
          </button>
        </div>
      </form>
    </div>

  </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const input = document.getElementById('image');
  const preview = document.getElementById('imagePreview');
  const img = document.getElementById('previewImg');

  if (input) {
    input.addEventListener('change', function() {
      const file = this.files[0];
      if (file) {
        img.src = URL.createObjectURL(file);
        preview.classList.remove('hidden');
      } else {
        preview.classList.add('hidden');
      }
    });
  }
});
</script>
@endsection