# Deploy Ekimochi ke Railway

Panduan deploy Laravel 10 ini ke Railway (container nginx + PHP-FPM 8.2 + MySQL).

## 1. File yang dipakai Railway

| File | Fungsi |
|---|---|
| `Dockerfile` | Image produksi: nginx + php-fpm 8.2, ext `pdo_mysql`, `gd`, `opcache`, upload limit 25MB |
| `docker/nginx.conf.template` | Konfigurasi nginx; `${PORT}` di-render saat container start |
| `docker/php.ini` | Tuning PHP (upload 25MB, opcache) |
| `docker/entrypoint.sh` | Render nginx, `storage:link`, migrasi DB, cache config/view, jalankan php-fpm + nginx |
| `railway.json` | `builder: DOCKERFILE`, healthcheck `/up` |
| `.dockerignore` | Mengecilkan build context (vendor, node_modules, .env tidak dikirim) |
| `routes/web.php` | Route `GET /up` untuk healthcheck Railway |

`nixpacks.toml` sudah dihapus. Konfigurasi nixpacks sebelumnya tidak bisa jalan:
build-nya men-`config:cache` sebelum env tersedia, dan start command-nya
`php artisan serve` sementara repo ini tidak punya `server.php`.

## 2. Yang harus disiapkan di Railway

1. **Service aplikasi** dari repo GitHub `Vnswx/Ekimochi-Rekayasa-Bisnis`, branch `main`.
2. **Plugin MySQL** (`+ New` -> `Database` -> `MySQL`).
3. **Variabel** di service aplikasi (tab *Variables*), semuanya tanpa tanda kutip:

```
APP_NAME=Ekimochi
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:...            # lihat bagian 3
APP_URL=https://<domain-railway-kamu>
LOG_CHANNEL=stderr
LOG_LEVEL=warning

DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}

CACHE_DRIVER=file
SESSION_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync

RUN_SEED=true                  # hanya deploy pertama, lihat bagian 4

GOOGLE_CLIENT_ID=<dari .env lokal>
GOOGLE_CLIENT_SECRET=<dari .env lokal>
GOOGLE_REDIRECT_URI=https://<domain-railway>/auth/google/callback

MIDTRANS_MERCHANT_ID=
MIDTRANS_CLIENT_KEY=
MIDTRANS_SERVER_KEY=
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

`${{MySQL.MYSQLHOST}}` adalah sintaks referensi variable Railway; nilainya otomatis
terisi dari service MySQL, jadi tidak perlu copy-paste kredensial.

`APP_URL` wajib benar, karena `Storage::disk('public')->url()` (gambar produk &
outlet) dan redirect Google OAuth dibentuk dari nilai ini.

## 3. APP_KEY

```bash
php artisan key:generate --show     # jalankan di lokal, copy hasilnya
```

Set hasilnya di Railway sebagai `APP_KEY` (51 karakter, diawali `base64:`).

## 4. Migrasi & data awal

`docker/entrypoint.sh` menjalankan `php artisan migrate --force` setiap kali container
start (retry 15x @4s, karena MySQL Railway sering belum siap saat app boot).

Seed data demo (user, outlet, produk, kategori) dijalankan bila `RUN_SEED=true`:

```bash
railway variables --set RUN_SEED=true      # deploy pertama
railway run php artisan db:seed --force    # atau jalankan manual sekali
railway variables --set RUN_SEED=false     # setelah data masuk
```

Setelah data masuk sebaiknya `RUN_SEED=false`, karena `OutletSeeder` memakai
`updateOrCreate` sehingga perubahan data outlet dari panel admin bisa tertimpa.

Akun hasil seeder: `admin@ekimochi.com` / `admin123` (admin), `user@ekimochi.com` / `user123`.

## 5. Setelah deploy

- **Google OAuth**: tambahkan `https://<domain-railway>/auth/google/callback` di
  Google Cloud Console -> Credentials -> Authorized redirect URIs, lalu update
  `GOOGLE_REDIRECT_URI` di Railway.
- **Midtrans**: set notification URL ke `https://<domain-railway>/payment/notification`.
- **Gambar**: `storage/app/public` bersifat sementara (container diganti setiap deploy).
  Gambar produk/outlet seeder ikut ter-copy ke sana saat start, tapi file yang di-upload
  lewat panel admin akan hilang setelah redeploy. Kalau perlu persisten, tambahkan
  Railway Volume dengan mount path `/var/www/html/storage/app/public`.

## 6. Troubleshooting

| Gejala | Penyebab / solusi |
|---|---|
| Deploy gagal di healthcheck | Cek `APP_URL`, lalu `railway logs` |
| `could not find driver` | Masih pakai builder Nixpacks; pastikan `DOCKERFILE` |
| 500 di semua halaman | `APP_KEY` belum diset; cek `railway logs` |
| Gambar produk 404 | `php artisan storage:link` gagal; cek `/var/www/html/public/storage` |
| Upload gambar gagal | nginx `client_max_body_size 30M`, PHP `upload_max_filesize 25M` |
