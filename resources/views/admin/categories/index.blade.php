@extends('layouts.admin')

@section('title', 'Kelola Kategori - Admin Ekimochi')

@section('content')
<div class="py-8" x-data="categoryManager()">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- Header & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Kelola Kategori</h1>
        <p class="text-sm text-gray-500 mt-1">Manajemen kategori produk Ekimochi</p>
      </div>
      <button @click="openAddModal()" class="mt-4 sm:mt-0 inline-flex items-center px-5 py-3 bg-[#D26986] hover:bg-[#BD5773] text-white font-bold rounded-xl transition transform active:scale-95 shadow-lg">
        <i class="fa-solid fa-plus mr-2"></i>
        Tambah Kategori
      </button>
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

    <!-- Categories Table Card -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
      
      <!-- Table Header Info -->
      <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <div class="flex items-center justify-between">
          <span class="text-sm text-gray-600">
            Total <strong class="text-gray-900">{{ $categories->count() }}</strong> kategori
          </span>
        </div>
      </div>

      @if($categories->count() > 0)
      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
              <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Nama Kategori</th>
              <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Deskripsi</th>
              <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Jumlah Produk</th>
              <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @foreach($categories as $category)
            <tr class="hover:bg-gray-50 transition duration-150">
              <!-- Category Name -->
              <td class="px-6 py-4">
                <div class="flex items-center space-x-3">
                  <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-[#FBE8EE] flex items-center justify-center">
                    <i class="fa-solid fa-tag text-[#D26986]"></i>
                  </div>
                  <div>
                    <p class="text-sm font-bold text-gray-900">{{ $category->name }}</p>
                  </div>
                </div>
              </td>

              <!-- Description -->
              <td class="px-6 py-4">
                <p class="text-sm text-gray-600">{{ Str::limit($category->description, 50) ?: '-' }}</p>
              </td>

              <!-- Products Count -->
              <td class="px-6 py-4 text-center">
                <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-purple-100 text-purple-800 text-sm font-bold">
                  <i class="fa-solid fa-box mr-1.5"></i>
                  {{ $category->products_count }}
                </span>
              </td>

              <!-- Actions -->
              <td class="px-6 py-4">
                <div class="flex items-center justify-center space-x-2">
                  <button @click="openEditModal({{ $category->id }}, '{{ $category->name }}', '{{ addslashes($category->description) }}')"
                     class="inline-flex items-center px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg transition text-xs font-semibold"
                     title="Edit">
                    <i class="fa-solid fa-pen"></i>
                  </button>
                  
                  <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" 
                        onsubmit="return confirm('Yakin ingin menghapus kategori {{ $category->name }}? Semua produk dalam kategori ini akan terpengaruh.')"
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

      @else
      <!-- Empty State -->
      <div class="text-center py-16">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-100 mb-4">
          <i class="fa-solid fa-tags text-4xl text-gray-400"></i>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-2">Belum ada kategori</h3>
        <p class="text-sm text-gray-500 mb-6">Mulai dengan menambahkan kategori pertama</p>
        <button @click="openAddModal()" class="inline-flex items-center px-5 py-3 bg-[#D26986] hover:bg-[#BD5773] text-white font-bold rounded-xl transition">
          <i class="fa-solid fa-plus mr-2"></i>
          Tambah Kategori
        </button>
      </div>
      @endif

    </div>
  </div>

  <!-- Add Category Modal -->
  <div x-show="showAddModal" 
       x-cloak
       @click.away="closeAddModal()"
       class="fixed inset-0 z-50 overflow-y-auto" 
       style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
      <!-- Background overlay -->
      <div x-show="showAddModal" 
           x-transition:enter="ease-out duration-300"
           x-transition:enter-start="opacity-0"
           x-transition:enter-end="opacity-100"
           x-transition:leave="ease-in duration-200"
           x-transition:leave-start="opacity-100"
           x-transition:leave-end="opacity-0"
           class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"></div>

      <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

      <!-- Modal panel -->
      <div x-show="showAddModal"
           x-transition:enter="ease-out duration-300"
           x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
           x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
           x-transition:leave="ease-in duration-200"
           x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
           x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
           class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
        
        <form method="POST" action="{{ route('admin.categories.store') }}">
          @csrf
          
          <!-- Modal Header -->
          <div class="bg-gradient-to-r from-[#D26986] to-[#BD5773] px-6 py-4">
            <h3 class="text-lg font-bold text-white">
              <i class="fa-solid fa-plus-circle mr-2"></i>
              Tambah Kategori Baru
            </h3>
          </div>

          <!-- Modal Body -->
          <div class="px-6 py-6 space-y-4">
            <!-- Name -->
            <div>
              <label for="add_name" class="block text-sm font-bold text-gray-700 mb-2">
                Nama Kategori <span class="text-red-500">*</span>
              </label>
              <input type="text" 
                     id="add_name" 
                     name="name" 
                     value="{{ old('name') }}"
                     required
                     class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#D26986] focus:ring-2 focus:ring-[#FBE8EE] text-sm"
                     placeholder="Contoh: Mochi Premium">
            </div>

            <!-- Description -->
            <div>
              <label for="add_description" class="block text-sm font-bold text-gray-700 mb-2">
                Deskripsi
              </label>
              <textarea id="add_description" 
                        name="description" 
                        rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#D26986] focus:ring-2 focus:ring-[#FBE8EE] text-sm resize-none"
                        placeholder="Deskripsi kategori...">{{ old('description') }}</textarea>
            </div>
          </div>

          <!-- Modal Footer -->
          <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3">
            <button type="button" 
                    @click="closeAddModal()"
                    class="px-4 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition text-sm">
              Batal
            </button>
            <button type="submit" 
                    class="px-5 py-2.5 bg-[#D26986] hover:bg-[#BD5773] text-white font-bold rounded-xl transition text-sm">
              <i class="fa-solid fa-save mr-2"></i>
              Simpan
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Edit Category Modal -->
  <div x-show="showEditModal" 
       x-cloak
       @click.away="closeEditModal()"
       class="fixed inset-0 z-50 overflow-y-auto" 
       style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
      <!-- Background overlay -->
      <div x-show="showEditModal" 
           x-transition:enter="ease-out duration-300"
           x-transition:enter-start="opacity-0"
           x-transition:enter-end="opacity-100"
           x-transition:leave="ease-in duration-200"
           x-transition:leave-start="opacity-100"
           x-transition:leave-end="opacity-0"
           class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"></div>

      <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

      <!-- Modal panel -->
      <div x-show="showEditModal"
           x-transition:enter="ease-out duration-300"
           x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
           x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
           x-transition:leave="ease-in duration-200"
           x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
           x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
           class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
        
        <form method="POST" :action="'/admin/categories/' + editId">
          @csrf
          @method('PUT')
          
          <!-- Modal Header -->
          <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
            <h3 class="text-lg font-bold text-white">
              <i class="fa-solid fa-pen-to-square mr-2"></i>
              Edit Kategori
            </h3>
          </div>

          <!-- Modal Body -->
          <div class="px-6 py-6 space-y-4">
            <!-- Name -->
            <div>
              <label for="edit_name" class="block text-sm font-bold text-gray-700 mb-2">
                Nama Kategori <span class="text-red-500">*</span>
              </label>
              <input type="text" 
                     id="edit_name" 
                     name="name" 
                     x-model="editName"
                     required
                     class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#D26986] focus:ring-2 focus:ring-[#FBE8EE] text-sm"
                     placeholder="Nama kategori">
            </div>

            <!-- Description -->
            <div>
              <label for="edit_description" class="block text-sm font-bold text-gray-700 mb-2">
                Deskripsi
              </label>
              <textarea id="edit_description" 
                        name="description" 
                        x-model="editDescription"
                        rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-[#D26986] focus:ring-2 focus:ring-[#FBE8EE] text-sm resize-none"
                        placeholder="Deskripsi kategori..."></textarea>
            </div>
          </div>

          <!-- Modal Footer -->
          <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3">
            <button type="button" 
                    @click="closeEditModal()"
                    class="px-4 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition text-sm">
              Batal
            </button>
            <button type="submit" 
                    class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition text-sm">
              <i class="fa-solid fa-save mr-2"></i>
              Update
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  function categoryManager() {
    return {
      showAddModal: false,
      showEditModal: false,
      editId: null,
      editName: '',
      editDescription: '',

      openAddModal() {
        this.showAddModal = true;
      },
      closeAddModal() {
        this.showAddModal = false;
      },
      openEditModal(id, name, description) {
        this.editId = id;
        this.editName = name;
        this.editDescription = description;
        this.showEditModal = true;
      },
      closeEditModal() {
        this.showEditModal = false;
        this.editId = null;
        this.editName = '';
        this.editDescription = '';
      }
    }
  }
</script>

<style>
  [x-cloak] { display: none !important; }
</style>
@endsection
