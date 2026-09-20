# Backend Ekimochi - Sistem Autentikasi

Backend aplikasi Ekimochi menggunakan Laravel 10 dengan sistem autentikasi lengkap.

## Fitur yang Sudah Diimplementasikan

### 1. Database & Model
- ✅ Tabel users dengan kolom: id, name, username, email (nullable), password, role, email_verified_at, remember_token, timestamps
- ✅ Role: admin dan user (default: user)
- ✅ Password di-hash menggunakan Laravel Hash (bcrypt)
- ✅ Email nullable untuk fleksibilitas registrasi

### 2. Autentikasi
- ✅ **Register**: User dapat mendaftar dengan name, username, dan password
- ✅ **Login**: Menggunakan username dan password (session-based authentication)
- ✅ **Logout**: Menghapus session dan regenerate token
- ✅ **Remember Me**: Fitur ingat saya saat login

### 3. Authorization
- ✅ Middleware admin untuk memproteksi route khusus admin
- ✅ Method helper: `isAdmin()` dan `isUser()` pada User model
- ✅ Role tidak dapat diubah saat register (selalu user)
- ✅ Admin hanya dibuat via seeder (keamanan)

### 4. Profile Management
- ✅ Halaman profile untuk melihat dan edit informasi user
- ✅ User dapat menambahkan email melalui halaman profile
- ✅ Username tidak dapat diubah setelah registrasi
- ✅ Validasi email unique saat update profile

### 5. Password Reset
- ✅ Forgot password dengan mengirim link ke email
- ✅ Reset password dengan token yang valid
- ✅ Validasi token dan email
- ✅ Password baru di-hash secara otomatis

### 6. Validasi & Keamanan
- ✅ Form Request validation untuk semua input
- ✅ Custom error messages dalam bahasa Indonesia
- ✅ Password minimal 8 karakter
- ✅ Username hanya huruf, angka, dash, underscore
- ✅ CSRF protection pada semua form
- ✅ Session regeneration setelah login
- ✅ Password hashing dengan bcrypt

## Struktur Database

```sql
CREATE TABLE users (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(191) NOT NULL,
    username VARCHAR(191) UNIQUE NOT NULL,
    email VARCHAR(191) UNIQUE NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

## Kredensial Default

**Admin Account:**
- Username: `admin`
- Password: `Admin123!`
- Email: `admin@ekimochi.com`
- Role: `admin`

## Endpoints / Routes

### Guest Routes (tidak perlu login)
- `GET /register` - Form registrasi
- `POST /register` - Proses registrasi
- `GET /login` - Form login
- `POST /login` - Proses login
- `GET /forgot-password` - Form lupa password
- `POST /forgot-password` - Kirim link reset password
- `GET /reset-password/{token}` - Form reset password
- `POST /reset-password` - Proses reset password

### Authenticated Routes (perlu login)
- `POST /logout` - Logout
- `GET /dashboard` - Dashboard user
- `GET /profile` - Lihat profile
- `PUT /profile` - Update profile

### Admin Routes (perlu login sebagai admin)
- `GET /admin/dashboard` - Dashboard admin

## Cara Menjalankan

1. **Setup database di `.env`:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ekimochi
DB_USERNAME=root
DB_PASSWORD=
```

2. **Jalankan migrasi:**
```bash
php artisan migrate
```

3. **Seed admin user:**
```bash
php artisan db:seed --class=AdminSeeder
```

4. **Jalankan server:**
```bash
php artisan serve
```

5. **Akses aplikasi:**
- URL: http://127.0.0.1:8000
- Login: http://127.0.0.1:8000/login
- Register: http://127.0.0.1:8000/register

## Testing Manual

### 1. Test Register User Biasa
1. Buka http://127.0.0.1:8000/register
2. Isi form: nama, username, password, konfirmasi password
3. Submit - akan otomatis login dan redirect ke dashboard
4. Cek role = user (tidak bisa akses admin dashboard)

### 2. Test Login
1. Buka http://127.0.0.1:8000/login
2. Login dengan username: `admin` password: `Admin123!`
3. Akan redirect ke dashboard
4. Cek navbar ada link "Admin Dashboard"

### 3. Test Profile & Email
1. Login sebagai user
2. Klik Profile di navbar
3. Tambahkan email
4. Submit - email tersimpan

### 4. Test Reset Password
1. Logout
2. Klik "Lupa Password"
3. Masukkan email yang sudah didaftarkan
4. (Perlu konfigurasi email di .env untuk testing penuh)

### 5. Test Authorization
1. Login sebagai user biasa
2. Coba akses http://127.0.0.1:8000/admin/dashboard
3. Akan muncul error 403 Forbidden

### 6. Test Logout
1. Klik logout di navbar
2. Session akan dihapus
3. Redirect ke login page

## Keamanan yang Diimplementasikan

1. **Password Security**
   - Hash menggunakan bcrypt
   - Minimal 8 karakter
   - Konfirmasi password saat registrasi dan reset

2. **Session Security**
   - Session regeneration setelah login
   - Session invalidation saat logout
   - CSRF token pada semua form

3. **Authorization**
   - Middleware auth untuk route authenticated
   - Middleware guest untuk route login/register
   - Middleware admin untuk route admin
   - Abort 403 jika akses unauthorized

4. **Validasi Input**
   - Server-side validation menggunakan Form Request
   - Username unique check
   - Email unique check (jika diisi)
   - Format email validation

5. **Database Security**
   - Email nullable untuk fleksibilitas
   - Role default user, tidak bisa di-set manual
   - Admin hanya via seeder

## Struktur File

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   │   ├── LoginController.php
│   │   │   ├── RegisterController.php
│   │   │   ├── ProfileController.php
│   │   │   ├── ForgotPasswordController.php
│   │   │   └── ResetPasswordController.php
│   │   └── DashboardController.php
│   ├── Middleware/
│   │   └── EnsureUserIsAdmin.php
│   └── Requests/
│       ├── LoginRequest.php
│       ├── RegisterRequest.php
│       ├── UpdateProfileRequest.php
│       ├── ForgotPasswordRequest.php
│       └── ResetPasswordRequest.php
├── Models/
│   └── User.php
└── Providers/
    └── AppServiceProvider.php

database/
├── migrations/
│   ├── 2014_10_12_000000_create_users_table.php
│   └── 2014_10_12_100000_create_password_reset_tokens_table.php
└── seeders/
    └── AdminSeeder.php

resources/views/
├── auth/
│   ├── login.blade.php
│   ├── register.blade.php
│   ├── profile.blade.php
│   ├── forgot-password.blade.php
│   └── reset-password.blade.php
├── admin/
│   └── dashboard.blade.php
└── dashboard.blade.php

routes/
└── web.php
```

## Catatan Penting

1. **Email Configuration**: Untuk fitur reset password bekerja penuh, perlu konfigurasi SMTP di `.env`
2. **Production**: Ganti password admin default sebelum deploy
3. **Database**: Sudah support MySQL dengan charset utf8mb4
4. **Session**: Menggunakan session driver (bukan API token)

## Next Steps / Pengembangan Lanjutan

Untuk pengembangan selanjutnya, bisa ditambahkan:
- Email verification saat registrasi
- Two-factor authentication
- API authentication dengan Laravel Sanctum
- Admin panel untuk manage users
- Audit log untuk tracking aktivitas user
- Rate limiting untuk login attempts
- Social authentication (Google, Facebook, dll)
