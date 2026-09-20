<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Ekimochi</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .navbar { background: #dc3545; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        .navbar h1 { font-size: 24px; }
        .navbar-right { display: flex; gap: 15px; align-items: center; }
        .navbar a { color: white; text-decoration: none; }
        .navbar a:hover { text-decoration: underline; }
        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
        .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .admin-badge { background: #dc3545; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: bold; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 20px; }
        .stat-card { background: #f8f9fa; padding: 20px; border-radius: 8px; text-align: center; border-left: 4px solid #dc3545; }
        .stat-card h3 { font-size: 32px; color: #dc3545; margin-bottom: 5px; }
        .stat-card p { color: #666; }
        .logout-form { display: inline; }
        .logout-btn { background: #343a40; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; }
        .logout-btn:hover { background: #23272b; }
    </style>
</head>
<body>
    <nav class="navbar">
        <h1>Ekimochi Admin</h1>
        <div class="navbar-right">
            <span>{{ Auth::user()->name }} <span class="admin-badge">ADMIN</span></span>
            <a href="{{ route('dashboard') }}">User Dashboard</a>
            <a href="{{ route('profile.show') }}">Profile</a>
            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <h2>Admin Dashboard</h2>
            <p style="color: #666;">Selamat datang di dashboard administrator Ekimochi!</p>
            
            <div class="stats">
                <div class="stat-card">
                    <h3>-</h3>
                    <p>Total Users</p>
                </div>
                <div class="stat-card">
                    <h3>-</h3>
                    <p>Active Sessions</p>
                </div>
                <div class="stat-card">
                    <h3>-</h3>
                    <p>Total Admin</p>
                </div>
            </div>

            <p style="margin-top: 30px; color: #666; font-style: italic;">
                Ini adalah halaman khusus admin. Hanya user dengan role admin yang dapat mengakses halaman ini.
            </p>
        </div>
    </div>
</body>
</html>
