# 🚀 Quick Start - Pilih Environment Kamu

## 📊 Temenmu Clone dari GitHub?

Pilih sesuai yang kamu punya:

| Kamu Punya? | Pilih Ini | Setup | Config |
|------------|-----------|-------|--------|
| Docker Desktop | 🐳 Docker | 5 min | ❌ |
| XAMPP | 📦 XAMPP | 3 min | ✅ Edit 2 baris |
| Laragon | 🚀 Laragon | 2 min | ⚠️ Optional |

---

## 🐳 Pakai Docker

```bash
git clone https://github.com/kendikadimas/dvwa.git
cd dvwa
docker compose up -d
```

Buka: **http://localhost:8000**

✅ Paling aman & portable  
❌ Butuh Docker Desktop + RAM 500MB+

📖 [Panduan lengkap](SETUP_GITHUB.md#-setup-dengan-docker)

---

## 📦 Pakai XAMPP

```bash
git clone https://github.com/kendikadimas/dvwa.git
cd dvwa
xcopy /E /I dvwa C:\xampp\htdocs\dvwa
```

**PENTING! Edit `config.php` baris 5-6:**
```php
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASSWORD', getenv('DB_PASSWORD') ?: '');
```

Start **Apache + MySQL** di XAMPP Control Panel

Buka: **http://localhost/dvwa/login.php**

✅ Tercepat, RAM rendah  
❌ Harus edit config

📖 [Panduan lengkap](SETUP_GITHUB.md#-setup-dengan-xampp) | [Troubleshooting](SETUP_XAMPP.md)

---

## 🚀 Pakai Laragon

```bash
git clone https://github.com/kendikadimas/dvwa.git
cd dvwa
xcopy /E /I dvwa d:\laragon\www\dvwa
```

Klik **Start All** di Laragon

Buka: **http://dvwa.test** atau **http://localhost/dvwa**

✅ Paling cepat (2 menit)  
⚠️ Mungkin perlu buat user MySQL

📖 [Panduan lengkap](SETUP_GITHUB.md#-setup-dengan-laragon)

---

## 🎯 Setelah Setup

**Klik tombol ini di login page:**
- **📦 Create/Reset Database** → Buat database otomatis
- **🔄 Reset Data** → Clear comments (XSS testing)

**Login:**
```
Username: admin
Password: admin123
```

**Test SQLi:**
```sql
1' OR '1'='1' #
```

---

## ❌ Troubleshooting Cepat

### XAMPP - Access Denied
```php
// config.php HARUS root/empty, BUKAN dvwa/dvwa123
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASSWORD', getenv('DB_PASSWORD') ?: '');
```

### XAMPP - Apache Tidak Start
```powershell
# Check port 80
netstat -ano | findstr :80
# Ganti port Apache ke 8080 di httpd.conf
```

### Docker - Port 8000 Dipakai
```yaml
# docker-compose.yml
ports:
  - "8080:80"  # ganti dari 8000
```

### Laragon - Virtual Host Error
```
# Tambah di C:\Windows\System32\drivers\etc\hosts
127.0.0.1    dvwa.test
```

---

## 📚 Dokumentasi Lengkap

1. **[SETUP_GITHUB.md](SETUP_GITHUB.md)** - Panduan lengkap semua environment
2. **[SETUP_XAMPP.md](SETUP_XAMPP.md)** - Troubleshooting khusus XAMPP
3. **[EVASION_PAYLOADS.md](EVASION_PAYLOADS.md)** - 40+ payload bypass IDS
4. **[LAPORAN_IDS_EVASION.md](LAPORAN_IDS_EVASION.md)** - Laporan BAB I-V

---

## 🐳 Docker Commands (Opsional)

### Start
```bash
docker compose up -d        # Run in background
docker compose up          # Run with logs
```

### Stop
```bash
docker compose down        # Stop containers
docker compose down -v     # Stop & reset database
```

### Logs
```bash
docker-compose logs -f              # All services
docker-compose logs -f web          # Web service only
docker-compose logs -f db           # Database only
```

### Status
```bash
docker-compose ps                   # Show running containers
```

### Database Access
```bash
docker-compose exec db mysql -u dvwa -pdvwa123 dvwa
```

---

## ⚙️ Custom Configuration

### Custom Port

Option 1: Create `.env` file:
```env
WEB_PORT=9999
```

Option 2: Edit docker-compose.yml:
```yaml
ports:
  - "9999:80"  # Change 9999 to your port
```

Then:
```bash
docker-compose up -d
# Access: http://localhost:9999/login_id.php
```

### Custom Database Credentials

Create `.env` file:
```env
DB_USER=myuser
DB_PASSWORD=mypassword
DB_NAME=mydb
```

Then:
```bash
docker-compose up -d
```

---

## 🔧 Troubleshooting

### Port 8000 already in use
```bash
# Find what's using port 8000
netstat -ano | findstr :8000

# Change port in docker-compose.yml or .env
# Then: docker-compose up -d
```

### Database connection error
```bash
# Wait 30 seconds for database initialization
# Check logs:
docker-compose logs db

# If still error, rebuild:
docker-compose down -v
docker-compose up -d
```

### Health check failed
```bash
# Check container health
docker-compose ps

# View detailed logs
docker-compose logs web
docker-compose logs db
```

### Permission denied
```bash
# Fix file permissions
docker-compose exec web chown -R www-data:www-data /var/www/html
```

---

## 🌍 Supported Platforms

✅ Windows (with Docker Desktop)
✅ macOS (with Docker Desktop)
✅ Linux (Docker Engine)
✅ Cloud (AWS, Azure, GCP, Railway, etc)

---

## 📦 What's Included

- **PHP 8.2** - Latest stable PHP
- **Apache 2.4** - Web server
- **MariaDB 11.4** - Database
- **mysqli** - PHP database extension
- **Health checks** - Auto monitoring
- **Volume persistence** - Data survives restarts

---

## 🔒 Security Notes

⚠️ **DVWA is INTENTIONALLY VULNERABLE** for learning purposes!

- ✅ Only use for training
- ✅ Don't expose to untrusted networks
- ✅ Change default credentials in production
- ✅ Keep Docker updated

---

## 📚 More Resources

- Full Docker guide: `DOCKER_GUIDE.md`
- Railway deployment: `RAILWAY_STEP_BY_STEP.md`
- Project info: `README.md`

---

## 🚀 Deploy to Cloud

DVWA bisa di-deploy ke:
- **Railway.app** - Easiest option
- **DigitalOcean App Platform**
- **AWS ECS**
- **Azure Container Instances**
- **Google Cloud Run**

Lihat `RAILWAY_STEP_BY_STEP.md` untuk instruksi.

---

**Happy Testing! 🔥**
