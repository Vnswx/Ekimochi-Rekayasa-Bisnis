@extends('layouts.app')

@section('title', 'Profile - Ekimochi')

@section('content')
<div class="container max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
  @if(session('success'))
  <div class="mb-6 p-4 rounded-2xl bg-green-50 border border-green-200 text-green-800 text-sm flex items-center gap-3">
    <i class="fa-solid fa-circle-check text-xl"></i>
    <span>{{ session('success') }}</span>
  </div>
  @endif

  <!-- Profile Card -->
  <div class="bg-white rounded-3xl shadow-lg border border-rose-100 overflow-hidden">
    <!-- Profile Header -->
    <div class="bg-gradient-to-r from-[#D26986] to-[#BD5773] p-8 text-white">
      <div class="flex items-center gap-6">
        <div class="w-24 h-24 rounded-full bg-white/20 flex items-center justify-center text-4xl font-bold border-4 border-white/30">
          {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div>
          <h1 class="text-3xl font-bold">{{ $user->name }}</h1>
          {{-- <p class="text-white/90 mt-1">@{{ $user->username }}</p> --}}
          <span class="inline-block mt-2 px-3 py-1 bg-white/20 rounded-full text-xs font-semibold uppercase">
            {{ $user->role }}
          </span>
        </div>
      </div>
    </div>

    <!-- Profile Body -->
    <div class="p-8">
      <h2 class="text-xl font-bold text-gray-900 mb-6 pb-3 border-b-2 border-rose-100">Informasi Akun</h2>
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div>
          <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Username</label>
          <p class="text-base font-medium text-gray-900">{{ $user->username }}</p>
        </div>
        
        <div>
          <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Email</label>
          <p class="text-base font-medium text-gray-900">{{ $user->email ?? 'Belum diisi' }}</p>
        </div>
        
        <div>
          <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Role</label>
          <p class="text-base font-medium text-gray-900">{{ ucfirst($user->role) }}</p>
        </div>
        
        <div>
          <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Bergabung Sejak</label>
          <p class="text-base font-medium text-gray-900">{{ $user->created_at->format('d M Y') }}</p>
        </div>
      </div>

      <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 bg-[#D26986] hover:bg-[#BD5773] text-white px-6 py-3 rounded-full font-semibold text-sm shadow-md transition transform active:scale-95">
        <i class="fa-solid fa-pen-to-square"></i>
        <span>Edit Profile</span>
      </a>

      @if(!$user->email)
      <div class="mt-6 p-4 bg-amber-50 border-l-4 border-amber-400 rounded-lg">
        <p class="text-sm font-semibold text-amber-800">Email belum diisi</p>
        <p class="text-xs text-amber-700 mt-1">Tambahkan email untuk menggunakan fitur reset password</p>
      </div>
      @endif

      <!-- Stats -->
      <div class="mt-8 grid grid-cols-3 gap-4">
        <div class="bg-rose-50 p-4 rounded-2xl text-center border border-rose-100">
          <p class="text-2xl font-bold text-[#D26986]">{{ $user->created_at->diffInDays(now()) }}</p>
          <p class="text-xs text-gray-600 mt-1">Hari Bergabung</p>
        </div>
        <div class="bg-rose-50 p-4 rounded-2xl text-center border border-rose-100">
          <p class="text-2xl font-bold text-[#D26986]">{{ $user->profile_photo ? '✓' : '○' }}</p>
          <p class="text-xs text-gray-600 mt-1">Foto Profile</p>
        </div>
        <div class="bg-rose-50 p-4 rounded-2xl text-center border border-rose-100">
          <p class="text-2xl font-bold text-[#D26986]">{{ $user->email ? '✓' : '○' }}</p>
          <p class="text-xs text-gray-600 mt-1">Email Verified</p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
