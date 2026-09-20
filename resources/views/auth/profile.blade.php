<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Ekimochi</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .navbar { background: #007bff; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        .navbar h1 { font-size: 24px; }
        .navbar-right { display: flex; gap: 15px; align-items: center; }
        .navbar a { color: white; text-decoration: none; }
        .navbar a:hover { text-decoration: underline; }
        .container { max-width: 800px; margin: 30px auto; padding: 0 20px; }
        .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .success { color: green; padding: 10px; background: #d4edda; border-radius: 4px; margin-bottom: 15px; }
        .avatar-container { display: flex; align-items: center; gap: 20px; margin-bottom: 30px; }
        .avatar { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; }
        .info-row { display: flex; justify-content: space-between; padding: 15px 0; border-bottom: 1px solid #eee; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; margin-top: 20px; }
        .btn:hover { background: #0056b3; }
        .back-link { display: inline-block; margin-bottom: 20px; color: #007bff; text-decoration: none; }
        .logout-form { display: inline; }
        .logout-btn { background: #dc3545; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; }
        .badge { padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: bold; }
        .badge-admin { background: #dc3545; color: white; }
        .badge-user { background: #28a745; color: white; }
    </style>
</head>
<body>
    <nav class="navbar">
        <h1>Ekimochi</h1>
        <div class="navbar-right">
            <span>{{ Auth::user()->name }}</span>
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </nav>

    <div class="container">
        <a href="{{ route('dashboard') }}" class="back-link">← Kembali ke Dashboard</a>

        @if(session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <h2 style="margin-bottom: 20px;">Profile Saya</h2>
            
            <div class="avatar-container">
                <img src="{{ $user->avatar }}" alt="Avatar" class="avatar">
                <div>
                    <h3>{{ $user->name }}</h3>
                    <p style="color: #666;">@{{ $user->username }}</p>
                    <span class="badge {{ $user->isAdmin() ? 'badge-admin' : 'badge-user' }}">
                        {{ strtoupper($user->role) }}
                    </span>
                </div>
            </div>

            <div>
                <div class="info-row">
                    <strong>Username:</strong>
                    <span>{{ $user->username }}</span>
                </div>
                <div class="info-row">
                    <strong>Email:</strong>
                    <span>{{ $user->email ?? 'Belum diisi' }}</span>
                </div>
                <div class="info-row">
                    <strong>Role:</strong>
                    <span>{{ ucfirst($user->role) }}</span>
                </div>
                <div class="info-row">
                    <strong>Bergabung:</strong>
                    <span>{{ $user->created_at->format('d M Y') }}</span>
                </div>
            </div>

            <a href="{{ route('profile.edit') }}" class="btn">Edit Profile</a>
        </div>
    </div>
</body>
</html>
