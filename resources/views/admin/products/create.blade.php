@extends('layouts.admin')

@section('title', 'Tambah Produk - Admin Ekimochi')

@section('content')
<div class="py-8">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- Breadcrumb -->
    <nav class="text-xs text-gray-500 mb-6">
      <a href="{{ route('admin.dashboard') }}" class="hover:text-[#D26986] transition">Dashboard</a>
      <span class="mx-2">/</span>
      <a href="{{ route('admin.products.index') }}" class="hover:text-[#D26986] transition">Produk</a>
      <span class="mx-2">/</span>
      <span class="text-gray-900 font-semibold">Tambah Produk</span>
    </nav>

    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-2xl font-bold text-gray-900">Tambah Produk Baru</h1>
      <p class="text-sm text-gray-500 mt-1">Lengkapi informasi produk yang akan ditambahkan</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
      <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- Form Content -->
        <div class="p-6 sm:p-8 space-y-6">
          
          <!-- Basic Information Section -->
          <div>
            <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200">
              <i class="fa-solid fa-info-circle text-[#D26986] mr-2"></i>
              Informasi Dasar
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Product Name -->
              <div class="md:col-span-2">
                <label for="name" class="block text-sm font-bold text-gray-700 mb-2">
                  Nama Produk <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}" 
                       required
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#D26986] focus:ring-2 focus:ring-[#FBE8EE] text-sm @error('name') border-red-500 @enderror"
                       placeholder="Contoh: Mochi Strawberry Premium">
                @error('name')
                  <p class="mt-2 text-xs text-red-600 flex items-center">
                    <i class="fa-solid fa-circle-exclamation mr-1"></i>
                    {{ $message }}
                  </p>
                @enderror
              </div>

              <!-- SKU -->
              <div>
                <label for="sku" class="block text-sm font-bold text-gray-700 mb-2">
                  SKU <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="sku" 
                       name="sku" 
                       value="{{ old('sku') }}" 
                       required
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#D26986] focus:ring-2 focus:ring-[#FBE8EE] text-sm font-mono @error('sku') border-red-500 @enderror"
                       placeholder="EKI-001">
                <p class="mt-1 text-xs text-gray-500">Kode unik produk</p>
                @error('sku')
                  <p class="mt-2 text-xs text-red-600 flex items-center">
                    <i class="fa-solid fa-circle-exclamation mr-1"></i>
                    {{ $message }}
                  </p>
                @enderror
              </div>

              <!-- Category -->
              <div>
                <label for="category_id" class="block text-sm font-bold text-gray-700 mb-2">
                  Kategori <span class="text-red-500">*</span>
                </label>
                <select id="category_id" 
                        name="category_id" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#D26986] focus:ring-2 focus:ring-[#FBE8EE] text-sm @error('category_id') border-red-500 @enderror">
                  <option value="">Pilih Kategori</option>
                  @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                      {{ $category->name }}
                    </option>
                  @endforeach
                </select>
                @error('category_id')
                  <p class="mt-2 text-xs text-red-600 flex items-center">
                    <i class="fa-solid fa-circle-exclamation mr-1"></i>
                    {{ $message }}
                  </p>
                @enderror
              </div>

              <!-- Description -->
              <div class="md:col-span-2">
                <label for="description" class="block text-sm font-bold text-gray-700 mb-2">
                  Deskripsi Produk
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="4"
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#D26986] focus:ring-2 focus:ring-[#FBE8EE] text-sm resize-none @error('description') border-red-500 @enderror"
                          placeholder="Deskripsikan produk Anda...">{{ old('description') }}</textarea>
                @error('description')
                  <p class="mt-2 text-xs text-red-600 flex items-center">
                    <i class="fa-solid fa-circle-exclamation mr-1"></i>
                    {{ $message }}
                  </p>
                @enderror
              </div>
            </div>
          </div>

          <!-- Pricing & Inventory Section -->
          <div>
            <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200">
              <i class="fa-solid fa-dollar-sign text-[#D26986] mr-2"></i>
              Harga & Stok
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <!-- Price -->
              <div>
                <label for="price" class="block text-sm font-bold text-gray-700 mb-2">
                  Harga <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                  <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-semibold">Rp</span>
                  <input type="number" 
                         id="price" 
                         name="price" 
                         value="{{ old('price') }}" 
                         min="0" 
                         step="0.01" 
                         required
                         class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#D26986] focus:ring-2 focus:ring-[#FBE8EE] text-sm @error('price') border-red-500 @enderror"
                         placeholder="15000">
                </div>
                @error('price')
                  <p class="mt-2 text-xs text-red-600 flex items-center">
                    <i class="fa-solid fa-circle-exclamation mr-1"></i>
                    {{ $message }}
                  </p>
                @enderror
              </div>

              <!-- Stock -->
              <div>
                <label for="stock" class="block text-sm font-bold text-gray-700 mb-2">
                  Stok <span class="text-red-500">*</span>
                </label>
                <input type="number" 
                       id="stock" 
                       name="stock" 
                       value="{{ old('stock', 0) }}" 
                       min="0" 
                       required
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#D26986] focus:ring-2 focus:ring-[#FBE8EE] text-sm @error('stock') border-red-500 @enderror"
                       placeholder="100">
                @error('stock')
                  <p class="mt-2 text-xs text-red-600 flex items-center">
                    <i class="fa-solid fa-circle-exclamation mr-1"></i>
                    {{ $message }}
                  </p>
                @enderror
              </div>

              <!-- Unit -->
              <div>
                <label for="unit" class="block text-sm font-bold text-gray-700 mb-2">
                  Satuan <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="unit" 
                       name="unit" 
                       value="{{ old('unit') }}" 
                       required
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#D26986] focus:ring-2 focus:ring-[#FBE8EE] text-sm @error('unit') border-red-500 @enderror"
                       placeholder="pcs / box / pack">
                @error('unit')
                  <p class="mt-2 text-xs text-red-600 flex items-center">
                    <i class="fa-solid fa-circle-exclamation mr-1"></i>
                    {{ $message }}
                  </p>
                @enderror
              </div>
            </div>
          </div>

          <!-- Status & Image Section -->
          <div>
            <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200">
              <i class="fa-solid fa-image text-[#D26986] mr-2"></i>
              Status & Gambar
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Status -->
              <div>
                <label for="status" class="block text-sm font-bold text-gray-700 mb-2">
                  Status Produk <span class="text-red-500">*</span>
                </label>
                <select id="status" 
                        name="status" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#D26986] focus:ring-2 focus:ring-[#FBE8EE] text-sm @error('status') border-red-500 @enderror">
                  <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                  <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
                @error('status')
                  <p class="mt-2 text-xs text-red-600 flex items-center">
                    <i class="fa-solid fa-circle-exclamation mr-1"></i>
                    {{ $message }}
                  </p>
                @enderror
              </div>

              <!-- Image Upload -->
              <div>
                <label for="image" class="block text-sm font-bold text-gray-700 mb-2">
                  Gambar Produk
                </label>
                <input type="file" 
                       id="image" 
                       name="image" 
                       accept="image/*"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#D26986] focus:ring-2 focus:ring-[#FBE8EE] text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#FBE8EE] file:text-[#D26986] hover:file:bg-[#D26986] hover:file:text-white file:cursor-pointer @error('image') border-red-500 @enderror">
                <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG. Maksimal 2MB</p>
                @error('image')
                  <p class="mt-2 text-xs text-red-600 flex items-center">
                    <i class="fa-solid fa-circle-exclamation mr-1"></i>
                    {{ $message }}
                  </p>
                @enderror
              </div>
            </div>

            <!-- Image Preview -->
            <div id="imagePreview" class="hidden mt-4">
              <p class="text-sm font-semibold text-gray-700 mb-2">Preview:</p>
              <div class="w-40 h-40 rounded-xl overflow-hidden bg-gray-100 border-2 border-gray-300">
                <img id="previewImg" src="" alt="Preview" class="w-full h-full object-cover">
              </div>
            </div>
          </div>

        </div>

        <!-- Form Actions -->
        <div class="px-6 sm:px-8 py-6 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
          <a href="{{ route('admin.products.index') }}" 
             class="inline-flex items-center px-5 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Batal
          </a>
          
          <button type="submit" 
                  class="inline-flex items-center px-6 py-3 bg-[#D26986] hover:bg-[#BD5773] text-white font-bold rounded-xl transition transform active:scale-95 shadow-lg">
            <i class="fa-solid fa-save mr-2"></i>
            Simpan Produk
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  // Image preview
  document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function(e) {
        document.getElementById('previewImg').src = e.target.result;
        document.getElementById('imagePreview').classList.remove('hidden');
      }
      reader.readAsDataURL(file);
    }
  });
</script>
@endsection
