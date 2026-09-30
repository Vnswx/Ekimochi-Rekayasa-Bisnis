# Panduan Hosting Laravel di InfinityFree (100% Gratis)

## ⚠️ Disclaimer
InfinityFree adalah hosting gratis dengan limitasi:
- CPU limit rendah (bisa suspend jika traffic tinggi)
- Tidak support Composer di hosting (harus upload vendor/)
- MySQL basic (MyISAM, bukan InnoDB)
- Kadang lambat
- Google OAuth mungkin ter-block karena rate limit

Alternatif lebih baik: **Fly.io** atau **Railway** (lebih reliable untuk Laravel).

---

## 📋 Persiapan Project

### 1. Install Dependencies & Build Assets

```bash
# Install composer dependencies
composer install --optimize-autoloader --no-dev

# Build frontend assets (jika ada)
npm install
npm run build

# Clear cache
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### 2. Setup Environment Production

Buat file `.env.production`:

```env
APP_NAME=Ekimochi
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://yourdomain.infinityfreeapp.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=sqlXXX.infinityfreeapp.com
DB_PORT=3306
DB_DATABASE=epiz_XXXXXXXX_ekimochi
DB_USERNAME=epiz_XXXXXXXX
DB_PASSWORD=YOUR_DB_PASSWORD

SESSION_DRIVER=file
SESSION_LIFETIME=120

GOOGLE_CLIENT_ID=your_google_client_id_here
GOOGLE_CLIENT_SECRET=your_google_client_secret_here
GOOGLE_REDIRECT_URI=https://yourdomain.infinityfreeapp.com/auth/google/callback
```

### 3. Generate ZIP untuk Upload

```bash
# Windows PowerShell
Compress-Archive -Path * -DestinationPath ekimochi-deploy.zip -Force

# Atau manual zip semua file kecuali:
# - node_modules/
# - .git/
# - tests/
# - storage/logs/*
```

---

## 🌐 Setup InfinityFree

### Step 1: Registrasi Account

1. Buka https://infinityfree.com/
2. Klik **Sign Up** (100% gratis, tidak perlu kartu kredit)
3. Isi form registrasi:
   - Email
   - Password
4. Verifikasi email

### Step 2: Create Hosting Account

1. Login ke **Control Panel**
2. Klik **Create Account**
3. Isi form:
   - **Subdomain:** pilih nama (contoh: `ekimochi.infinityfreeapp.com`)
   - **Password:** buat password
4. Tunggu account dibuat (~5 menit)
5. Note credentials:
   - FTP Host
   - FTP Username
   - FTP Password
   - MySQL Host
   - MySQL Username
   - MySQL Database

### Step 3: Setup Database

1. Di Control Panel, buka **MySQL Databases**
2. Database sudah auto-created, note:
   - **Database Name:** `epiz_XXXXXXXX_ekimochi`
   - **Username:** `epiz_XXXXXXXX`
   - **Password:** (dari email/control panel)
   - **Host:** `sqlXXX.infinityfreeapp.com`

3. Import database (2 cara):

**Cara A: Via phpMyAdmin**
1. Buka **phpMyAdmin** dari control panel
2. Login dengan MySQL credentials
3. Select database `epiz_XXXXXXXX_ekimochi`
4. Klik **Import**
5. Upload file SQL (export dulu dari local):
   ```bash
   # Export dari local
   mysqldump -u root laravel > ekimochi-backup.sql
   ```
6. Import file tersebut

**Cara B: Via Terminal (local)**
```bash
# Export structure + data
php artisan schema:dump

# Nanti upload manual via phpMyAdmin
```

---

## 📤 Upload Project ke InfinityFree

### Struktur Folder di Hosting

InfinityFree menggunakan struktur:
```
/htdocs/              ← Document root (public web)
  ├── index.php       ← Dari public/
  ├── css/
  ├── js/
  └── .htaccess

/                     ← Root directory (di atas htdocs)
  ├── app/
  ├── bootstrap/
  ├── config/
  ├── database/
  ├── resources/
  ├── routes/
  ├── storage/
  ├── vendor/
  ├── .env
  └── artisan
```

### Upload via FTP

1. **Download FileZilla** atau FTP client lain

2. **Connect FTP:**
   - Host: `ftpupload.net` atau sesuai panel
   - Username: `epiz_XXXXXXXX`
   - Password: dari control panel
   - Port: 21

3. **Upload struktur:**

   **A. Upload Laravel files (kecuali public/) ke root:**
   ```
   Upload ke /: 
   - app/
   - bootstrap/
   - config/
   - database/
   - resources/
   - routes/
   - storage/
   - vendor/
   - .env (rename dari .env.production)
   - artisan
   - composer.json
   - composer.lock
   ```

   **B. Upload public/ ke htdocs:**
   ```
   Upload semua isi folder public/ ke /htdocs/:
   - index.php
   - .htaccess
   - css/
   - js/
   - images/
   - storage/ (symlink, bikin manual)
   ```

4. **Edit index.php di htdocs:**

   ```php
   <?php

   // Ganti path ke parent directory
   require __DIR__.'/../vendor/autoload.php';
   $app = require_once __DIR__.'/../bootstrap/app.php';

   // ... rest of file
   ```

5. **Set Permissions:**
   ```
   storage/ → 755 atau 777
   storage/logs/ → 755 atau 777
   storage/framework/ → 755 atau 777
   bootstrap/cache/ → 755 atau 777
   ```

---

## 🔐 Update Google OAuth

1. Buka **Google Cloud Console**: https://console.cloud.google.com/
2. Pilih project **Ekimochi**
3. **APIs & Services** → **Credentials**
4. Edit OAuth 2.0 Client ID
5. **Authorized redirect URIs**, tambahkan:
   ```
   https://yourdomain.infinityfreeapp.com/auth/google/callback
   ```
6. Save

7. Update `.env` di hosting:
   ```env
   APP_URL=https://yourdomain.infinityfreeapp.com
   GOOGLE_REDIRECT_URI=https://yourdomain.infinityfreeapp.com/auth/google/callback
   ```

---

## 🔧 Fix Common Issues

### 1. Storage Link Error

InfinityFree tidak support `php artisan storage:link`. Fix manual:

**Option A: Copy files**
```bash
# Di htdocs/, buat folder storage/
mkdir htdocs/storage
# Copy semua dari ../storage/app/public/ ke htdocs/storage/
```

**Option B: Edit config (recommended)**
Edit `config/filesystems.php`:
```php
'public' => [
    'driver' => 'local',
    'root' => storage_path('app/public'),
    'url' => env('APP_URL').'/storage',  // Bukan /storage tapi direct path
    'visibility' => 'public',
],
```

### 2. 500 Internal Server Error

Cek error log:
1. Control Panel → **Error Logs**
2. Atau tambah di `.env`:
   ```env
   APP_DEBUG=true
   ```
   (Jangan lupa set false setelah fix)

Common fixes:
- Permissions storage/ dan bootstrap/cache/ → 777
- .env APP_KEY harus diisi (copy dari local atau generate)
- Path di index.php salah

### 3. SSL Certificate (HTTPS)

InfinityFree auto-provide SSL. Tapi untuk force HTTPS, tambahkan di `public/.htaccess`:

```apache
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

### 4. Composer Dependencies

InfinityFree tidak support `composer install` di hosting. Solusi:

**Upload vendor/ dari local:**
```bash
# Di local, pastikan vendor/ ter-generate
composer install --optimize-autoloader --no-dev

# Upload seluruh folder vendor/ via FTP (akan lama ~30 menit)
```

### 5. Database Migration

Karena tidak bisa `php artisan migrate` di hosting:

**Option A: Import SQL**
```bash
# Di local, export database
mysqldump -u root laravel > database.sql

# Upload via phpMyAdmin di InfinityFree
```

**Option B: Manual CREATE TABLE**
Copy SQL dari migration files dan run di phpMyAdmin.

---

## ✅ Checklist Deployment

- [ ] Composer install --no-dev
- [ ] Build assets (npm run build)
- [ ] Export database dari local
- [ ] Buat .env.production dengan config InfinityFree
- [ ] ZIP project (tanpa node_modules/, .git/, tests/)
- [ ] Create InfinityFree account
- [ ] Note MySQL credentials
- [ ] Upload files via FTP (struktur benar)
- [ ] Edit index.php di htdocs
- [ ] Set permissions storage/ → 777
- [ ] Import database via phpMyAdmin
- [ ] Update Google OAuth redirect URIs
- [ ] Test login, register, Google OAuth
- [ ] Set APP_DEBUG=false

---

## 🚀 Alternative: Fly.io (Recommended)

Jika InfinityFree terlalu lambat atau Google OAuth ter-block:

**Fly.io** lebih reliable:
- SSL otomatis
- MySQL support (via PlanetScale gratis)
- Full Laravel support
- Deploy via Git
- Free tier: 3 VM, 3GB storage

Saya bisa buatkan setup Fly.io jika diperlukan.

---

## 📞 Support

Jika ada error saat deploy, screenshot error message dan control panel info.

**Common InfinityFree Limits:**
- Max file size upload: 10MB
- Max execution time: 30s
- Inode limit: 10,000 files
- Bandwidth: Unlimited (tapi CPU limit ketat)

Good luck! 🎉
