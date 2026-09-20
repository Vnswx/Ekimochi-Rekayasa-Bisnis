<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Ekimochi</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .navbar { background: #007bff; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        .navbar h1 { font-size: 24px; }
        .navbar-right { display: flex; gap: 15px; align-items: center; }
        .navbar a { color: white; text-decoration: none; }
        .navbar a:hover { text-decoration: underline; }
        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
        .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .success { color: green; padding: 10px; background: #d4edda; border-radius: 4px; margin-bottom: 15px; }
        .info-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; }
        .btn:hover { background: #0056b3; }
        .logout-form { display: inline; }
        .logout-btn { background: #dc3545; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; }
        .logout-btn:hover { background: #c82333; }
    </style>
</head>
<body>
    <nav class="navbar">
        <h1>Ekimochi</h1>
        <div class="navbar-right">
            <span>{{ Auth::user()->name }} ({{ Auth::user()->role }})</span>
            <a href="{{ route('profile.show') }}">Profile</a>
            @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
            @endif
            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </nav>

    <div class="container">
        @if(session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <h2>Dashboard User</h2>
            <p>Selamat datang di aplikasi Ekimochi!</p>
            
            <div style="margin-top: 20px;">
                <div class="info-row">
                    <strong>Nama:</strong>
                    <span>{{ Auth::user()->name }}</span>
                </div>
                <div class="info-row">
                    <strong>Username:</strong>
                    <span>{{ Auth::user()->username }}</span>
                </div>
                <div class="info-row">
                    <strong>Email:</strong>
                    <span>{{ Auth::user()->email ?? 'Belum diisi' }}</span>
                </div>
                <div class="info-row">
                    <strong>Role:</strong>
                    <span>{{ Auth::user()->role }}</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
