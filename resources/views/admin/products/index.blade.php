@extends('layouts.admin')

@section('title', 'Kelola Produk - Admin Ekimochi')

@section('content')
<div class="py-8">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- Header & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Kelola Produk</h1>
        <p class="text-sm text-gray-500 mt-1">Manajemen produk Ekimochi</p>
      </div>
      <a href="{{ route('admin.products.create') }}" class="mt-4 sm:mt-0 inline-flex items-center px-5 py-3 bg-[#D26986] hover:bg-[#BD5773] text-white font-bold rounded-xl transition transform active:scale-95 shadow-lg">
        <i class="fa-solid fa-plus mr-2"></i>
        Tambah Produk
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

    <!-- Filters & Search -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 mb-6">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Search -->
        <div class="md:col-span-2">
          <label class="block text-xs font-semibold text-gray-700 mb-2">
            <i class="fa-solid fa-magnifying-glass mr-1"></i> Cari Produk
          </label>
          <input type="text" id="searchInput" placeholder="Cari nama atau SKU..." 
                 class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:border-[#D26986] focus:ring-2 focus:ring-[#FBE8EE] text-sm">
        </div>

        <!-- Category Filter -->
        <div>
          <label class="block text-xs font-semibold text-gray-700 mb-2">
            <i class="fa-solid fa-filter mr-1"></i> Kategori
          </label>
          <select id="categoryFilter" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:border-[#D26986] focus:ring-2 focus:ring-[#FBE8EE] text-sm">
            <option value="">Semua Kategori</option>
            @foreach(App\Models\Category::all() as $category)
              <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
          </select>
        </div>

        <!-- Status Filter -->
        <div>
          <label class="block text-xs font-semibold text-gray-700 mb-2">
            <i class="fa-solid fa-toggle-on mr-1"></i> Status
          </label>
          <select id="statusFilter" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:border-[#D26986] focus:ring-2 focus:ring-[#FBE8EE] text-sm">
            <option value="">Semua Status</option>
            <option value="active">Aktif</option>
            <option value="inactive">Tidak Aktif</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Products Table Card -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
      
      <!-- Table Header Info -->
      <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <div class="flex items-center justify-between">
          <span class="text-sm text-gray-600">
            Menampilkan <strong class="text-gray-900">{{ $products->count() }}</strong> dari <strong class="text-gray-900">{{ $products->total() }}</strong> produk
          </span>
          <span class="text-xs text-gray-500">
            <i class="fa-solid fa-clock mr-1"></i> Terakhir diperbarui: {{ now()->format('d M Y H:i') }}
          </span>
        </div>
      </div>

      @if($products->count() > 0)
      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
              <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Produk</th>
              <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">SKU</th>
              <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Kategori</th>
              <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Harga</th>
              <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Stok</th>
              <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
              <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @foreach($products as $product)
            <tr class="hover:bg-gray-50 transition duration-150">
              <!-- Product Info -->
              <td class="px-6 py-4">
                <div class="flex items-center space-x-4">
                  <div class="flex-shrink-0 w-16 h-16 rounded-xl overflow-hidden bg-gradient-to-br from-[#FBE8EE] to-white border border-rose-100">
                    @if($product->image)
                      <img src="{{ asset('storage/' . $product->image) }}" 
                           alt="{{ $product->name }}" 
                           class="w-full h-full object-cover">
                    @else
                      <div class="w-full h-full flex items-center justify-center text-2xl">🍡</div>
                    @endif
                  </div>
                  <div class="min-w-0 flex-1">
                    <p class="text-sm font-bold text-gray-900 truncate">{{ $product->name }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ Str::limit($product->description, 40) }}</p>
                  </div>
                </div>
              </td>

              <!-- SKU -->
              <td class="px-6 py-4">
                <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-gray-100 text-xs font-mono font-semibold text-gray-700">
                  {{ $product->sku }}
                </span>
              </td>

              <!-- Category -->
              <td class="px-6 py-4">
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-[#FBE8EE] text-xs font-semibold text-[#D26986]">
                  <i class="fa-solid fa-tag mr-1.5"></i>
                  {{ $product->category->name }}
                </span>
              </td>

              <!-- Price -->
              <td class="px-6 py-4">
                <div class="text-sm font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                <div class="text-xs text-gray-500">per {{ $product->unit }}</div>
              </td>

              <!-- Stock -->
              <td class="px-6 py-4 text-center">
                @if($product->stock > 10)
                  <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-green-100 text-green-800 text-sm font-bold">
                    <i class="fa-solid fa-circle-check mr-1.5"></i>
                    {{ $product->stock }}
                  </span>
                @elseif($product->stock > 0)
                  <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-yellow-100 text-yellow-800 text-sm font-bold">
                    <i class="fa-solid fa-triangle-exclamation mr-1.5"></i>
                    {{ $product->stock }}
                  </span>
                @else
                  <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-red-100 text-red-800 text-sm font-bold">
                    <i class="fa-solid fa-circle-xmark mr-1.5"></i>
                    0
                  </span>
                @endif
              </td>

              <!-- Status -->
              <td class="px-6 py-4 text-center">
                @if($product->status === 'active')
                  <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold uppercase">
                    <i class="fa-solid fa-circle mr-1.5 text-[8px]"></i>
                    Aktif
                  </span>
                @else
                  <span class="inline-flex items-center px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-bold uppercase">
                    <i class="fa-solid fa-circle mr-1.5 text-[8px]"></i>
                    Nonaktif
                  </span>
                @endif
              </td>

              <!-- Actions -->
              <td class="px-6 py-4">
                <div class="flex items-center justify-center space-x-2">
                  <a href="{{ route('admin.products.edit', $product) }}" 
                     class="inline-flex items-center px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg transition text-xs font-semibold"
                     title="Edit">
                    <i class="fa-solid fa-pen"></i>
                  </a>
                  
                  <form method="POST" action="{{ route('admin.products.destroy', $product) }}" 
                        onsubmit="return confirm('Yakin ingin menghapus produk {{ $product->name }}?')"
                        class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center px-3 py-2 bg-red-50 hover:bg-red-100 text-red-700 rounded-lg transition text-xs font-semibold"
                            title="Hapus">
                      <i class="fa-solid fa-trash"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        {{ $products->links() }}
      </div>

      @else
      <!-- Empty State -->
      <div class="text-center py-16">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-100 mb-4">
          <i class="fa-solid fa-box-open text-4xl text-gray-400"></i>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-2">Belum ada produk</h3>
        <p class="text-sm text-gray-500 mb-6">Mulai dengan menambahkan produk pertama Anda</p>
        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center px-5 py-3 bg-[#D26986] hover:bg-[#BD5773] text-white font-bold rounded-xl transition">
          <i class="fa-solid fa-plus mr-2"></i>
          Tambah Produk
        </a>
      </div>
      @endif

    </div>
  </div>
</div>
@endsection
