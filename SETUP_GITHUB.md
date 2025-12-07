# 🚀 PANDUAN UNTUK TEMAN YANG CLONE DARI GITHUB

## 📊 Pilih Environment

Pilih sesuai setup yang kamu punya:

| Environment | Setup Time | Konfigurasi | Cocok Untuk |
|------------|------------|-------------|-------------|
| 🐳 **[Docker](#-setup-dengan-docker)** | 5 menit | Tidak perlu edit | Punya Docker Desktop, butuh isolasi |
| 📦 **[XAMPP](#-setup-dengan-xampp)** | 3 menit | Edit 1 file | Laptop low-spec, tidak bisa Docker |
| 🚀 **[Laragon](#-setup-dengan-laragon)** | 2 menit | Optional | Developer, sudah pakai Laragon |

**Rekomendasi:**
- ✅ **Docker** - Jika kamu bisa install Docker (paling aman & portable)
- ✅ **XAMPP** - Jika temenmu bilang "gabisa pake Docker" 
- ✅ **Laragon** - Jika sudah pake Laragon untuk project lain

---

## 🐳 Setup dengan Docker

### 1️⃣ Clone Repository
```bash
git clone https://github.com/kendikadimas/dvwa.git
cd dvwa
```

### 2️⃣ Start Docker
Pastikan Docker Desktop sudah running, lalu:
```bash
docker compose up -d
```

Tunggu sampai selesai (30-60 detik).

### 3️⃣ Buka Browser
```
http://localhost:8000
```

Atau versi Indonesia:
```
http://localhost:8000/login_id.php
```

### 4️⃣ Setup Database (Pertama Kali Saja)

Anda akan melihat halaman seperti ini:

```
┌─────────────────────────────────────────┐
│           DVWA - Login                  │
├─────────────────────────────────────────┤
│                                         │
│  ⚠️ Database tidak ditemukan            │
│                                         │
│  🚀 First Time Setup Required           │
│                                         │
│  Klik tombol di bawah untuk create      │
│  database otomatis                      │
│                                         │
│  ┌───────────────────────────────┐     │
│  │   📦 Setup Database           │     │
│  └───────────────────────────────┘     │
│                                         │
└─────────────────────────────────────────┘
```

**Klik tombol "Setup Database"** itu. Nanti akan:
- Buat database `dvwa`
- Buat tabel `users` dan `comments`
- Insert user admin dan sample data
- Semua otomatis!

### 5️⃣ Login
Setelah setup selesai, akan muncul tombol "Go to Login Page". Klik, lalu login:

```
Username: admin
Password: admin123
```

## 🎉 Selesai! (Docker)

Sekarang kamu bisa:
- Test SQL Injection di "Pencarian Surat"
- Test XSS Stored di "Komentar Surat"
- Test XSS Reflected di "Feedback Sistem"
- Test CSRF di "Ganti Password"

---

## 📦 Setup dengan XAMPP

**Cocok untuk:** Windows tanpa Docker, setup cepat

### 1️⃣ Clone Repository
```bash
git clone https://github.com/username/dvwalast.git
cd dvwalast
```

### 2️⃣ Copy ke XAMPP htdocs
Pindahkan seluruh folder ke direktori XAMPP:
```powershell
# Copy folder ke htdocs
xcopy /E /I d:\laragon\www\dvwalast C:\xampp\htdocs\dvwa
```

Atau manual: Copy folder `dvwalast` ke `C:\xampp\htdocs\` lalu rename jadi `dvwa`

### 3️⃣ Edit Konfigurasi Database
**PENTING!** XAMPP menggunakan kredensial berbeda dari Docker.

Edit file `config.php` di baris 5-6:

**Sebelum:**
```php
define('DB_USER', getenv('DB_USER') ?: 'dvwa');
define('DB_PASSWORD', getenv('DB_PASSWORD') ?: 'dvwa123');
```

**Sesudah (untuk XAMPP):**
```php
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASSWORD', getenv('DB_PASSWORD') ?: '');
```

Atau copy template yang sudah disediakan:
```powershell
copy config_xampp_example.php config.php
```

### 4️⃣ Start XAMPP Services
1. Buka **XAMPP Control Panel**
2. Klik tombol **Start** di Apache
3. Klik tombol **Start** di MySQL
4. Tunggu sampai status jadi hijau (Running)

**Catatan:** Jika port 80 atau 3306 konflik, lihat [SETUP_XAMPP.md](SETUP_XAMPP.md) untuk solusi.

### 5️⃣ Buka di Browser
```
http://localhost/dvwa/login.php
```

### 6️⃣ Setup Database Otomatis
Sama seperti Docker:
1. Klik tombol **"📦 Create/Reset Database"**
2. Tunggu proses selesai (5-10 detik)
3. Klik **"Go to Login Page"**
4. Login dengan:
   ```
   Username: admin
   Password: admin123
   ```

### 7️⃣ Verifikasi Installation
Test payload sederhana di **Pencarian Surat**:
```sql
1' OR '1'='1' #
```

## 🎉 Selesai! (XAMPP)

**Perbedaan dengan Docker:**
- ✅ Tidak perlu Docker Desktop
- ✅ Akses langsung di `localhost` (port 80)
- ⚠️ Harus edit `config.php` secara manual
- ⚠️ Database tidak auto-reset saat restart

**Troubleshooting XAMPP:** Lihat [SETUP_XAMPP.md](SETUP_XAMPP.md) untuk masalah seperti:
- Port 80/3306 sudah dipakai
- Apache tidak bisa start
- MySQL connection error
- phpMyAdmin tidak bisa akses

---

## 🚀 Setup dengan Laragon

**Cocok untuk:** Developer yang sudah pakai Laragon, setup tercepat

### 1️⃣ Clone Repository
```bash
git clone https://github.com/username/dvwalast.git
cd dvwalast
```

### 2️⃣ Copy ke Laragon www
Pindahkan folder ke Laragon:
```powershell
# Copy folder ke Laragon www
xcopy /E /I d:\repos\dvwalast d:\laragon\www\dvwa
```

Atau manual: Copy folder ke `d:\laragon\www\dvwa`

### 3️⃣ Check Konfigurasi Database
Laragon menggunakan kredensial yang sama dengan Docker:

File `config.php` baris 5-6:
```php
define('DB_USER', getenv('DB_USER') ?: 'dvwa');
define('DB_PASSWORD', getenv('DB_PASSWORD') ?: 'dvwa123');
```

**Jika username/password database Laragon berbeda:**
1. Buka HeidiSQL dari Laragon
2. Buat user baru `dvwa` dengan password `dvwa123`
3. Grant semua privileges ke user `dvwa`

Atau edit `config.php` sesuai kredensial Laragon kamu (biasanya `root` / kosong).

### 4️⃣ Start Laragon
1. Buka **Laragon**
2. Klik **Start All**
3. Tunggu sampai status Apache dan MySQL hijau

### 5️⃣ Buka di Browser
Laragon otomatis buat virtual host, bisa akses via:
```
http://dvwa.test
```

Atau akses langsung:
```
http://localhost/dvwa/login.php
```

### 6️⃣ Setup Database Otomatis
1. Klik tombol **"📦 Create/Reset Database"**
2. Tunggu proses selesai
3. Login dengan `admin` / `admin123`

### 7️⃣ Bonus: Pretty URLs
Jika pakai virtual host `http://dvwa.test`, Laragon otomatis enable:
- ✅ Auto SSL (https://dvwa.test)
- ✅ Akses dari devices lain di network yang sama
- ✅ Pretty terminal domain

## 🎉 Selesai! (Laragon)

**Keuntungan Laragon:**
- ✅ Tercepat untuk setup (1 klik Start All)
- ✅ Virtual host otomatis (dvwa.test)
- ✅ Tidak perlu edit config (jika user dvwa sudah ada)
- ✅ HeidiSQL built-in untuk manage database
- ✅ Support multiple PHP versions

---

## 🔧 Troubleshooting

### ❌ Docker tidak bisa start
```bash
# Cek apakah Docker Desktop running
docker version

# Kalau error, buka Docker Desktop dulu
# Tunggu sampai icon Docker hijau
```

### ❌ Port 8000 sudah dipakai
Edit file `docker-compose.yml`, cari baris:
```yaml
ports:
  - "8000:80"
```

Ganti jadi:
```yaml
ports:
  - "8080:80"  # atau port lain yang kosong
```

Lalu restart:
```bash
docker compose down
docker compose up -d
```

### ❌ Halaman tidak muncul
```bash
# Cek status container
docker ps

# Harus muncul 2 container:
# - dvwa_web (Apache + PHP)
# - dvwa_db (MariaDB)

# Kalau tidak ada, coba restart
docker compose down
docker compose up -d
```

### ❌ Setup Database gagal
Kemungkinan database container belum ready. Tunggu 30 detik lagi, lalu:
1. Klik tombol "🔄 Retry Connection"
2. Atau refresh halaman (F5)
3. Lalu klik "Setup Database" lagi

### ❌ [XAMPP] Apache tidak bisa start
**Penyebab:** Port 80 sudah dipakai (Skype, IIS, atau aplikasi lain)

**Solusi 1: Ganti port Apache**
1. Edit `C:\xampp\apache\conf\httpd.conf`
2. Cari `Listen 80`, ganti jadi `Listen 8080`
3. Restart Apache
4. Akses di `http://localhost:8080/dvwa/login.php`

**Solusi 2: Stop aplikasi yang pakai port 80**
```powershell
# Check aplikasi yang pakai port 80
netstat -ano | findstr :80
```

Lihat [SETUP_XAMPP.md](SETUP_XAMPP.md) untuk detail troubleshooting.

### ❌ [XAMPP] MySQL connection error
**Error:** `Connection failed: Access denied for user 'dvwa'@'localhost'`

**Penyebab:** Belum edit `config.php` atau user `dvwa` belum dibuat

**Solusi:**
1. Edit `config.php` line 5-6 jadi `root` / kosong:
   ```php
   define('DB_USER', getenv('DB_USER') ?: 'root');
   define('DB_PASSWORD', getenv('DB_PASSWORD') ?: '');
   ```
2. Atau buat user `dvwa` di phpMyAdmin:
   - Buka `http://localhost/phpmyadmin`
   - Tab "User accounts" → "Add user account"
   - Username: `dvwa`, Password: `dvwa123`
   - Check "Create database with same name"
   - Check "Grant all privileges"

### ❌ [Laragon] Virtual host tidak bisa diakses
**Error:** `dvwa.test` tidak bisa dibuka

**Solusi:**
1. Pastikan Laragon sudah Start All
2. Klik kanan icon Laragon → **Menu** → **Nginx/Apache** → **Reload**
3. Check file `C:\Windows\System32\drivers\etc\hosts`:
   ```
   127.0.0.1    dvwa.test
   ```
   Jika tidak ada, tambahkan manual atau klik **Laragon Menu** → **Tools** → **Quick add** → **dvwa.test**

### ❌ [Laragon] Database user tidak ada
**Error:** `Access denied for user 'dvwa'@'localhost'`

**Solusi:**
1. Klik icon Laragon → **Database** → **HeidiSQL**
2. Klik **Session** → **New**
3. Root password biasanya kosong (blank)
4. Klik **Open**
5. Di menu, **Tools** → **User Manager**
6. Klik **Add** → Username: `dvwa`, Password: `dvwa123`
7. Tab **Objects**, grant all privileges ke database `dvwa`

## 📚 Dokumentasi Lengkap

Setelah berhasil login, baca dokumentasi ini:

1. **PAYLOADS.md** - Semua payload untuk SQLi, XSS, CSRF
2. **EVASION_PAYLOADS.md** - 40+ teknik bypass IDS
3. **LAPORAN_IDS_EVASION.md** - Laporan lengkap (BAB I-V)
4. **TABEL_REKAP_TESTING.md** - Hasil testing IDS

## 🎯 Quick Test

Setelah login, coba payload ini di **Pencarian Surat**:

### Test 1: Show All Records
```sql
1' OR '1'='1' #
```
Hasil: Semua data surat muncul

### Test 2: Extract Users
```sql
1' UNION SELECT username,password,3,created_at FROM users #
```
Hasil: Username dan password hash muncul

### Test 3: XSS
Di form **Komentar Surat**, isi:
```html
<script>alert('XSS Works!')</script>
```
Hasil: Alert box muncul

## 🌐 Akses dari Kali VM

Untuk test IDS (Suricata/Snort3) dari Kali Linux:

### 1️⃣ Cek IP Windows
```powershell
ipconfig | findstr IPv4
```
Contoh output: `192.168.1.100`

### 2️⃣ Akses dari Kali (sesuai environment)

**Jika pakai Docker:**
```bash
# Di Kali VM
firefox http://192.168.1.100:8000 &
```

**Jika pakai XAMPP/Laragon:**
```bash
# Di Kali VM (port default 80)
firefox http://192.168.1.100/dvwa/login.php &
```

### 3️⃣ Konfigurasi Firewall Windows
Jika tidak bisa akses, allow port di Windows Firewall:

**Docker (port 8000):**
```powershell
netsh advfirewall firewall add rule name="DVWA Docker" dir=in action=allow protocol=TCP localport=8000
```

**XAMPP/Laragon (port 80):**
```powershell
netsh advfirewall firewall add rule name="DVWA HTTP" dir=in action=allow protocol=TCP localport=80
```

**Atau disable firewall sementara (testing only):**
```powershell
netsh advfirewall set allprofiles state off
```

### 4️⃣ Setup IDS di Kali
```bash
# Install Suricata
sudo apt update
sudo apt install suricata -y

# Download custom rules
git clone https://github.com/username/dvwa-ids-rules.git
cd dvwa-ids-rules

# Load rules
sudo cp rules/*.rules /etc/suricata/rules/
sudo suricata -c /etc/suricata/suricata.yaml -i eth0
```

### 5️⃣ Monitor Traffic
```bash
# Tail Suricata logs
sudo tail -f /var/log/suricata/fast.log

# Test dengan payload evasion
curl "http://192.168.1.100:8000/pencarian.php?id=1%27%20OR%20%271%27=%271"
```

Lihat [LAPORAN_IDS_EVASION.md](LAPORAN_IDS_EVASION.md) untuk setup IDS lengkap dan 40+ payload evasion.

## 💡 Tips & Best Practices

### Kredensial Default
- **Admin:** `admin` / `admin123`
- **Sample Users:** john, jane, bob (password: `{username}123`)

### Database Management
- **Docker:** Database reset otomatis setiap restart container
- **XAMPP/Laragon:** Database persisten, gunakan tombol "Reset Data" untuk clear
- **Create/Reset DB:** Hapus semua data dan buat ulang dari awal
- **Reset Data:** Hanya clear comments (untuk testing XSS)

### Testing Workflow
1. Login sebagai admin
2. Test SQLi di **Pencarian Surat**
3. Test XSS Stored di **Komentar Surat**
4. Klik "Reset Data" untuk clear comments
5. Test XSS Reflected di **Feedback Sistem**
6. Test CSRF di **Ganti Password**

### Environment Selection Guide

**Pilih Docker jika:**
- ✅ Kamu butuh environment terisolasi
- ✅ Mau portable (bisa pindah-pindah server)
- ✅ Tidak masalah install Docker Desktop
- ✅ Ada RAM 4GB+ free

**Pilih XAMPP jika:**
- ✅ Laptop/PC low-spec
- ✅ Sudah familiar dengan XAMPP
- ✅ Tidak bisa install Docker
- ✅ Butuh setup cepat (5 menit)

**Pilih Laragon jika:**
- ✅ Developer yang sudah pakai Laragon
- ✅ Mau virtual host (dvwa.test)
- ✅ Butuh manage multiple PHP projects
- ✅ Suka tools built-in (HeidiSQL, Terminal)

### Performance Tips
- **Docker:** Allocate minimal 2GB RAM di Docker Desktop settings
- **XAMPP:** Stop service yang tidak dipakai (FileZilla, Mercury)
- **Laragon:** Enable Nginx jika Apache lambat
- **Semua:** Clear browser cache jika payload XSS tidak muncul

### Security Notes
⚠️ **JANGAN deploy ke production!**
- Aplikasi ini SENGAJA vulnerable
- Default password sangat lemah
- Tidak ada input validation
- Tidak ada CSRF protection
- Hanya untuk learning/testing IDS

## ❓ Butuh Bantuan?

1. Baca [README.md](README.md) - Panduan lengkap
2. Baca [LAPORAN_IDS_EVASION.md](LAPORAN_IDS_EVASION.md) - Troubleshooting detail
3. Check GitHub Issues

---

**Happy Hacking! 🎓🔐**

*Project ini untuk educational purposes only. Jangan deploy ke production!*
