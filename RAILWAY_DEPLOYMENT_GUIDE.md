# Railway Deployment Guide - Laravel Ekimochi
# 100% Gratis via GitHub Auto-Deploy

## 🚀 Kenapa Railway?

✅ **Deploy otomatis dari GitHub** - Push code → Auto deploy  
✅ **$5 free credit/bulan** - Cukup untuk ~500 jam runtime (~20 hari non-stop)  
✅ **MySQL gratis included**  
✅ **SSL otomatis**  
✅ **Environment variables easy setup**  
✅ **Logs real-time**  
✅ **Tidak perlu kartu kredit** (trial mode)

---

## 📋 Prerequisites

- ✅ GitHub account (sudah ada: https://github.com/Vnswx/Ekimochi-Rekayasa-Bisnis.git)
- ✅ Project Laravel ready
- ✅ Railway account (akan dibuat)

---

## 🎯 Step-by-Step Deployment

### STEP 1: Push Latest Code ke GitHub (2 menit)

```bash
# Commit semua perubahan terbaru
git add .
git commit -m "Prepare for Railway deployment"
git push origin main
```

**Status:** [ ] Done

---

### STEP 2: Registrasi Railway (2 menit)

1. Buka https://railway.app/
2. Klik **Login**
3. Pilih **Login with GitHub**
4. Authorize Railway ke GitHub account
5. Berhasil login (no credit card needed!)

**Status:** [ ] Done

---

### STEP 3: Create New Project (5 menit)

1. Dashboard Railway → Klik **New Project**
2. Pilih **Deploy from GitHub repo**
3. Pilih repository: **Vnswx/Ekimochi-Rekayasa-Bisnis**
4. Railway akan auto-detect Laravel
5. Klik **Deploy Now**

**Status:** [ ] Done

---

### STEP 4: Add MySQL Database (2 menit)

1. Di project Railway, klik **New** → **Database** → **Add MySQL**
2. MySQL akan auto-provision
3. Note: Database credentials auto-generated di environment variables

**Status:** [ ] Done

---

### STEP 5: Setup Environment Variables (5 menit)

1. Klik service **Ekimochi-Rekayasa-Bisnis**
2. Tab **Variables**
3. Klik **RAW Editor**
4. Paste ini:

```env
APP_NAME=Ekimochi
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_FROM_LOCAL_ENV
APP_DEBUG=false
APP_URL=${{RAILWAY_PUBLIC_DOMAIN}}

LOG_CHANNEL=stack
LOG_LEVEL=error

# MySQL akan auto-filled oleh Railway (jangan edit)
DB_CONNECTION=mysql
DB_HOST=${{MYSQL_HOST}}
DB_PORT=${{MYSQL_PORT}}
DB_DATABASE=${{MYSQL_DATABASE}}
DB_USERNAME=${{MYSQL_USER}}
DB_PASSWORD=${{MYSQL_PASSWORD}}

SESSION_DRIVER=database
SESSION_LIFETIME=120
QUEUE_CONNECTION=database

GOOGLE_CLIENT_ID=your_google_client_id_here
GOOGLE_CLIENT_SECRET=your_google_client_secret_here
GOOGLE_REDIRECT_URI=https://${{RAILWAY_PUBLIC_DOMAIN}}/auth/google/callback
```

5. Klik **Save**

**IMPORTANT:** Ganti `YOUR_APP_KEY_FROM_LOCAL_ENV` dengan APP_KEY dari `.env` local Anda

**Status:** [ ] Done

---

### STEP 6: Add Railway Config Files (10 menit)

Buat file konfigurasi Railway di local:

#### A. Create `railway.json`

```json
{
  "$schema": "https://railway.app/railway.schema.json",
  "build": {
    "builder": "NIXPACKS"
  },
  "deploy": {
    "startCommand": "php artisan migrate --force && php artisan optimize && php artisan serve --host=0.0.0.0 --port=$PORT",
    "healthcheckPath": "/up",
    "healthcheckTimeout": 300,
    "restartPolicyType": "ON_FAILURE",
    "restartPolicyMaxRetries": 10
  }
}
```

#### B. Create `nixpacks.toml`

```toml
[phases.setup]
nixPkgs = ["...", "php83", "php83Packages.composer"]

[phases.install]
cmds = ["composer install --optimize-autoloader --no-dev"]

[phases.build]
cmds = ["php artisan config:cache", "php artisan route:cache", "php artisan view:cache"]

[start]
cmd = "php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT"
```

#### C. Create `Procfile` (alternative)

```
web: php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT
```

Push ke GitHub:

```bash
git add railway.json nixpacks.toml Procfile
git commit -m "Add Railway deployment config"
git push origin main
```

Railway akan **auto-redeploy** setelah push!

**Status:** [ ] Done

---

### STEP 7: Run Database Migration (3 menit)

Setelah deploy selesai:

1. Railway dashboard → Service **Ekimochi-Rekayasa-Bisnis**
2. Tab **Deployments** → Klik latest deployment
3. Scroll ke **Logs** → Pastikan migration running
4. Atau manual run via Railway CLI:

```bash
# Install Railway CLI (Windows)
npm install -g @railway/cli

# Login
railway login

# Link project
railway link

# Run migration
railway run php artisan migrate --force
```

**Status:** [ ] Done

---

### STEP 8: Generate Public Domain (2 menit)

1. Service **Ekimochi-Rekayasa-Bisnis** → Tab **Settings**
2. Scroll ke **Networking**
3. Klik **Generate Domain**
4. Copy domain: `ekimochi-rekayasa-bisnis-production.up.railway.app`
5. Buka di browser untuk test

**Status:** [ ] Done

---

### STEP 9: Update Google OAuth (3 menit)

1. Google Cloud Console → https://console.cloud.google.com/
2. Pilih project **Ekimochi**
3. **APIs & Services** → **Credentials**
4. Edit OAuth 2.0 Client
5. **Authorized redirect URIs**, tambahkan:
   ```
   https://ekimochi-rekayasa-bisnis-production.up.railway.app/auth/google/callback
   ```
6. Save

**Status:** [ ] Done

---

### STEP 10: Test Website (5 menit)

Buka: `https://ekimochi-rekayasa-bisnis-production.up.railway.app`

Test:
- [ ] Homepage load
- [ ] Register
- [ ] Login
- [ ] Google OAuth
- [ ] Dashboard
- [ ] Products

**Status:** [ ] Done

---

## 🔧 Common Issues & Fixes

### 1. Build Failed - Composer Install Error

**Symptoms:** Build fails with "composer: command not found"

**Fix:** Pastikan `nixpacks.toml` ada dan correct

### 2. Migration Failed - Connection Refused

**Symptoms:** "SQLSTATE[HY000] [2002] Connection refused"

**Fix:** 
- Pastikan MySQL service sudah running
- Check environment variables: `DB_HOST`, `DB_PORT`, dll
- Restart MySQL service di Railway

### 3. 500 Internal Server Error

**Symptoms:** White page dengan 500 error

**Fix:**
1. Railway → Logs → Cek error detail
2. Set `APP_DEBUG=true` temporary
3. Common causes:
   - APP_KEY tidak diset
   - Storage permissions
   - .env config salah

**Fix Storage:**
```bash
railway run php artisan storage:link
```

### 4. Google OAuth Error

**Symptoms:** "redirect_uri_mismatch"

**Fix:** Double-check redirect URI di Google Console = Railway domain exactly

### 5. Session Not Working

**Symptoms:** Login logout terus

**Fix:** 
- Set `SESSION_DRIVER=database` di env
- Run migration untuk session table:
  ```bash
  railway run php artisan session:table
  railway run php artisan migrate
  ```

---

## 💰 Credit Usage Monitoring

Railway $5/bulan = ~500 jam runtime

**Monitor Usage:**
1. Dashboard → **Usage**
2. Lihat credit remaining
3. Tips hemat:
   - Scale down saat tidak dipakai
   - Optimize queries (reduce CPU usage)
   - Use cache

**Auto-scale down:**
```json
{
  "deploy": {
    "sleepApplication": true,
    "sleepThreshold": "15m"
  }
}
```

---

## 🎉 Auto-Deploy dari GitHub

Sekarang setiap kali push ke GitHub:
```bash
git add .
git commit -m "Update feature"
git push origin main
```

Railway akan **otomatis deploy** dalam ~2-3 menit!

Monitor deploy:
- Railway Dashboard → Deployments → Logs

---

## 📊 Railway vs InfinityFree

| Feature | Railway | InfinityFree |
|---------|---------|--------------|
| Deploy method | GitHub auto | FTP manual |
| MySQL | Included | Included |
| PHP version | Latest | PHP 7.4-8.2 |
| SSL | Auto | Auto |
| Performance | Fast | Slow (CPU limit) |
| Logs | Real-time | Basic |
| CLI tools | Yes | No |
| Google OAuth | Works | Sometimes blocked |
| Credit | $5/bulan | Unlimited |
| Uptime | High | Medium |

**Verdict:** Railway jauh lebih mudah dan reliable!

---

## 🔄 Rollback Deploy

Jika deploy baru error:

1. Railway → Deployments
2. Pilih deployment yang lama (yang stable)
3. Klik **⋯** → **Redeploy**

---

## 🛠️ Useful Railway CLI Commands

```bash
# Login
railway login

# Link project
railway link

# View logs
railway logs

# Run artisan commands
railway run php artisan migrate
railway run php artisan optimize
railway run php artisan tinker

# Open in browser
railway open

# Check environment variables
railway variables

# Shell access
railway shell
```

---

## ✅ Final Checklist

- [ ] GitHub repo updated
- [ ] Railway account created
- [ ] Project deployed
- [ ] MySQL database added
- [ ] Environment variables configured
- [ ] Migration running
- [ ] Public domain generated
- [ ] Google OAuth updated
- [ ] Website tested
- [ ] Auto-deploy working

---

## 🎯 Setelah Deploy Berhasil

Website live di:
**https://ekimochi-rekayasa-bisnis-production.up.railway.app**

Setiap push ke GitHub → auto deploy dalam 2-3 menit!

---

## 💡 Tips Production

1. **Set APP_DEBUG=false** (security)
2. **Monitor credit usage** (Railway dashboard)
3. **Setup logging** (use external service jika perlu)
4. **Backup database** regular:
   ```bash
   railway run mysqldump > backup.sql
   ```
5. **Use queue** untuk heavy tasks:
   ```bash
   railway run php artisan queue:work
   ```

---

**Need help?** Railway Discord: https://discord.gg/railway

Good luck! 🚀
