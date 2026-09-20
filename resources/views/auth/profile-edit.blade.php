<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Ekimochi</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f8f9fa; color: #333; }
        
        /* Navbar */
        .navbar { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px 0; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .nav-container { max-width: 1200px; margin: 0 auto; padding: 0 20px; display: flex; justify-content: space-between; align-items: center; }
        .nav-brand { font-size: 28px; font-weight: bold; text-decoration: none; color: white; }
        .nav-links { display: flex; gap: 25px; align-items: center; }
        .nav-links a { color: white; text-decoration: none; transition: opacity 0.3s; font-weight: 500; }
        .nav-links a:hover { opacity: 0.8; }
        .logout-btn { background: rgba(255,255,255,0.2); color: white; border: none; padding: 8px 20px; border-radius: 20px; cursor: pointer; font-weight: 600; transition: all 0.3s; }
        .logout-btn:hover { background: rgba(255,255,255,0.3); }
        
        /* Container */
        .container { max-width: 900px; margin: 0 auto; padding: 40px 20px; }
        .back-link { display: inline-block; color: #667eea; text-decoration: none; margin-bottom: 20px; font-weight: 600; }
        .back-link:hover { text-decoration: underline; }
        
        /* Messages */
        .success { background: #d4edda; color: #155724; padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #28a745; }
        .error { background: #f8d7da; color: #721c24; padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #dc3545; }
        .error ul { margin: 0; padding-left: 20px; }
        
        /* Card */
        .card { background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 35px; margin-bottom: 25px; }
        .section-title { font-size: 22px; font-weight: 600; color: #333; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid #667eea; display: flex; align-items: center; gap: 10px; }
        .section-icon { font-size: 24px; }
        
        /* Avatar Preview */
        .avatar-preview { display: flex; align-items: center; gap: 25px; margin-bottom: 25px; padding: 20px; background: #f8f9fa; border-radius: 8px; }
        .avatar { width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid #667eea; }
        .avatar-info h3 { font-size: 20px; margin-bottom: 5px; color: #333; }
        .avatar-info p { font-size: 14px; color: #666; }
        
        /* Form */
        .form-group { margin-bottom: 25px; }
        .form-label { display: block; margin-bottom: 8px; color: #555; font-weight: 600; font-size: 14px; }
        .form-input { width: 100%; padding: 12px 15px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 15px; transition: all 0.3s; }
        .form-input:focus { outline: none; border-color: #667eea; box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1); }
        .form-input:disabled { background: #f5f5f5; cursor: not-allowed; }
        .form-hint { font-size: 13px; color: #999; margin-top: 6px; display: block; }
        .validation-error { color: #dc3545; font-size: 13px; margin-top: 6px; display: block; }
        
        /* File Input Custom */
        .file-input-wrapper { position: relative; }
        .file-input { width: 100%; padding: 12px 15px; border: 2px dashed #e0e0e0; border-radius: 8px; cursor: pointer; transition: all 0.3s; background: #fafafa; }
        .file-input:hover { border-color: #667eea; background: #f0f4ff; }
        
        /* Buttons */
        .btn { padding: 12px 28px; border-radius: 8px; font-weight: 600; font-size: 15px; cursor: pointer; transition: all 0.3s; border: none; text-decoration: none; display: inline-block; }
        .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3); }
        .btn-secondary { background: #6c757d; color: white; margin-left: 10px; }
        .btn-secondary:hover { background: #5a6268; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-danger:hover { background: #c82333; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3); }
        
        /* Info Box */
        .info-box { background: #e7f3ff; border-left: 4px solid #2196F3; padding: 15px 20px; border-radius: 4px; margin-bottom: 20px; }
        .info-box.warning { background: #fff3cd; border-left-color: #ffc107; }
        .info-box strong { display: block; margin-bottom: 5px; }
        .info-box small { color: #666; }
        
        /* Responsive */
        @media (max-width: 768px) {
            .container { padding: 20px 15px; }
            .card { padding: 25px; }
            .avatar-preview { flex-direction: column; text-align: center; }
            .btn { width: 100%; margin: 5px 0 !important; }
            .nav-links { gap: 15px; font-size: 14px; }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="/" class="nav-brand">🍡 Ekimochi</a>
            <div class="nav-links">
                <a href="{{ route('catalog.index') }}">Katalog</a>
                <a href="{{ route('dashboard') }}">Dashboard</a>
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.products.index') }}">Admin</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <a href="{{ route('profile.show') }}" class="back-link">← Kembali ke Profile</a>

        @if(session('success'))
            <div class="success">✓ {{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="error">
                <strong>Terjadi kesalahan:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Profile Photo Section -->
        <div class="card">
            <h2 class="section-title">
                <span class="section-icon">📸</span>
                Foto Profile
            </h2>
            
            <div class="avatar-preview">
                <img src="{{ $user->avatar }}" alt="Avatar" class="avatar" id="avatarPreview">
                <div class="avatar-info">
                    <h3>{{ $user->name }}</h3>
                    <p>@{{ $user->username }} • {{ ucfirst($user->role) }}</p>
                    @if(!$user->profile_photo)
                        <p style="font-size: 12px; color: #999; margin-top: 5px;">Default avatar - huruf "{{ $user->initial }}"</p>
                    @endif
                </div>
            </div>

            <form method="POST" action="{{ route('profile.photo.upload') }}" enctype="multipart/form-data" style="margin-bottom: 15px;">
                @csrf
                <div class="form-group">
                    <label for="photo" class="form-label">Upload Foto Baru</label>
                    <input type="file" id="photo" name="photo" accept="image/*" class="file-input" onchange="previewImage(event)">
                    <small class="form-hint">Format: JPG, PNG, GIF. Maksimal 2MB</small>
                    @error('photo')
                        <span class="validation-error">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">📤 Upload Foto</button>
            </form>

            @if($user->profile_photo)
                <form method="POST" action="{{ route('profile.photo.delete') }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Hapus foto profile dan kembali ke avatar default?')">🗑️ Hapus Foto</button>
                </form>
            @endif
        </div>

        <!-- Basic Info Section -->
        <div class="card">
            <h2 class="section-title">
                <span class="section-icon">👤</span>
                Informasi Dasar
            </h2>
            
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name" class="form-label">Nama Lengkap *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="form-input">
                    @error('name')
                        <span class="validation-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="username" class="form-label">Username *</label>
                    <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" required class="form-input">
                    <small class="form-hint">Username harus unik dan tidak boleh sama dengan user lain</small>
                    @error('username')
                        <span class="validation-error">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
            </form>
        </div>

        <!-- Email Section -->
        <div class="card">
            <h2 class="section-title">
                <span class="section-icon">📧</span>
                Email Account
            </h2>
            
            @if(!$user->email)
                <div class="info-box warning">
                    <strong>⚠️ Email belum diisi</strong>
                    <small>Email diperlukan untuk menggunakan fitur reset password</small>
                </div>
            @endif

            <form method="POST" action="{{ route('profile.email') }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" placeholder="contoh@email.com" class="form-input">
                    <small class="form-hint">Email harus unik dan valid</small>
                    @error('email')
                        <span class="validation-error">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    {{ $user->email ? '✏️ Update Email' : '➕ Tambah Email' }}
                </button>
            </form>
        </div>

        <!-- Security Section -->
        <div class="card">
            <h2 class="section-title">
                <span class="section-icon">🔒</span>
                Security - Ubah Password
            </h2>
            
            <div class="info-box">
                <strong>🛡️ Keamanan Password</strong>
                <small>Gunakan password yang kuat dengan minimal 8 karakter</small>
            </div>

            <form method="POST" action="{{ route('profile.password') }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="current_password" class="form-label">Password Saat Ini *</label>
                    <input type="password" id="current_password" name="current_password" required class="form-input">
                    @error('current_password')
                        <span class="validation-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password Baru *</label>
                    <input type="password" id="password" name="password" required class="form-input">
                    <small class="form-hint">Minimal 8 karakter</small>
                    @error('password')
                        <span class="validation-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password Baru *</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required class="form-input">
                </div>

                <button type="submit" class="btn btn-primary">🔐 Ubah Password</button>
            </form>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatarPreview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>
