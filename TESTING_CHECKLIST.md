# Testing Checklist - Backend Ekimochi

## Status Server
- Server berjalan di: http://127.0.0.1:8000
- Database: MySQL
- Admin sudah di-seed

## Kredensial Testing

### Admin
- Username: `admin`
- Password: `Admin123!`

### User Test (buat sendiri saat testing)
- Buat via register form

---

## Testing Checklist

### ✅ 1. Register User Baru
**Endpoint:** http://127.0.0.1:8000/register

**Test Cases:**
- [ ] Form register tampil dengan field: name, username, password, password_confirmation
- [ ] Validasi: semua field wajib diisi
- [ ] Validasi: username hanya huruf, angka, dash, underscore
- [ ] Validasi: password minimal 8 karakter
- [ ] Validasi: konfirmasi password harus sama
- [ ] Validasi: username harus unique
- [ ] Setelah register berhasil, otomatis login
- [ ] Redirect ke dashboard setelah register
- [ ] Role otomatis jadi "user"
- [ ] Email NULL (tidak wajib saat register)

**Expected Result:**
- User baru tersimpan di database
- Password ter-hash (bukan plaintext)
- Role = user
- User langsung login dan masuk dashboard

---

### ✅ 2. Login
**Endpoint:** http://127.0.0.1:8000/login

**Test Cases:**
- [ ] Form login tampil dengan field: username, password, remember me
- [ ] Login dengan username yang tidak terdaftar → error
- [ ] Login dengan password salah → error "Username atau password salah"
- [ ] Login dengan kredensial benar → berhasil
- [ ] Checkbox "Ingat Saya" berfungsi
- [ ] Setelah login redirect ke dashboard
- [ ] Session tersimpan

**Test Data:**
```
Username: admin
Password: Admin123!
```

**Expected Result:**
- Login berhasil
- Redirect ke /dashboard
- Navbar menampilkan nama user dan menu logout

---

### ✅ 3. Dashboard User
**Endpoint:** http://127.0.0.1:8000/dashboard

**Test Cases:**
- [ ] Hanya bisa diakses setelah login
- [ ] Menampilkan informasi user: name, username, email, role
- [ ] Ada link ke Profile
- [ ] Ada button Logout
- [ ] Jika role=admin, ada link "Admin Dashboard"
- [ ] Jika role=user, tidak ada link "Admin Dashboard"

**Expected Result:**
- Dashboard tampil dengan data user yang login
- Navigasi berfungsi dengan baik

---

### ✅ 4. Profile Management
**Endpoint:** http://127.0.0.1:8000/profile

**Test Cases:**
- [ ] Form tampil dengan field: name, username (disabled), email
- [ ] Username tidak bisa diubah (disabled)
- [ ] Bisa update name
- [ ] Bisa menambahkan email (jika NULL)
- [ ] Validasi: email harus format valid
- [ ] Validasi: email harus unique
- [ ] Setelah update → success message
- [ ] Data tersimpan di database

**Test Steps:**
1. Login sebagai user yang baru di-register (email masih NULL)
2. Klik "Profile" di navbar
3. Tambahkan email (contoh: test@example.com)
4. Submit
5. Cek database: email tersimpan

**Expected Result:**
- Profile berhasil diupdate
- Email tersimpan di database
- Tampil pesan sukses

---

### ✅ 5. Authorization - Role Admin
**Endpoint:** http://127.0.0.1:8000/admin/dashboard

**Test Cases:**
- [ ] Login sebagai admin → bisa akses admin dashboard
- [ ] Login sebagai user biasa → tidak bisa akses (403 Forbidden)
- [ ] Admin dashboard hanya bisa diakses role=admin
- [ ] Navbar admin berbeda (warna merah, badge ADMIN)

**Test Steps:**

**Sebagai Admin:**
1. Login dengan username: `admin`, password: `Admin123!`
2. Klik link "Admin Dashboard" di navbar
3. Halaman admin dashboard terbuka

**Sebagai User Biasa:**
1. Logout dari admin
2. Register user baru atau login sebagai user biasa
3. Coba akses langsung http://127.0.0.1:8000/admin/dashboard
4. Harus muncul error 403 Forbidden

**Expected Result:**
- Admin bisa akses admin dashboard
- User biasa tidak bisa akses (403)
- Middleware admin berfungsi dengan baik

---

### ✅ 6. Forgot Password
**Endpoint:** http://127.0.0.1:8000/forgot-password

**Test Cases:**
- [ ] Form tampil dengan field: email
- [ ] Validasi: email wajib diisi
- [ ] Validasi: email harus format valid
- [ ] Validasi: email harus terdaftar di database
- [ ] Email yang belum diisi → error "Email tidak terdaftar"
- [ ] Email yang valid → success message (link dikirim)

**CATATAN:**
Untuk testing penuh fitur ini, perlu konfigurasi SMTP di `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Ekimochi"
```

Tanpa konfigurasi email, fitur ini akan error saat mengirim email (expected).

---

### ✅ 7. Reset Password
**Endpoint:** http://127.0.0.1:8000/reset-password/{token}

**Test Cases:**
- [ ] Form tampil dengan field: email, password, password_confirmation
- [ ] Token valid → bisa reset password
- [ ] Token invalid/expired → error
- [ ] Password baru minimal 8 karakter
- [ ] Konfirmasi password harus sama
- [ ] Setelah reset berhasil → redirect ke login
- [ ] Password baru ter-hash di database
- [ ] Bisa login dengan password baru

**CATATAN:**
Testing ini tergantung fitur forgot password (perlu email terkirim).

---

### ✅ 8. Logout
**Endpoint:** POST http://127.0.0.1:8000/logout

**Test Cases:**
- [ ] Klik button Logout di navbar
- [ ] Session dihapus
- [ ] Redirect ke halaman login
- [ ] Success message: "Logout berhasil!"
- [ ] Tidak bisa akses dashboard lagi tanpa login ulang

**Expected Result:**
- Logout berhasil
- Session invalidated
- Harus login ulang untuk akses dashboard

---

### ✅ 9. Security Testing

**A. Password Hashing**
- [ ] Cek database: password tidak plaintext
- [ ] Password ter-hash menggunakan bcrypt
- [ ] Command: `SELECT password FROM users LIMIT 1;`

**B. CSRF Protection**
- [ ] Semua form punya `@csrf` token
- [ ] Submit form tanpa CSRF → error 419

**C. Session Security**
- [ ] Session regenerate setelah login
- [ ] Session invalidate setelah logout
- [ ] Cookie `laravel_session` ada

**D. Input Validation**
- [ ] XSS: coba input `<script>alert('xss')</script>` di name → di-escape
- [ ] SQL Injection: username dengan quote → di-escape oleh Laravel
- [ ] Long string: name 300 karakter → error max 255

**E. Authorization**
- [ ] User biasa tidak bisa akses admin route
- [ ] Guest tidak bisa akses authenticated route
- [ ] Authenticated user tidak bisa akses guest route (redirect)

---

### ✅ 10. Database Verification

**Jalankan di database:**

```sql
-- Cek struktur tabel users
DESC users;

-- Cek admin user
SELECT * FROM users WHERE role = 'admin';

-- Cek user biasa
SELECT * FROM users WHERE role = 'user';

-- Cek password ter-hash
SELECT username, password FROM users;

-- Cek email nullable
SELECT username, email FROM users WHERE email IS NULL;
```

**Expected:**
- Tabel users punya semua kolom yang diminta
- Ada user dengan role=admin
- Password berupa hash (60 karakter bcrypt)
- Email bisa NULL

---

## Quick Testing Flow

### Scenario 1: User Journey
1. Buka http://127.0.0.1:8000
2. Klik Register
3. Isi form → submit
4. Masuk dashboard otomatis
5. Klik Profile
6. Tambah email → save
7. Logout
8. Login lagi dengan kredensial yang sama
9. Berhasil masuk dashboard

### Scenario 2: Admin Journey
1. Login dengan admin / Admin123!
2. Masuk dashboard
3. Klik "Admin Dashboard"
4. Lihat halaman admin (warna merah)
5. Klik "User Dashboard" → kembali ke dashboard biasa
6. Logout

### Scenario 3: Authorization Test
1. Register user baru
2. Coba akses http://127.0.0.1:8000/admin/dashboard langsung
3. Harus dapat 403 Forbidden
4. Logout
5. Login sebagai admin
6. Akses admin dashboard berhasil

---

## Bug Report Template

Jika menemukan bug, catat dengan format:

```
BUG: [Judul singkat]

Steps to Reproduce:
1. ...
2. ...
3. ...

Expected Result:
...

Actual Result:
...

Screenshot/Error Message:
...
```

---

## Testing Completed

- [ ] Semua test cases dijalankan
- [ ] Semua fitur berfungsi sesuai spesifikasi
- [ ] Tidak ada bug kritis
- [ ] Security measures terverifikasi
- [ ] Database sesuai struktur

**Tester:** __________
**Date:** __________
**Signature:** __________
