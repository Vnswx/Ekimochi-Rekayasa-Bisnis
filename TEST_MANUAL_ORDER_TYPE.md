# MANUAL TESTING GUIDE - ORDER TYPE FEATURE
## Ekimochi Laravel E-Commerce

---

## ✅ CHECKLIST TESTING

### 1. DATABASE VERIFICATION

```sql
-- Cek outlets
SELECT * FROM outlets;
-- Expected: 3 outlets (Senayan, Bandung, Surabaya)

-- Cek outlet tables
SELECT o.name, ot.table_number, ot.qr_token 
FROM outlets o 
JOIN outlet_tables ot ON o.id = ot.outlet_id 
ORDER BY o.id, ot.table_number;
-- Expected: 30 rows (10 meja per outlet)

-- Cek struktur orders table
DESCRIBE orders;
-- Expected: kolom order_type, outlet_id, outlet_table_id, delivery_distance, estimated_ready_time
```

### 2. ROUTE TESTING

```bash
# Cek routes tersedia
php artisan route:list | grep checkout
php artisan route:list | grep scan
php artisan route:list | grep outlet
```

**Expected Routes:**
- ✅ GET /checkout (checkout.index)
- ✅ POST /checkout (checkout.store)
- ✅ GET /scan/{qrToken} (scan.table)
- ✅ GET /admin/outlets (admin.outlets.index)

### 3. FUNCTIONAL TESTING

#### A. DINE IN FLOW

**Step 1: Generate QR Code**
1. Login sebagai admin
2. Akses: `http://localhost:8000/admin/outlets`
3. Verifikasi: Muncul 3 outlet dengan QR code
4. Download QR code meja pertama

**Step 2: Scan QR Code**
1. Scan QR atau akses URL dari QR
   Format: `http://localhost:8000/scan/{qrToken}`
2. Verifikasi: Redirect ke `/checkout?qr={qrToken}`
3. Verifikasi: Muncul notifikasi hijau "Meja terdeteksi dari QR Code"
4. Verifikasi: Tampil nama outlet & nomor meja

**Step 3: Checkout Dine In**
1. Pastikan "Dine In" ter-select
2. Isi data pembeli (nama, email, phone)
3. Isi catatan (opsional)
4. Klik "Lanjut ke Pembayaran"

**Verifikasi Database:**
```sql
SELECT order_number, order_type, outlet_id, outlet_table_id, 
       shipping_cost, delivery_distance, estimated_ready_time
FROM orders 
WHERE order_type = 'dine_in' 
ORDER BY id DESC LIMIT 1;
```

**Expected Result:**
- ✅ order_type = 'dine_in'
- ✅ outlet_id = (sesuai QR)
- ✅ outlet_table_id = (sesuai QR)
- ✅ shipping_cost = 0
- ✅ delivery_distance = NULL
- ✅ estimated_ready_time = now + 15-25 menit

---

#### B. TAKE AWAY FLOW

**Step 1: Pilih Take Away**
1. Login user
2. Tambah produk ke cart
3. Akses `/checkout`
4. Pilih "Take Away"
5. Verifikasi: Muncul form pilihan outlet

**Step 2: Pilih Outlet**
1. Pilih salah satu outlet (misal: Bandung)
2. Isi data pembeli
3. Isi catatan (opsional)
4. Klik "Lanjut ke Pembayaran"

**Verifikasi Database:**
```sql
SELECT order_number, order_type, outlet_id, outlet_table_id, 
       shipping_cost, delivery_distance, estimated_ready_time
FROM orders 
WHERE order_type = 'take_away' 
ORDER BY id DESC LIMIT 1;
```

**Expected Result:**
- ✅ order_type = 'take_away'
- ✅ outlet_id = (outlet yang dipilih)
- ✅ outlet_table_id = NULL
- ✅ shipping_cost = 0
- ✅ delivery_distance = NULL
- ✅ estimated_ready_time = now + 20-30 menit

**Error Testing:**
1. Submit tanpa pilih outlet → Error: outlet_id required

---

#### C. DELIVERY FLOW

**Step 1: Pilih Delivery**
1. Login user
2. Tambah produk ke cart
3. Akses `/checkout`
4. Pilih "Delivery"
5. Verifikasi: Muncul form alamat pengiriman

**Step 2: Input Alamat**
1. Isi alamat lengkap (misal: Jakarta)
2. Isi data pembeli
3. Isi catatan (opsional)
4. Klik "Lanjut ke Pembayaran"

**Verifikasi Database:**
```sql
SELECT order_number, order_type, outlet_id, outlet_table_id, 
       shipping_address, shipping_cost, delivery_distance, estimated_ready_time
FROM orders 
WHERE order_type = 'delivery' 
ORDER BY id DESC LIMIT 1;
```

**Expected Result:**
- ✅ order_type = 'delivery'
- ✅ outlet_id = (outlet terdekat)
- ✅ outlet_table_id = NULL
- ✅ shipping_address = (alamat yang diisi)
- ✅ shipping_cost = 15000 (default tanpa koordinat)
- ✅ delivery_distance = NULL (tanpa koordinat)
- ✅ estimated_ready_time = now + 30-40 menit

**Error Testing:**
1. Submit tanpa alamat → Error: shipping_address required

---

### 4. EDGE CASE TESTING

#### A. QR Code Invalid
**Test:**
```
http://localhost:8000/scan/invalid-token-12345
```
**Expected:** Redirect ke home dengan error "QR Code tidak valid"

#### B. Order Type Tidak Dipilih
**Test:** Submit form tanpa pilih order type
**Expected:** Error validation "order_type required"

#### C. Delivery Jarak Jauh (Manual Test dengan Koordinat)
**Test:** Tambahkan hidden input latitude/longitude yang jaraknya > 15 km
**Expected:** Error "lokasi Anda terlalu jauh dari outlet kami"

---

### 5. UI/UX TESTING

#### Checkout Page
- ✅ 3 card order type tampil dengan benar
- ✅ Icon & warna sesuai per type
- ✅ Card aktif berubah warna saat dipilih
- ✅ Form berubah dinamis sesuai order type
- ✅ Dine In: tampil notifikasi meja (jika dari QR)
- ✅ Take Away: tampil list outlet
- ✅ Delivery: tampil textarea alamat & info ongkir
- ✅ Responsive di mobile
- ✅ Sidebar summary tetap sticky

#### Admin Outlets Page
- ✅ Tampil semua outlet
- ✅ Tampil QR code per meja
- ✅ Grid layout rapi
- ✅ Download button berfungsi
- ✅ QR code bisa di-scan

---

### 6. INTEGRATION TESTING

#### Cart → Checkout → Payment
**Test Full Flow:**
1. Tambah 3 produk ke cart
2. View cart → summary benar
3. Checkout dengan Dine In (scan QR)
4. Order created
5. Redirect ke payment page
6. Verifikasi order di database

**Expected:**
- ✅ Cart cleared setelah checkout
- ✅ Order items tersimpan
- ✅ Stock produk berkurang
- ✅ Total amount benar (subtotal + shipping)

---

### 7. VALIDATION TESTING

#### Dine In Validation
```
POST /checkout
{
  "order_type": "dine_in",
  "qr_token": "", // KOSONG
  ...
}
```
**Expected:** Error "qr_token required"

#### Take Away Validation
```
POST /checkout
{
  "order_type": "take_away",
  "outlet_id": "", // KOSONG
  ...
}
```
**Expected:** Error "outlet_id required"

#### Delivery Validation
```
POST /checkout
{
  "order_type": "delivery",
  "shipping_address": "", // KOSONG
  ...
}
```
**Expected:** Error "shipping_address required"

---

### 8. SECURITY TESTING

#### QR Token Security
- ✅ Token 32 karakter random
- ✅ Tidak bisa ditebak
- ✅ Unique per meja
- ✅ Validated sebelum digunakan

#### SQL Injection
**Test:**
```
/scan/'; DROP TABLE outlets; --
```
**Expected:** 404 atau redirect dengan error (tidak execute SQL)

---

### 9. PERFORMANCE TESTING

#### Haversine Calculation
**Test:** Order delivery dengan koordinat
**Expected:** Response time < 1 detik

#### QR Redirect
**Test:** Scan QR code
**Expected:** Redirect < 500ms

---

## 📊 TESTING SUMMARY TEMPLATE

```
===========================================
TESTING REPORT - ORDER TYPE FEATURE
Date: _______________
Tester: _______________
===========================================

1. Database Structure        [ ] PASS  [ ] FAIL
2. Routes Availability       [ ] PASS  [ ] FAIL
3. Dine In Flow             [ ] PASS  [ ] FAIL
4. Take Away Flow           [ ] PASS  [ ] FAIL
5. Delivery Flow            [ ] PASS  [ ] FAIL
6. QR Code Generation       [ ] PASS  [ ] FAIL
7. QR Code Scanning         [ ] PASS  [ ] FAIL
8. Shipping Cost Calc       [ ] PASS  [ ] FAIL
9. Estimated Time Calc      [ ] PASS  [ ] FAIL
10. Validation Rules        [ ] PASS  [ ] FAIL
11. UI/UX Checkout          [ ] PASS  [ ] FAIL
12. Admin Outlets Page      [ ] PASS  [ ] FAIL

BUGS FOUND:
___________________________________________
___________________________________________
___________________________________________

NOTES:
___________________________________________
___________________________________________
___________________________________________
```

---

## 🎯 QUICK TEST COMMANDS

```bash
# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear

# Run server
php artisan serve

# Check database
mysql -u root laravel -e "SELECT * FROM outlets;"

# Check routes
php artisan route:list | grep -E "(checkout|scan|outlet)"
```

---

## 🔍 DEBUG TIPS

### Jika QR Scan Tidak Bekerja:
1. Cek QR token di database
2. Cek route `scan.table` terdaftar
3. Test manual: `/scan/{paste-token-dari-db}`
4. Cek error log

### Jika Ongkir Salah:
1. Cek koordinat outlet di database
2. Cek method `distanceTo()` di Outlet model
3. Cek shipping cost logic di CheckoutController

### Jika Form Tidak Berubah:
1. Cek Alpine.js loaded
2. Cek x-data="checkoutData()" ada
3. Cek x-show directive
4. Inspect element → console error

---

Generated: 4 Oktober 2026
