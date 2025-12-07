# 🐳 DVWA - Docker Quick Reference

## 🚀 Run Anywhere dengan Docker

DVWA sekarang bisa dijalankan di **mana saja** yang ada Docker installed!

### Minimal Requirements
- Docker & Docker Compose installed
- Port 8000 available
- ~500MB disk space

---

## ⚡ Quick Start

```bash
# 1. Clone repository
git clone https://github.com/yourusername/dvwalast.git
cd dvwalast

# 2. Run Docker
docker-compose up -d

# 3. Access
# Indonesian: http://localhost:8000/login_id.php
# English: http://localhost:8000/login.php

# Credentials: admin / admin123
```

**Siap dalam 2 menit!**

---

## 📝 Commands

### Start
```bash
docker-compose up -d        # Run in background
docker-compose up          # Run in foreground (see logs)
```

### Stop
```bash
docker-compose down        # Stop containers
docker-compose down -v     # Stop & delete volumes (reset database)
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
