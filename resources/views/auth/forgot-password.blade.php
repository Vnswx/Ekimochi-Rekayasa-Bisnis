<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Lupa Password - Ekimochi</title>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com" rel="preconnect"/>
  <link crossorigin href="https://fonts.gstatic.com" rel="preconnect"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet"/>
  <script src="https://cdn.tailwindcss.com"></script>
  <script id="tailwind-config">
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "surface-container-low": "#feefff",
            "surface-container-lowest": "#ffffff",
            "surface-container": "#fae8fd",
            "surface-container-high": "#f5e2f7",
            "on-surface": "#221826",
            "on-surface-variant": "#574048",
            "primary": "#b10e6b",
            "primary-container": "#d23284",
            "on-primary": "#ffffff",
            "primary-fixed": "#ffd9e4",
            "on-primary-fixed": "#3e0022",
            "secondary": "#00668a",
            "secondary-fixed": "#c4e7ff",
            "on-secondary-fixed": "#001e2c",
            "background": "#fff7fc",
            "outline-variant": "#debec8"
          }
        }
      }
    };
  </script>
  <style>
    html, body {
      margin: 0;
      padding: 0;
      height: 100vh;
      overflow: hidden;
    }
  </style>
</head>
<body class="bg-background font-['Inter'] text-on-surface antialiased flex items-center justify-center p-4 lg:p-6">

  <main class="w-full max-w-6xl h-[92vh] max-h-[720px] bg-surface-container-lowest rounded-3xl shadow-2xl overflow-hidden grid grid-cols-1 lg:grid-cols-12 border border-outline-variant/30">
    
    <!-- Kolom Kiri: Visual Atmosfer Produk (5 Kolom) -->
    <div class="hidden lg:flex lg:col-span-5 relative bg-primary-fixed/30 flex-col justify-between p-8 overflow-hidden">
      <!-- Background Image dengan Overlay Elegan -->
      <div class="absolute inset-0 z-0">
        <img class="w-full h-full object-cover transform scale-105 hover:scale-100 transition-transform duration-1000" src="https://images.unsplash.com/photo-1563805042-7684c019e1cb?auto=format&fit=crop&w=1000&q=80" alt="Artisanal Wagashi Spread"/>
        <div class="absolute inset-0 bg-gradient-to-t from-[#221826]/80 via-[#221826]/20 to-transparent"></div>
      </div>

      <!-- Header Brand di atas Gambar -->
      <div class="relative z-10 flex items-center justify-between">
        <div class="bg-white/80 backdrop-blur-md px-4 py-1.5 rounded-full shadow-sm">
          <span class="text-xs font-bold tracking-widest text-primary uppercase">Edisi Musim Semi</span>
        </div>
        <span class="material-symbols-outlined text-white/80">bakery_dining</span>
      </div>

      <!-- Konten Footer di dalam Gambar -->
      <div class="relative z-10 text-white space-y-2">
        <h3 class="font-['Plus_Jakarta_Sans'] text-2xl font-bold tracking-tight">Artisanal Mochi & Wagashi</h3>
        <p class="text-sm text-white/80 leading-relaxed font-light">
          Nikmati kelembutan mochi autentik Jepang yang dibuat segar setiap hari dengan bahan-bahan premium pilihan.
        </p>
        <div class="flex items-center gap-2 pt-2 text-xs text-secondary-fixed">
          <span class="material-symbols-outlined text-[16px]">verified</span>
          <span>Pengiriman berpendingin aman sampai tujuan</span>
        </div>
      </div>
    </div>

    <!-- Kolom Kanan: Form Auth (7 Kolom) -->
    <div class="lg:col-span-7 p-6 sm:p-8 lg:p-10 flex flex-col justify-between bg-surface-container-lowest overflow-y-auto">
      
      <!-- Top Bar: Navigasi Kembali -->
      <div>
        <div class="flex items-center justify-between mb-6">
          <a class="inline-flex items-center gap-1.5 text-on-surface-variant hover:text-primary transition-colors text-xs font-semibold px-3 py-1.5 rounded-full bg-surface-container-low" href="{{ route('login') }}">
            <span class="material-symbols-outlined text-[16px]">arrow_back</span>
            <span>Kembali ke Login</span>
          </a>
        </div>

        <!-- Heading Form -->
        <div class="mb-5">
          <h2 class="font-['Plus_Jakarta_Sans'] text-xl sm:text-2xl font-bold text-on-surface tracking-tight">
            Lupa Password?
          </h2>
          <p class="text-xs sm:text-sm text-on-surface-variant mt-0.5">
            Masukkan email yang terdaftar. Kami akan mengirimkan link untuk reset password Anda.
          </p>
        </div>

        <!-- Info Box -->
        <div class="mb-4 p-3 rounded-2xl bg-blue-50 border border-blue-200 text-blue-800 text-xs flex items-start gap-2">
          <span class="material-symbols-outlined text-[16px]">info</span>
          <div class="flex-1">
            Link reset password akan dikirim ke email Anda dan berlaku selama 60 menit.
          </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="mb-4 p-3 rounded-2xl bg-green-50 border border-green-200 text-green-800 text-xs flex items-center gap-2">
          <span class="material-symbols-outlined text-[16px]">check_circle</span>
          <span>{{ session('success') }}</span>
        </div>
        @endif

        @if(session('status'))
        <div class="mb-4 p-3 rounded-2xl bg-green-50 border border-green-200 text-green-800 text-xs flex items-center gap-2">
          <span class="material-symbols-outlined text-[16px]">check_circle</span>
          <span>{{ session('status') }}</span>
        </div>
        @endif

        @if($errors->any())
        <div class="mb-4 p-3 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-xs flex items-start gap-2">
          <span class="material-symbols-outlined text-[16px]">error</span>
          <div class="flex-1">
            @foreach($errors->all() as $error)
              <p>{{ $error }}</p>
            @endforeach
          </div>
        </div>
        @endif

        <!-- Form Forgot Password -->
        <form method="POST" action="{{ route('password.email') }}" class="space-y-3">
          @csrf
          
          <div>
            <label class="block text-xs font-semibold text-on-surface mb-1">Alamat Email</label>
            <div class="relative flex items-center">
              <span class="material-symbols-outlined absolute left-3.5 text-on-surface-variant text-[18px]">mail</span>
              <input 
                name="email" 
                value="{{ old('email') }}"
                class="w-full pl-10 pr-3 py-2.5 rounded-full bg-surface-container-low text-on-surface placeholder:text-on-surface-variant/50 text-xs focus:bg-surface-container-lowest focus:outline-none focus:ring-2 focus:ring-primary/20 shadow-sm transition-all @error('email') ring-2 ring-red-400 @enderror" 
                placeholder="contoh@gmail.com" 
                required 
                type="email"
                autofocus
              />
            </div>
            @error('email')
            <p class="text-xs text-red-600 mt-1 ml-3">{{ $message }}</p>
            @enderror
          </div>

          <!-- Submit Button -->
          <button class="w-full mt-2 flex items-center justify-center gap-2 py-2.5 px-6 rounded-full bg-primary hover:bg-primary-container text-on-primary text-xs font-bold shadow-md hover:shadow-lg transition-all" type="submit">
            <span class="material-symbols-outlined text-[16px]">mail</span>
            <span>Kirim Link Reset Password</span>
          </button>
        </form>

        <!-- Back to Login Link -->
        <div class="mt-4 text-center text-xs text-on-surface-variant">
          Ingat password Anda? 
          <a href="{{ route('login') }}" class="text-primary font-semibold hover:underline">Masuk di sini</a>
        </div>
      </div>

      <!-- Footer Info Keamanan -->
      <div class="pt-4 mt-4 border-t border-surface-container-high flex items-center justify-between text-[11px] text-on-surface-variant">
        <div class="flex items-center gap-1.5">
          <span class="material-symbols-outlined text-[14px] text-secondary">verified_user</span>
          <span>Enkripsi SSL 256-bit</span>
        </div>
        <a class="flex items-center gap-1 text-primary hover:underline font-medium" href="https://wa.me/6281200000000" target="_blank">
          <span class="material-symbols-outlined text-[14px]">chat</span>
          <span>Bantuan CS</span>
        </a>
      </div>

    </div>
  </main>

</body>
</html>
