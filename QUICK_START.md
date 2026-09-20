# Quick Start Guide - Ekimochi Backend

## 🚀 Start in 5 Minutes

### 1. Check Server Status
Server sudah berjalan di: **http://127.0.0.1:8000**

### 2. Login Admin
```
URL: http://127.0.0.1:8000/login
Username: admin
Password: Admin123!
```

### 3. Register User Baru
```
URL: http://127.0.0.1:8000/register
Isi: Name, Username, Password, Password Confirmation
```

---

## 📍 Important URLs

| Page | URL |
|------|-----|
| Home | http://127.0.0.1:8000 |
| Login | http://127.0.0.1:8000/login |
| Register | http://127.0.0.1:8000/register |
| Dashboard | http://127.0.0.1:8000/dashboard |
| Profile | http://127.0.0.1:8000/profile |
| Admin Dashboard | http://127.0.0.1:8000/admin/dashboard |
| Forgot Password | http://127.0.0.1:8000/forgot-password |

---

## 🧪 Quick Tests

### Test 1: Admin Access (30 seconds)
```
1. Open: http://127.0.0.1:8000/login
2. Login: admin / Admin123!
3. Click "Admin Dashboard"
4. ✅ You should see admin dashboard (red theme)
```

### Test 2: User Registration (1 minute)
```
1. Open: http://127.0.0.1:8000/register
2. Fill form:
   - Name: Test User
   - Username: testuser
   - Password: testpass123
   - Confirm: testpass123
3. Submit
4. ✅ Auto login → Dashboard
5. ✅ No "Admin Dashboard" link (because role = user)
```

### Test 3: Authorization Test (30 seconds)
```
1. Stay logged in as testuser (from Test 2)
2. Try access: http://127.0.0.1:8000/admin/dashboard
3. ✅ Should get 403 Forbidden error
```

### Test 4: Profile & Email (1 minute)
```
1. Login as testuser
2. Click "Profile" in navbar
3. Add email: test@example.com
4. Click "Simpan Perubahan"
5. ✅ Success message appears
6. ✅ Email saved in database
```

### Test 5: Logout (15 seconds)
```
1. Click "Logout" button in navbar
2. ✅ Redirect to login page
3. ✅ Success message: "Logout berhasil!"
4. Try access: http://127.0.0.1:8000/dashboard
5. ✅ Should redirect to login (protected route)
```

---

## 🔧 Troubleshooting

### Server tidak berjalan?
```bash
php artisan serve
```

### Database error?
```bash
# Check .env database settings
# Then run:
php artisan migrate:fresh
php artisan db:seed --class=AdminSeeder
```

### Cache issues?
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

### Need fresh start?
```bash
php artisan migrate:fresh
php artisan db:seed --class=AdminSeeder
php artisan config:clear
```

---

## 📊 Database Quick Check

### Via MySQL CLI:
```sql
-- Connect to database
mysql -u root -p ekimochi

-- Check users
SELECT id, name, username, email, role FROM users;

-- Check admin exists
SELECT * FROM users WHERE role = 'admin';

-- Check password is hashed (should be 60 chars bcrypt)
SELECT username, LENGTH(password) as pass_length FROM users;
```

### Via Laravel Tinker:
```bash
php artisan tinker

# Check total users
>>> User::count()

# Check admin
>>> User::where('role', 'admin')->first()

# Check all usernames
>>> User::pluck('username')
```

---

## 🎯 What to Test

| Feature | Status | Priority |
|---------|--------|----------|
| Register | ✅ Ready | HIGH |
| Login | ✅ Ready | HIGH |
| Logout | ✅ Ready | HIGH |
| Dashboard | ✅ Ready | HIGH |
| Profile Edit | ✅ Ready | MEDIUM |
| Add Email | ✅ Ready | MEDIUM |
| Admin Auth | ✅ Ready | HIGH |
| Forgot Password | ✅ Ready* | LOW |
| Reset Password | ✅ Ready* | LOW |

*Forgot/Reset password needs email configuration in .env

---

## 📝 Default Credentials

### Admin
```
Username: admin
Password: Admin123!
Email: admin@ekimochi.com
Role: admin
```

### Test Users (create via register)
```
Create your own test users via:
http://127.0.0.1:8000/register
```

---

## 🔐 Security Checklist

- [x] Passwords are hashed (bcrypt)
- [x] CSRF tokens on all forms
- [x] Session regeneration on login
- [x] Session invalidation on logout
- [x] Admin middleware working (403 for non-admin)
- [x] Input validation on all forms
- [x] SQL injection protected (Eloquent ORM)
- [x] XSS protected (Blade auto-escape)
- [x] Unique constraints (username, email)
- [x] Role-based authorization

---

## 📞 Need Help?

### Documentation Files:
- **IMPLEMENTATION_SUMMARY.md** - Complete summary
- **BACKEND_README.md** - Technical documentation  
- **TESTING_CHECKLIST.md** - Detailed testing guide
- **QUICK_START.md** - This file

### Check Routes:
```bash
php artisan route:list
```

### Check Database:
```bash
php artisan db:show
```

### View Logs:
```bash
tail -f storage/logs/laravel.log
```

---

## ✨ All Features Working!

Backend Ekimochi sudah **100% siap digunakan** dengan:

✅ Complete authentication system  
✅ Role-based authorization  
✅ Profile management  
✅ Password reset via email  
✅ Security best practices  
✅ Input validation  
✅ Clean code structure  

**Happy Testing! 🎉**

---

Last Updated: 2026-09-20  
Server: http://127.0.0.1:8000  
Framework: Laravel 10.50.3  
PHP: 8.3.16
