<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Ekimochi</title>
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
        
        /* Success Message */
        .success { background: #d4edda; color: #155724; padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #28a745; }
        
        /* Profile Card */
        .profile-card { background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); overflow: hidden; margin-bottom: 30px; }
        
        /* Profile Header */
        .profile-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px; text-align: center; color: white; }
        .avatar-container { display: flex; flex-direction: column; align-items: center; gap: 15px; }
        .avatar { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid white; box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
        .profile-name { font-size: 28px; font-weight: bold; margin-top: 10px; }
        .profile-username { font-size: 16px; opacity: 0.9; }
        .role-badge { display: inline-block; padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 600; margin-top: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
        .role-badge.admin { background: #dc3545; color: white; }
        .role-badge.user { background: #28a745; color: white; }
        
        /* Profile Body */
        .profile-body { padding: 40px; }
        .section-title { font-size: 20px; font-weight: 600; color: #333; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #f0f0f0; }
        
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px; }
        .info-item { }
        .info-label { font-size: 13px; color: #999; font-weight: 600; text-transform: uppercase; margin-bottom: 5px; letter-spacing: 0.5px; }
        .info-value { font-size: 16px; color: #333; font-weight: 500; }
        .info-value.empty { color: #999; font-style: italic; }
        
        /* Buttons */
        .btn-primary { display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 30px; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s; border: none; cursor: pointer; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3); }
        
        /* Stats */
        .stats-section { background: #f8f9fa; padding: 30px; border-radius: 8px; margin-top: 30px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
        .stat-card { background: white; padding: 20px; border-radius: 8px; text-align: center; }
        .stat-value { font-size: 32px; font-weight: bold; color: #667eea; }
        .stat-label { font-size: 14px; color: #666; margin-top: 5px; }
        
        /* Responsive */
        @media (max-width: 768px) {
            .profile-header { padding: 30px 20px; }
            .avatar { width: 100px; height: 100px; }
            .profile-name { font-size: 24px; }
            .profile-body { padding: 25px; }
            .info-grid { grid-template-columns: 1fr; gap: 15px; }
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
        <a href="{{ route('dashboard') }}" class="back-link">← Kembali ke Dashboard</a>

        @if(session('success'))
            <div class="success">✓ {{ session('success') }}</div>
        @endif

        <!-- Profile Card -->
        <div class="profile-card">
            <!-- Profile Header -->
            <div class="profile-header">
                <div class="avatar-container">
                    <img src="{{ $user->avatar }}" alt="Avatar" class="avatar">
                    <div>
                        <h1 class="profile-name">{{ $user->name }}</h1>
                        <p class="profile-username">@{{ $user->username }}</p>
                        <span class="role-badge {{ $user->role }}">{{ $user->role }}</span>
                    </div>
                </div>
            </div>

            <!-- Profile Body -->
            <div class="profile-body">
                <h2 class="section-title">Informasi Akun</h2>
                
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Username</div>
                        <div class="info-value">{{ $user->username }}</div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Email</div>
                        <div class="info-value {{ !$user->email ? 'empty' : '' }}">
                            {{ $user->email ?? 'Belum diisi' }}
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Role</div>
                        <div class="info-value">{{ ucfirst($user->role) }}</div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Bergabung Sejak</div>
                        <div class="info-value">{{ $user->created_at->format('d M Y') }}</div>
                    </div>
                </div>

                <a href="{{ route('profile.edit') }}" class="btn-primary">✏️ Edit Profile</a>

                @if(!$user->email)
                    <div style="margin-top: 20px; padding: 15px; background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 4px;">
                        <strong>⚠️ Email belum diisi</strong><br>
                        <small style="color: #856404;">Tambahkan email untuk menggunakan fitur reset password</small>
                    </div>
                @endif

                <!-- Account Stats -->
                <div class="stats-section">
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-value">{{ $user->created_at->diffInDays(now()) }}</div>
                            <div class="stat-label">Hari Bergabung</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value">{{ $user->profile_photo ? '✓' : '○' }}</div>
                            <div class="stat-label">Foto Profile</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value">{{ $user->email ? '✓' : '○' }}</div>
                            <div class="stat-label">Email Terverifikasi</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
