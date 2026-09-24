@extends('layouts.app')

@section('title', 'Edit Profile - Ekimochi')

@section('content')
<div class="container max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
  <a href="{{ route('profile.show') }}" class="inline-flex items-center text-[#D26986] hover:text-[#BD5773] font-semibold text-sm mb-6">
    <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Profile
  </a>

  @if(session('success'))
  <div class="mb-6 p-4 rounded-2xl bg-green-50 border border-green-200 text-green-800 text-sm flex items-center gap-3">
    <i class="fa-solid fa-circle-check text-xl"></i>
    <span>{{ session('success') }}</span>
  </div>
  @endif

  @if($errors->any())
  <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-sm">
    <p class="font-semibold mb-2">Terjadi kesalahan:</p>
    <ul class="list-disc list-inside space-y-1">
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
  @endif

  <!-- Basic Info Card -->
  <div class="bg-white rounded-3xl shadow-lg border border-rose-100 p-8 mb-6">
    <h2 class="text-xl font-bold text-gray-900 mb-6 pb-3 border-b-2 border-rose-100 flex items-center gap-2">
      <i class="fa-solid fa-user text-[#D26986]"></i>
      Informasi Dasar
    </h2>
    
    <form method="POST" action="{{ route('profile.update') }}">
      @csrf
      @method('PUT')

      <div class="space-y-5">
        <div>
          <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap *</label>
          <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required 
                 class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#D26986] focus:ring-2 focus:ring-[#D26986]/20 transition">
          @error('name')
            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
          @enderror
        </div>

        <div>
          <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">Username *</label>
          <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" required 
                 class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#D26986] focus:ring-2 focus:ring-[#D26986]/20 transition">
          <p class="text-xs text-gray-500 mt-1">Username harus unik</p>
          @error('username')
            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
          @enderror
        </div>

        <button type="submit" class="inline-flex items-center gap-2 bg-[#D26986] hover:bg-[#BD5773] text-white px-6 py-3 rounded-full font-semibold text-sm shadow-md transition transform active:scale-95">
          <i class="fa-solid fa-floppy-disk"></i>
          Simpan Perubahan
        </button>
      </div>
    </form>
  </div>

  <!-- Email Card -->
  <div class="bg-white rounded-3xl shadow-lg border border-rose-100 p-8 mb-6">
    <h2 class="text-xl font-bold text-gray-900 mb-6 pb-3 border-b-2 border-rose-100 flex items-center gap-2">
      <i class="fa-solid fa-envelope text-[#D26986]"></i>
      Email Account
    </h2>
    
    @if(!$user->email)
    <div class="mb-4 p-4 bg-amber-50 border-l-4 border-amber-400 rounded-lg">
      <p class="text-sm font-semibold text-amber-800"> Email belum diisi</p>
      <p class="text-xs text-amber-700 mt-1">Email diperlukan untuk reset password</p>
    </div>
    @endif

    <form method="POST" action="{{ route('profile.email') }}">
      @csrf
      @method('PUT')

      <div class="space-y-5">
        <div>
          <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
          <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" placeholder="contoh@email.com"
                 class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#D26986] focus:ring-2 focus:ring-[#D26986]/20 transition">
          <p class="text-xs text-gray-500 mt-1">Email harus unik dan valid</p>
          @error('email')
            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
          @enderror
        </div>

        <button type="submit" class="inline-flex items-center gap-2 bg-[#D26986] hover:bg-[#BD5773] text-white px-6 py-3 rounded-full font-semibold text-sm shadow-md transition transform active:scale-95">
          <i class="fa-solid fa-{{ $user->email ? 'pen' : 'plus' }}"></i>
          {{ $user->email ? 'Update Email' : 'Tambah Email' }}
        </button>
      </div>
    </form>
  </div>

  <!-- Password Card -->
  <div class="bg-white rounded-3xl shadow-lg border border-rose-100 p-8">
    <h2 class="text-xl font-bold text-gray-900 mb-6 pb-3 border-b-2 border-rose-100 flex items-center gap-2">
      <i class="fa-solid fa-lock text-[#D26986]"></i>
      Ubah Password
    </h2>
    
    <div class="mb-4 p-4 bg-blue-50 border-l-4 border-blue-400 rounded-lg">
      <p class="text-sm font-semibold text-blue-800"> Keamanan Password</p>
      <p class="text-xs text-blue-700 mt-1">Gunakan password kuat minimal 8 karakter</p>
    </div>

    <form method="POST" action="{{ route('profile.password') }}">
      @csrf
      @method('PUT')

      <div class="space-y-5">
        <div>
          <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-2">Password Saat Ini *</label>
          <input type="password" id="current_password" name="current_password" required 
                 class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#D26986] focus:ring-2 focus:ring-[#D26986]/20 transition">
          @error('current_password')
            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
          @enderror
        </div>

        <div>
          <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password Baru *</label>
          <input type="password" id="password" name="password" required 
                 class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#D26986] focus:ring-2 focus:ring-[#D26986]/20 transition">
          <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
          @error('password')
            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
          @enderror
        </div>

        <div>
          <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password Baru *</label>
          <input type="password" id="password_confirmation" name="password_confirmation" required 
                 class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#D26986] focus:ring-2 focus:ring-[#D26986]/20 transition">
        </div>

        <button type="submit" class="inline-flex items-center gap-2 bg-[#D26986] hover:bg-[#BD5773] text-white px-6 py-3 rounded-full font-semibold text-sm shadow-md transition transform active:scale-95">
          <i class="fa-solid fa-shield-halved"></i>
          Ubah Password
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
