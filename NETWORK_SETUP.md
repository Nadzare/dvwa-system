# 🔗 DVWA Network Setup - Access dari Kali VM & Windows

## Konfigurasi untuk Kali Linux VM

Panduan ini untuk menjalankan DVWA di Windows host dan mengakses dari Kali Linux VM.

---

## ⚙️ Setup Step-by-Step

### Step 1: Dapatkan IP Windows

Buka PowerShell dan jalankan:

```powershell
ipconfig
```

Cari `Ethernet adapter` atau `WiFi` section dan catat **IPv4 Address** (contoh: `192.168.1.100`)

### Step 2: Start DVWA Docker

```bash
cd d:\laragon\www\dvwalast
docker-compose up -d
```

Verifikasi berjalan:
```bash
docker-compose ps
# STATUS: Up (healthy) ✅
```

### Step 3: Akses dari Windows Host

```
http://localhost:8000/login_id.php
http://localhost:8000/login.php
```

### Step 4: Akses dari Kali Linux VM

**Ganti `WINDOWS_IP` dengan IP dari Step 1 (contoh: `192.168.1.100`)**

```
http://WINDOWS_IP:8000/login_id.php
http://WINDOWS_IP:8000/login.php
```

**Contoh:**
```
http://192.168.1.100:8000/login_id.php
```

---

## 🔥 Firewall Configuration (Windows)

Jika tidak bisa akses dari Kali VM, butuh allow port 8000:

### Windows 11 Firewall

**Via PowerShell (Admin):**

```powershell
# Allow port 8000
New-NetFirewallRule -DisplayName "DVWA Port 8000" -Direction Inbound -Action Allow -Protocol TCP -LocalPort 8000

# Verify
Get-NetFirewallRule -DisplayName "DVWA Port 8000" | Get-NetFirewallPortFilter
```

**Via GUI:**
1. Windows Security → Firewall & Network Protection
2. Advanced Settings
3. Inbound Rules → New Rule
4. Port → TCP → 8000
5. Allow the connection
6. Apply to Domain, Private, Public

### Windows 10 Firewall

Control Panel → Windows Defender Firewall → Advanced Settings → Inbound Rules → New Rule

---

## 🌐 Network Modes

### Mode 1: Bridge (NAT) - Recommended for VM

Kali VM bisa access Windows host langsung via IP.

**Current Setup:** ✅ Already configured

```yaml
ports:
  - "0.0.0.0:8000:80"  # Bind to all interfaces
```

### Mode 2: Host Network

Edit docker-compose.yml:

```yaml
services:
  web:
    network_mode: "host"  # Gunakan host network
    ports:
      - "8000:80"
```

**Pros:** Direct access, lebih cepat
**Cons:** Port conflict dengan host

### Mode 3: Custom Bridge Network

```yaml
networks:
  dvwa_network:
    driver: bridge
    ipam:
      config:
        - subnet: 172.20.0.0/16
```

---

## 📡 Troubleshooting

### ❌ "Cannot connect to http://WINDOWS_IP:8000"

**Solution 1: Check Windows Firewall**
```powershell
# Allow port
netsh advfirewall firewall add rule name="DVWA" dir=in action=allow protocol=tcp localport=8000

# Verify
netsh advfirewall firewall show rule name="DVWA"
```

**Solution 2: Check if Docker listening**
```powershell
# Check port binding
netstat -ano | findstr :8000

# Should show: LISTENING
```

**Solution 3: Check Docker network**
```bash
docker network ls
docker network inspect dvwalast_dvwa_network
```

**Solution 4: Verify containers running**
```bash
docker-compose ps
# Both web and db should show "Up (healthy)"
```

### ❌ "Connection refused"

**Check if Docker daemon running:**
```powershell
docker ps
# Should list containers, not error
```

**Restart Docker:**
```bash
docker-compose down
docker-compose up -d
```

### ❌ Database connection error

**Wait for database initialization (30-60 seconds)**

```bash
docker-compose logs db | tail -20
# Should show: "ready for connections"
```

### ⚠️ Port 8000 already in use

**Change port in .env:**
```env
WEB_PORT=9000
```

**Or edit docker-compose.yml:**
```yaml
ports:
  - "0.0.0.0:9000:80"  # Change 9000 to any free port
```

---

## 🧪 Test Connectivity

### From Windows Host

```powershell
# Test port is open
Test-NetConnection localhost -Port 8000

# Test with curl
curl http://localhost:8000/login_id.php
```

### From Kali Linux VM

```bash
# Test connectivity to Windows
ping 192.168.1.100

# Test port is open
nc -zv 192.168.1.100 8000

# Test with curl
curl http://192.168.1.100:8000/login_id.php

# Using nmap
nmap -p 8000 192.168.1.100
```

---

## 📊 Network Architecture

```
┌─────────────────────────────────────────────┐
│         Windows Host                        │
│  IP: 192.168.1.100                         │
│  ┌──────────────────────────────────────┐   │
│  │ Docker Engine                        │   │
│  │ ┌───────────┐     ┌──────────────┐  │   │
│  │ │ dvwa_web  │────▶│ Port 8000    │  │   │
│  │ │ (Apache)  │     │ (Exposed)    │  │   │
│  │ └───────────┘     └──────────────┘  │   │
│  │ ┌───────────┐                       │   │
│  │ │ dvwa_db   │                       │   │
│  │ │ (MariaDB) │                       │   │
│  │ └───────────┘                       │   │
│  └──────────────────────────────────────┘   │
└─────────────────────────────────────────────┘
              ▲
              │ Network (Bridge)
              ▼
┌─────────────────────────────────────────────┐
│       Kali Linux VM                         │
│  IP: 192.168.1.50 (atau DHCP)              │
│                                             │
│  $ curl http://192.168.1.100:8000/         │
└─────────────────────────────────────────────┘
```

---

## 🔐 Security Tips

✅ **Firewall:** Only allow from trusted networks
✅ **Password:** Change default credentials
✅ **Network:** Don't expose to public internet
✅ **VPN:** Use VPN jika access dari outside

### Restrict Firewall to Specific IP

```powershell
# Allow hanya dari Kali VM (contoh: 192.168.1.50)
netsh advfirewall firewall add rule name="DVWA Kali Only" dir=in action=allow protocol=tcp localport=8000 remoteip=192.168.1.50
```

---

## 🚀 Advanced Setup

### Multiple VM Access

Jika ada multiple Kali VM:

```powershell
# Allow multiple IPs
netsh advfirewall firewall add rule name="DVWA MultiVM" dir=in action=allow protocol=tcp localport=8000 remoteip=192.168.1.50-192.168.1.60
```

### Custom Docker Network

```yaml
networks:
  dvwa_network:
    driver: bridge
    driver_opts:
      com.docker.network.bridge.name: br_dvwa
```

### Network Isolation

```yaml
services:
  web:
    networks:
      - dvwa_network
    ports:
      - "0.0.0.0:8000:80"
```

---

## 📋 Quick Reference

| Task | Command |
|------|---------|
| Get Windows IP | `ipconfig` |
| Start DVWA | `docker-compose up -d` |
| Stop DVWA | `docker-compose down` |
| View logs | `docker-compose logs -f` |
| Test port (Windows) | `Test-NetConnection localhost -Port 8000` |
| Test port (Kali) | `nc -zv WINDOWS_IP 8000` |
| Allow firewall | `netsh advfirewall firewall add rule name="DVWA" dir=in action=allow protocol=tcp localport=8000` |

---

## ✅ Verification Checklist

- [ ] DVWA running: `docker-compose ps` → both "Up (healthy)"
- [ ] Port exposed: `netstat -ano \| findstr :8000` → listening
- [ ] Firewall allowed: `netsh advfirewall firewall show rule name="DVWA"`
- [ ] Windows IP obtained: `ipconfig` → IPv4 Address
- [ ] Kali can ping: `ping WINDOWS_IP`
- [ ] Kali can access: `curl http://WINDOWS_IP:8000`
- [ ] Login works: admin / admin123

---

## 🆘 Still Can't Connect?

1. **Check Windows IP is not 127.0.0.1 or 10.x.x.x**
   - Gunakan real IP (192.168.x.x, 10.x.x.x, atau 172.16.x.x)

2. **Verify network card of VM**
   - Set ke "Bridged" mode (bukan NAT)
   - Or use "Host-only" + IP routing

3. **Check Docker binding**
   ```bash
   docker port dvwa_web
   # Should show: 80/tcp -> 0.0.0.0:8000
   ```

4. **Test dengan different port**
   ```bash
   # Edit .env atau docker-compose.yml
   WEB_PORT=9999
   docker-compose restart
   # Test: http://WINDOWS_IP:9999
   ```

5. **Check if VirtualBox/Hyper-V conflict**
   - WSL2 + Docker Desktop minimal Windows 10 Build 2004
   - Hyper-V must be enabled

6. **Ask for help:**
   - Provide: `ipconfig` output
   - Provide: `docker-compose ps` output
   - Provide: `docker network inspect dvwalast_dvwa_network`
   - Provide: Error message dari curl/browser

---

**Happy Penetration Testing! 🔥**
