# START SERVER - Ekimochi Backend

## Cara Menjalankan Server

### Option 1: Foreground (Recommended untuk Development)
```bash
php artisan serve
```
Server akan berjalan di: http://127.0.0.1:8000
Tekan Ctrl+C untuk stop.

### Option 2: Spesifik Host dan Port
```bash
php artisan serve --host=127.0.0.1 --port=8000
```

### Option 3: Custom Port
```bash
php artisan serve --port=8080
```

---

## Verifikasi Server Berjalan

```bash
# Check via curl
curl http://127.0.0.1:8000

# Check process
ps aux | grep "artisan serve"
```

---

## Troubleshooting

### Port sudah digunakan?
```bash
# Windows: Check port
netstat -ano | findstr :8000

# Kill process jika perlu
taskkill /PID [PID_NUMBER] /F

# Atau gunakan port lain
php artisan serve --port=8080
```

### Database connection error?
```bash
# Check .env database settings
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ekimochi
DB_USERNAME=root
DB_PASSWORD=

# Test connection
php artisan migrate:status
```

### Clear caches jika ada error
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

---

## Quick Start Commands

```bash
# 1. Start server
php artisan serve

# 2. Open browser
# http://127.0.0.1:8000

# 3. Login admin
# Username: admin
# Password: Admin123!

# 4. Test register
# http://127.0.0.1:8000/register
```

---

## Server Status Check

Server berjalan dengan baik jika:
- ✅ Browser dapat akses http://127.0.0.1:8000
- ✅ Login page tampil dengan styling
- ✅ Tidak ada error di terminal
- ✅ Database connected (migration status ok)

---

## Note

Server development Laravel menggunakan PHP built-in server.
Untuk production, gunakan web server proper seperti Apache atau Nginx.

---

Ready untuk testing! 🚀
