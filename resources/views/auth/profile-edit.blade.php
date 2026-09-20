<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Ekimochi</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .navbar { background: #007bff; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        .navbar h1 { font-size: 24px; }
        .navbar-right { display: flex; gap: 15px; align-items: center; }
        .navbar a { color: white; text-decoration: none; }
        .container { max-width: 800px; margin: 30px auto; padding: 0 20px; }
        .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .success { color: green; padding: 10px; background: #d4edda; border-radius: 4px; margin-bottom: 15px; }
        .error { color: red; padding: 10px; background: #f8d7da; border-radius: 4px; margin-bottom: 15px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; color: #555; font-weight: bold; }
        input[type="text"], input[type="email"], input[type="password"], input[type="file"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        button, .btn { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
        button:hover, .btn:hover { background: #0056b3; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
        .back-link { display: inline-block; margin-bottom: 20px; color: #007bff; }
        .section-title { font-size: 20px; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 2px solid #007bff; }
        .avatar-container { display: flex; align-items: center; gap: 20px; margin-bottom: 20px; }
        .avatar { width: 100px; height: 100px; border-radius: 50%; object-fit: cover; }
        .validation-error { color: red; font-size: 14px; margin-top: 5px; }
        .info-text { color: #666; font-size: 14px; margin-top: 5px; }
        .logout-btn { background: #dc3545; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <nav class="navbar">
        <h1>Ekimochi</h1>
        <div class="navbar-right">
            <span>{{ Auth::user()->name }}</span>
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </nav>

    <div class="container">
        <a href="{{ route('profile.show') }}" class="back-link">← Kembali ke Profile</a>

        @if(session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="error">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Profile Photo Section -->
        <div class="card">
            <h2 class="section-title">Foto Profile</h2>
            
            <div class="avatar-container">
                @if($user->profile_photo)
                    <img src="{{ $user->avatar }}" alt="Profile" class="avatar">
                @else
                    <img src="{{ $user->avatar }}" alt="Avatar" class="avatar">
                @endif
                <div>
                    <p><strong>{{ $user->name }}</strong></p>
                    <p style="color: #666; font-size: 14px;">{{ $user->initial }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('profile.photo.upload') }}" enctype="multipart/form-data" style="margin-bottom: 15px;">
                @csrf
                <div class="form-group">
                    <label for="photo">Upload Foto Baru</label>
                    <input type="file" id="photo" name="photo" accept="image/*">
                    <small class="info-text">Format: JPG, PNG. Maksimal 2MB</small>
                </div>
                <button type="submit">Upload Foto</button>
            </form>

            @if($user->profile_photo)
                <form method="POST" action="{{ route('profile.photo.delete') }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger" onclick="return confirm('Hapus foto profile?')">Hapus Foto</button>
                </form>
            @endif
        </div>

        <!-- Basic Info Section -->
        <div class="card">
            <h2 class="section-title">Informasi Dasar</h2>
            
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="validation-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" required>
                    @error('username')
                        <div class="validation-error">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit">Simpan Perubahan</button>
            </form>
        </div>

        <!-- Email Section -->
        <div class="card">
            <h2 class="section-title">Email Account</h2>
            
            @if(!$user->email)
                <p class="info-text" style="margin-bottom: 15px;">
                    Email diperlukan untuk menggunakan fitur reset password.
                </p>
            @endif

            <form method="POST" action="{{ route('profile.email') }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" placeholder="Masukkan email">
                    @error('email')
                        <div class="validation-error">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit">{{ $user->email ? 'Update Email' : 'Tambah Email' }}</button>
            </form>
        </div>

        <!-- Security Section -->
        <div class="card">
            <h2 class="section-title">Security - Ubah Password</h2>
            
            <form method="POST" action="{{ route('profile.password') }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="current_password">Password Saat Ini</label>
                    <input type="password" id="current_password" name="current_password" required>
                    @error('current_password')
                        <div class="validation-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password Baru</label>
                    <input type="password" id="password" name="password" required>
                    @error('password')
                        <div class="validation-error">{{ $message }}</div>
                    @enderror
                    <small class="info-text">Minimal 8 karakter</small>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password Baru</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required>
                </div>

                <button type="submit">Ubah Password</button>
            </form>
        </div>
    </div>
</body>
</html>
