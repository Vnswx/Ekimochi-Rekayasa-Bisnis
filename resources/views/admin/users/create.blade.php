@extends('layouts.admin')

@section('title', 'Tambah User - Admin Ekimochi')

@section('content')
<div class="py-8">
  <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- Back Button -->
    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-[#D26986] hover:text-[#BD5773] font-semibold text-sm mb-6">
      <i class="fa-solid fa-arrow-left mr-2"></i>
      Kembali ke Daftar User
    </a>

    <!-- Header -->
    <div class="mb-6 sm:mb-8">
      <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Tambah User Baru</h1>
      <p class="text-sm text-gray-500 mt-1">Buat akun user baru untuk sistem</p>
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
      <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        <div class="p-4 sm:p-8 space-y-6">
          
          <!-- Name -->
          <div>
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
              Nama Lengkap <span class="text-red-500">*</span>
            </label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#D26986] focus:ring-2 focus:ring-[#D26986]/20 transition @error('name') border-red-500 @enderror">
            @error('name')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Username -->
          <div>
            <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">
              Username <span class="text-red-500">*</span>
            </label>
            <input type="text" id="username" name="username" value="{{ old('username') }}" required
                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#D26986] focus:ring-2 focus:ring-[#D26986]/20 transition @error('username') border-red-500 @enderror">
            <p class="text-xs text-gray-500 mt-1">Username harus unik</p>
            @error('username')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Email -->
          <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
              Email
            </label>
            <input type="email" id="email" name="email" value="{{ old('email') }}"
                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#D26986] focus:ring-2 focus:ring-[#D26986]/20 transition @error('email') border-red-500 @enderror">
            <p class="text-xs text-gray-500 mt-1">Email opsional, tapi diperlukan untuk reset password</p>
            @error('email')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Password -->
          <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
              Password <span class="text-red-500">*</span>
            </label>
            <input type="password" id="password" name="password" required
                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#D26986] focus:ring-2 focus:ring-[#D26986]/20 transition @error('password') border-red-500 @enderror">
            <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
            @error('password')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Password Confirmation -->
          <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
              Konfirmasi Password <span class="text-red-500">*</span>
            </label>
            <input type="password" id="password_confirmation" name="password_confirmation" required
                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#D26986] focus:ring-2 focus:ring-[#D26986]/20 transition">
          </div>

          <!-- Role -->
          <div>
            <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">
              Role <span class="text-red-500">*</span>
            </label>
            <select id="role" name="role" required
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#D26986] focus:ring-2 focus:ring-[#D26986]/20 transition @error('role') border-red-500 @enderror">
              <option value="">Pilih Role</option>
              <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User</option>
              <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            <p class="text-xs text-gray-500 mt-1">Admin memiliki akses penuh ke dashboard admin</p>
            @error('role')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

        </div>

        <!-- Footer Actions -->
        <div class="bg-gray-50 px-4 sm:px-8 py-4 sm:py-5 border-t border-gray-200 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
          <a href="{{ route('admin.users.index') }}" class="inline-flex items-center justify-center px-5 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition order-2 sm:order-1">
            <i class="fa-solid fa-times mr-2"></i>
            Batal
          </a>
          <button type="submit" class="inline-flex items-center justify-center px-5 py-3 bg-[#D26986] hover:bg-[#BD5773] text-white font-semibold rounded-xl transition transform active:scale-95 shadow-lg order-1 sm:order-2">
            <i class="fa-solid fa-floppy-disk mr-2"></i>
            Simpan User
          </button>
        </div>
      </form>
    </div>

  </div>
</div>
@endsection
