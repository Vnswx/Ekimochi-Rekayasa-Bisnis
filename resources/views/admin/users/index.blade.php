@extends('layouts.admin')

@section('title', 'Kelola User - Admin Ekimochi')

@section('content')
<div class="py-8">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- Header & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
      <div>
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Kelola User</h1>
        <p class="text-sm text-gray-500 mt-1">Manajemen user dan hak akses</p>
      </div>
      <a href="{{ route('admin.users.create') }}" class="mt-4 sm:mt-0 inline-flex items-center justify-center px-5 py-3 bg-[#D26986] hover:bg-[#BD5773] text-white font-bold rounded-xl transition transform active:scale-95 shadow-lg text-sm">
        <i class="fa-solid fa-user-plus mr-2"></i>
        Tambah User
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
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-500">Total User</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $users->total() }}</p>
          </div>
          <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center">
            <i class="fa-solid fa-users text-2xl text-blue-600"></i>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-500">Admin</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\User::where('role', 'admin')->count() }}</p>
          </div>
          <div class="w-14 h-14 bg-rose-100 rounded-full flex items-center justify-center">
            <i class="fa-solid fa-user-shield text-2xl text-rose-600"></i>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-500">User Biasa</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\User::where('role', 'user')->count() }}</p>
          </div>
          <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center">
            <i class="fa-solid fa-user text-2xl text-green-600"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Users Table - Desktop View -->
    <div class="hidden lg:block bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">User</th>
              <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Username</th>
              <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Email</th>
              <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Role</th>
              <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Bergabung</th>
              <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @forelse($users as $user)
            <tr class="hover:bg-gray-50 transition">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  @if($user->profile_photo)
                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full object-cover">
                  @else
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#D26986] to-[#BD5773] flex items-center justify-center text-white font-bold">
                      {{ $user->initial }}
                    </div>
                  @endif
                  <div class="ml-4">
                    <div class="text-sm font-semibold text-gray-900">{{ $user->name }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-700">{{ $user->username }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-700">{{ $user->email ?? '-' }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                @if($user->role === 'admin')
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-800">
                    <i class="fa-solid fa-shield-halved mr-1"></i>
                    Admin
                  </span>
                @else
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                    <i class="fa-solid fa-user mr-1"></i>
                    User
                  </span>
                @endif
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-700">{{ $user->created_at->format('d M Y') }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold rounded-lg transition">
                    <i class="fa-solid fa-pen mr-1"></i>
                    Edit
                  </a>
                  
                  @if(Auth::id() !== $user->id)
                  <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user {{ $user->name }}?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded-lg transition">
                      <i class="fa-solid fa-trash mr-1"></i>
                      Hapus
                    </button>
                  </form>
                  @else
                  <span class="inline-flex items-center px-3 py-2 bg-gray-200 text-gray-500 text-xs font-semibold rounded-lg cursor-not-allowed" title="Tidak bisa menghapus akun sendiri">
                    <i class="fa-solid fa-ban mr-1"></i>
                    Hapus
                  </span>
                  @endif
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="6" class="px-6 py-12 text-center">
                <div class="flex flex-col items-center justify-center text-gray-400">
                  <i class="fa-solid fa-users text-5xl mb-4"></i>
                  <p class="text-lg font-medium">Tidak ada user</p>
                  <p class="text-sm mt-1">Belum ada user yang terdaftar</p>
                </div>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      @if($users->hasPages())
      <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
        {{ $users->links() }}
      </div>
      @endif
    </div>

    <!-- Users Cards - Mobile View -->
    <div class="lg:hidden space-y-4">
      @forelse($users as $user)
      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4">
        <!-- User Header -->
        <div class="flex items-center gap-3 mb-4">
          @if($user->profile_photo)
            <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-14 h-14 rounded-full object-cover">
          @else
            <div class="w-14 h-14 rounded-full bg-gradient-to-br from-[#D26986] to-[#BD5773] flex items-center justify-center text-xl font-bold text-white">
              {{ $user->initial }}
            </div>
          @endif
          <div class="flex-1">
            <h3 class="text-base font-bold text-gray-900">{{ $user->name }}</h3>
            <p class="text-sm text-gray-500">@{{ $user->username }}</p>
          </div>
          @if($user->role === 'admin')
            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-800">
              <i class="fa-solid fa-shield-halved mr-1"></i>
              Admin
            </span>
          @else
            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
              <i class="fa-solid fa-user mr-1"></i>
              User
            </span>
          @endif
        </div>

        <!-- User Info -->
        <div class="space-y-2 mb-4 pb-4 border-b border-gray-200">
          <div class="flex items-start gap-2">
            <i class="fa-solid fa-envelope text-gray-400 text-sm mt-0.5"></i>
            <div class="flex-1">
              <p class="text-xs text-gray-500">Email</p>
              <p class="text-sm text-gray-700">{{ $user->email ?? 'Belum diisi' }}</p>
            </div>
          </div>
          <div class="flex items-start gap-2">
            <i class="fa-solid fa-calendar text-gray-400 text-sm mt-0.5"></i>
            <div class="flex-1">
              <p class="text-xs text-gray-500">Bergabung</p>
              <p class="text-sm text-gray-700">{{ $user->created_at->format('d M Y') }}</p>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-2">
          <a href="{{ route('admin.users.edit', $user) }}" class="flex-1 inline-flex items-center justify-center px-4 py-2.5 bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold rounded-xl transition">
            <i class="fa-solid fa-pen mr-2"></i>
            Edit
          </a>
          
          @if(Auth::id() !== $user->id)
          <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user {{ $user->name }}?');" class="flex-1">
            @csrf
            @method('DELETE')
            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded-xl transition">
              <i class="fa-solid fa-trash mr-2"></i>
              Hapus
            </button>
          </form>
          @else
          <button disabled class="flex-1 inline-flex items-center justify-center px-4 py-2.5 bg-gray-200 text-gray-500 text-sm font-semibold rounded-xl cursor-not-allowed">
            <i class="fa-solid fa-ban mr-2"></i>
            Hapus
          </button>
          @endif
        </div>
      </div>
      @empty
      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8">
        <div class="flex flex-col items-center justify-center text-gray-400">
          <i class="fa-solid fa-users text-5xl mb-4"></i>
          <p class="text-lg font-medium">Tidak ada user</p>
          <p class="text-sm mt-1">Belum ada user yang terdaftar</p>
        </div>
      </div>
      @endforelse

      <!-- Pagination Mobile -->
      @if($users->hasPages())
      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4">
        {{ $users->links() }}
      </div>
      @endif
    </div>

  </div>
</div>
@endsection
