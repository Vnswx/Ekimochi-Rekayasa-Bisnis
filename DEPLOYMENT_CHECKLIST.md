# 📦 Deployment Checklist - InfinityFree

## ✅ File yang Sudah Disiapkan

### 1. Database Backup
- ✅ `ekimochi-database-backup.sql` (1.3 KB)
  - Export database lengkap
  - Siap import via phpMyAdmin

### 2. Environment File
- ✅ `.env.infinityfree`
  - Template config untuk hosting
  - **TODO:** Ganti placeholder setelah dapat credentials InfinityFree

### 3. Modified Files untuk InfinityFree
- ✅ `public/index-infinityfree.php` - Index dengan path adjusted
- ✅ `public/.htaccess-infinityfree` - .htaccess dengan force HTTPS
- ✅ `bootstrap/app-infinityfree.php` - Bootstrap dengan base path adjusted

---

## 🚀 Step-by-Step Deployment

### STEP 1: Registrasi InfinityFree (5 menit)

1. Buka https://infinityfree.com/
2. Klik **Sign Up**
3. Isi:
   - Email: _________
   - Password: _________
4. Verifikasi email
5. **Status:** [ ] Done

### STEP 2: Create Hosting Account (10 menit)

1. Login ke Control Panel
2. Klik **Create Account**
3. Pilih subdomain: `____________.infinityfreeapp.com`
4. Tunggu account created
5. Note credentials:
   ```
   FTP Host: ftpupload.net
   FTP Username: epiz_____________
   FTP Password: _______________
   
   MySQL Host: sql___.infinityfreeapp.com
   MySQL Database: epiz_________ekimochi
   MySQL Username: epiz_____________
   MySQL Password: _______________
   
   Site URL: https://_________.infinityfreeapp.com
   ```
6. **Status:** [ ] Done

### STEP 3: Update .env File (2 menit)

1. Buka file `.env.infinityfree`
2. Ganti placeholder:
   ```env
   APP_URL=https://YOURSITE.infinityfreeapp.com
   
   DB_HOST=sql___.infinityfreeapp.com
   DB_DATABASE=epiz_________ekimochi
   DB_USERNAME=epiz_____________
   DB_PASSWORD=_______________
   
   GOOGLE_REDIRECT_URI=https://YOURSITE.infinityfreeapp.com/auth/google/callback
   ```
3. Rename `.env.infinityfree` → `.env`
4. **Status:** [ ] Done

### STEP 4: Upload Files via FTP (30-60 menit)

**Install FileZilla:**
- Download: https://filezilla-project.org/

**Connect FTP:**
1. Host: `ftpupload.net`
2. Username: `epiz_____________`
3. Password: (dari Step 2)
4. Port: 21

**Upload Structure:**

**A. Upload ke ROOT (/):**
```
├── app/
├── bootstrap/
├── config/
├── database/
├── resources/
├── routes/
├── storage/
├── vendor/           ← IMPORTANT! Upload seluruh vendor/
├── .env              ← Renamed dari .env.infinityfree
├── artisan
├── composer.json
└── composer.lock
```

**B. Upload ke /htdocs/:**
```
htdocs/
├── index.php         ← Dari public/index-infinityfree.php (rename!)
├── .htaccess         ← Dari public/.htaccess-infinityfree (rename!)
├── favicon.ico
├── robots.txt
└── images/
```

**IMPORTANT:**
- `vendor/` harus di-upload lengkap (akan lama ~30-60 menit)
- Rename `index-infinityfree.php` → `index.php` di htdocs/
- Rename `.htaccess-infinityfree` → `.htaccess` di htdocs/

**Status:** [ ] Done

### STEP 5: Set Permissions (2 menit)

Via FileZilla, klik kanan folder → File Permissions:

```
storage/               → 755 atau 777
storage/logs/          → 755 atau 777
storage/framework/     → 755 atau 777
bootstrap/cache/       → 755 atau 777
```

**Status:** [ ] Done

### STEP 6: Import Database (5 menit)

1. Control Panel → **phpMyAdmin**
2. Login dengan MySQL credentials
3. Select database `epiz_________ekimochi`
4. Tab **Import**
5. Choose file: `ekimochi-database-backup.sql`
6. Click **Go**
7. Tunggu selesai
8. **Status:** [ ] Done

### STEP 7: Update Google OAuth (5 menit)

1. Buka https://console.cloud.google.com/
2. Pilih project **Ekimochi**
3. **APIs & Services** → **Credentials**
4. Edit OAuth 2.0 Client
5. **Authorized redirect URIs**, tambahkan:
   ```
   https://YOURSITE.infinityfreeapp.com/auth/google/callback
   ```
6. Save
7. **Status:** [ ] Done

### STEP 8: Test Website (10 menit)

1. Buka https://YOURSITE.infinityfreeapp.com
2. Test halaman:
   - [ ] Homepage load
   - [ ] Register berfungsi
   - [ ] Login email/password berfungsi
   - [ ] Login Google berfungsi
   - [ ] Dashboard load
   - [ ] Product list
   - [ ] Admin panel (jika sudah ada admin)

3. Jika error 500:
   - Cek Control Panel → **Error Logs**
   - Atau set `APP_DEBUG=true` di .env (jangan lupa disable setelah fix)

**Status:** [ ] Done

---

## ⚠️ Troubleshooting

### Error: "Class not found" atau Autoload Error
- **Penyebab:** vendor/ tidak ter-upload lengkap
- **Fix:** Re-upload vendor/ via FTP (pastikan semua ~10,000 files terupload)

### Error: "Storage link not found"
- **Penyebab:** `php artisan storage:link` tidak bisa di InfinityFree
- **Fix:** 
  1. Buat folder `htdocs/storage/`
  2. Copy files dari `/storage/app/public/` ke `htdocs/storage/`

### Error: "SQLSTATE[HY000] [1045] Access denied"
- **Penyebab:** MySQL credentials salah di .env
- **Fix:** Double-check DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD

### Website Lambat atau CPU Limit Exceeded
- **Penyebab:** InfinityFree punya CPU limit ketat
- **Fix:** 
  - Clear cache: delete files di `storage/framework/cache/`
  - Optimize queries di code
  - Atau consider pindah ke Fly.io/Railway

### Google OAuth Blocked
- **Penyebab:** InfinityFree IP ter-rate limit Google
- **Fix:** 
  - Tunggu beberapa jam
  - Atau gunakan provider hosting lain (Fly.io recommended)

---

## 📋 Final Checklist

Sebelum go-live:

- [ ] `.env` APP_DEBUG=false
- [ ] `.env` APP_ENV=production
- [ ] `.env` credentials benar
- [ ] Database imported
- [ ] Google OAuth redirect updated
- [ ] Storage permissions 777
- [ ] Test login/register
- [ ] Test Google login
- [ ] Error logs bersih
- [ ] SSL (HTTPS) berfungsi

---

## 🎉 Done!

Website seharusnya sudah live di:
**https://YOURSITE.infinityfreeapp.com**

Jika ada error, screenshot error message atau error logs dari Control Panel.

---

## 💡 Alternative (Recommended)

Jika InfinityFree terlalu lambat atau banyak limitasi, saya bisa setup ke:

**Fly.io** - Lebih reliable, gratis, full Laravel support:
- Deploy via Git
- MySQL via PlanetScale (gratis)
- SSL otomatis
- Tidak ada CPU limit ketat
- Google OAuth tidak ter-block

Let me know jika butuh panduan Fly.io!
