# 🔧 PANDUAN SETUP XAMPP (Alternative Docker)

## ⚠️ Masalah Umum XAMPP vs Docker

Jika teman menggunakan **XAMPP** dan tidak bisa menjalankan Docker, berikut solusinya:

---

## 🚀 OPSI 1: Setup XAMPP (Tanpa Docker)

### Prerequisites
- XAMPP installed (Apache + MySQL/MariaDB)
- PHP 7.4+ dan MySQL 5.7+
- Git untuk clone repository

### Langkah Setup

#### 1. Clone Repository
```bash
git clone https://github.com/kendikadimas/dvwa.git
cd dvwa
```

#### 2. Copy ke XAMPP htdocs
```bash
# Windows
xcopy /E /I app C:\xampp\htdocs\dvwa

# Atau manual copy folder 'app' ke C:\xampp\htdocs\dvwa
```

#### 3. Start XAMPP
- Buka XAMPP Control Panel
- Start **Apache**
- Start **MySQL**

#### 4. Buka Browser
```
http://localhost/dvwa/login.php
```
atau
```
http://localhost/dvwa/login_id.php
```

#### 5. Klik "📦 Create/Reset DB"
Database akan dibuat otomatis!

#### 6. Login
```
Username: admin
Password: admin123
```

### ✅ Selesai!

---

## 🔧 Konfigurasi XAMPP

### Jika Database Error

Edit file `C:\xampp\htdocs\dvwa\config.php`:

```php
<?php
// XAMPP Default Configuration
define('DB_HOST', 'localhost');      // atau '127.0.0.1'
define('DB_USER', 'root');           // XAMPP default user
define('DB_PASSWORD', '');           // XAMPP default: kosong
define('DB_NAME', 'dvwa');
define('DB_PORT', 3306);
```

**Catatan:** XAMPP default password untuk root adalah **kosong** (empty string).

### Jika Port Conflict

#### Apache Port Conflict (80 sudah dipakai)
Edit `C:\xampp\apache\conf\httpd.conf`:
```apache
Listen 8080
ServerName localhost:8080
```

Akses jadi:
```
http://localhost:8080/dvwa/login.php
```

#### MySQL Port Conflict (3306 sudah dipakai)
Edit `C:\xampp\mysql\bin\my.ini`:
```ini
[mysqld]
port=3307
```

Lalu update `config.php`:
```php
define('DB_PORT', 3307);
```

---

## 🐳 OPSI 2: Fix Docker di XAMPP Environment

### Problem: Docker dan XAMPP Port Conflict

#### Solusi A: Stop XAMPP saat pakai Docker
```bash
# Stop XAMPP Apache dan MySQL
# Lalu jalankan Docker
docker compose up -d
```

#### Solusi B: Ubah Port Docker
Edit `docker-compose.yml`:
```yaml
services:
  web:
    ports:
      - "8080:80"  # Ganti dari 8000 ke 8080 atau port lain
```

Akses jadi:
```
http://localhost:8080
```

---

## 📊 Perbandingan Environment

| Fitur | Docker | XAMPP | Laragon |
|-------|--------|-------|---------|
| Setup | Auto | Manual copy | Manual copy |
| Database | Auto create | Auto via web | Auto via web |
| Port default | 8000 | 80 | 80 |
| Isolation | ✅ Yes | ❌ No | ❌ No |
| Portable | ✅ Yes | ⚠️ Medium | ⚠️ Medium |
| Easy Reset | ✅ Easy | ⚠️ Manual | ⚠️ Manual |

---

## 🔍 Troubleshooting XAMPP

### 1. Apache Tidak Start

**Error:** Port 80 already in use

**Solusi:**
- Cek aplikasi yang pakai port 80 (Skype, IIS, etc)
- Atau ubah port Apache ke 8080 (lihat di atas)

### 2. MySQL Tidak Start

**Error:** Port 3306 already in use

**Solusi:**
- Stop MySQL service di Windows
- Atau ubah port MySQL XAMPP ke 3307

### 3. Database Connection Error

**Error:** "Database not found" atau "Access denied"

**Cek:**
```php
// config.php harus sesuai XAMPP
DB_HOST = 'localhost' atau '127.0.0.1'
DB_USER = 'root'
DB_PASSWORD = ''  // kosong untuk XAMPP default
```

**Solusi:**
1. Buka phpMyAdmin: http://localhost/phpmyadmin
2. Cek apakah user 'root' password kosong
3. Atau buat user baru 'dvwa' dengan password 'dvwa123'

### 4. Blank Page / 500 Error

**Kemungkinan:**
- PHP error_reporting off
- File permission issue
- .htaccess conflict

**Solusi:**
```bash
# Aktifkan error reporting
# Edit C:\xampp\php\php.ini
display_errors = On
error_reporting = E_ALL
```

### 5. Session Error

**Error:** "Session could not be started"

**Solusi:**
```bash
# Cek folder session writable
# C:\xampp\tmp harus ada dan writable
```

---

## 🌐 Akses dari Komputer Lain (Network)

### Setup XAMPP untuk Network Access

1. **Edit httpd.conf**
   File: `C:\xampp\apache\conf\httpd.conf`
   ```apache
   # Ganti
   Listen 127.0.0.1:80
   
   # Jadi
   Listen 0.0.0.0:80
   ```

2. **Edit httpd-xampp.conf**
   File: `C:\xampp\apache\conf\extra\httpd-xampp.conf`
   ```apache
   <Directory "C:/xampp/htdocs">
       Require all granted  # Atau sesuaikan IP range
   </Directory>
   ```

3. **Cek Firewall**
   - Allow port 80 di Windows Firewall
   - Atau matikan firewall sementara untuk testing

4. **Cek IP Windows**
   ```powershell
   ipconfig | findstr IPv4
   ```
   Contoh output: `192.168.1.100`

5. **Akses dari Kali VM**
   ```
   http://192.168.1.100/dvwa/login.php
   ```

---

## 📝 Script Helper untuk XAMPP

### check_xampp.bat
Simpan sebagai `check_xampp.bat` di folder dvwa:

```batch
@echo off
echo ==========================================
echo XAMPP Environment Checker for DVWA
echo ==========================================
echo.

echo [1] Checking Apache...
tasklist /FI "IMAGENAME eq httpd.exe" 2>NUL | find /I /N "httpd.exe">NUL
if "%ERRORLEVEL%"=="0" (
    echo     Apache: RUNNING
) else (
    echo     Apache: NOT RUNNING
    echo     Please start Apache from XAMPP Control Panel
)

echo.
echo [2] Checking MySQL...
tasklist /FI "IMAGENAME eq mysqld.exe" 2>NUL | find /I /N "mysqld.exe">NUL
if "%ERRORLEVEL%"=="0" (
    echo     MySQL: RUNNING
) else (
    echo     MySQL: NOT RUNNING
    echo     Please start MySQL from XAMPP Control Panel
)

echo.
echo [3] Checking PHP...
C:\xampp\php\php.exe -v
if %ERRORLEVEL% EQU 0 (
    echo     PHP: OK
) else (
    echo     PHP: ERROR
)

echo.
echo [4] Testing Database Connection...
C:\xampp\php\php.exe -r "$m = @new mysqli('localhost', 'root', '', 'dvwa'); echo $m->connect_error ? 'DB: NOT FOUND (Normal jika pertama kali)' : 'DB: EXISTS';"

echo.
echo [5] Your DVWA URL:
echo     http://localhost/dvwa/login.php
echo     http://localhost/dvwa/login_id.php
echo.
pause
```

**Cara pakai:**
1. Double-click `check_xampp.bat`
2. Lihat status Apache, MySQL, PHP, Database
3. Fix yang error

---

## 🎯 Quick Commands

### Start XAMPP (via Command Line)
```batch
# Start Apache
C:\xampp\apache_start.bat

# Start MySQL
C:\xampp\mysql_start.bat

# Stop Apache
C:\xampp\apache_stop.bat

# Stop MySQL
C:\xampp\mysql_stop.bat
```

### Manual Database Setup (jika auto-setup gagal)

1. Buka phpMyAdmin: http://localhost/phpmyadmin
2. Klik "New" untuk buat database baru
3. Nama database: `dvwa`
4. Collation: `utf8mb4_unicode_ci`
5. Klik "Create"
6. Import SQL (opsional, atau pakai web UI setup)

---

## 💡 Tips & Tricks

### 1. Matikan UAC Notification
Agar XAMPP tidak perlu admin terus:
- Control Panel → User Accounts → Change User Account Control
- Drag ke bawah

### 2. Auto-start XAMPP
- XAMPP Control Panel → Config → Service Settings
- Centang "Apache" dan "MySQL"
- Install as Windows Service

### 3. Backup Database
```batch
# Via Command Line
C:\xampp\mysql\bin\mysqldump -u root dvwa > backup.sql

# Restore
C:\xampp\mysql\bin\mysql -u root dvwa < backup.sql
```

### 4. Reset XAMPP MySQL Root Password
```batch
# Stop MySQL
# Edit C:\xampp\mysql\bin\my.ini
# Tambah di [mysqld]:
skip-grant-tables

# Start MySQL
# Buka command prompt:
C:\xampp\mysql\bin\mysql -u root
UPDATE mysql.user SET Password=PASSWORD('') WHERE User='root';
FLUSH PRIVILEGES;
EXIT;

# Hapus skip-grant-tables dari my.ini
# Restart MySQL
```

---

## 📞 Support

### Jika Masih Error:

1. **Cek XAMPP Logs:**
   - Apache: `C:\xampp\apache\logs\error.log`
   - MySQL: `C:\xampp\mysql\data\mysql_error.log`
   - PHP: `C:\xampp\php\logs\php_error_log`

2. **Cek PHP Info:**
   Buat file `info.php` di `C:\xampp\htdocs`:
   ```php
   <?php phpinfo(); ?>
   ```
   Buka: http://localhost/info.php

3. **Reinstall XAMPP:**
   - Backup data dulu
   - Uninstall XAMPP
   - Download versi terbaru: https://www.apachefriends.org/
   - Install ulang

---

## 🔗 Alternative: Portable USB

### Setup XAMPP Portable di USB

1. Download XAMPP Portable
2. Install ke USB drive (misal `E:\xampp`)
3. Copy folder `app` ke `E:\xampp\htdocs\dvwa`
4. Run `E:\xampp\xampp-control.exe`
5. Start Apache & MySQL
6. Akses: http://localhost/dvwa

**Keuntungan:** Bisa dibawa kemana-mana, tidak perlu install di setiap komputer!

---

## ✅ Kesimpulan

**Untuk XAMPP User:**
1. Copy folder `app` ke `C:\xampp\htdocs\dvwa`
2. Start Apache & MySQL di XAMPP Control Panel
3. Buka http://localhost/dvwa/login.php
4. Klik tombol "Create Database"
5. Login: admin / admin123

**Untuk Docker User:**
1. `docker compose up -d`
2. Buka http://localhost:8000
3. Klik "Create Database"
4. Done!

Kedua cara sama-sama work! 🎉

---

**Last Updated:** December 7, 2025  
**Tested on:** XAMPP 8.2.12, Windows 10/11
