# DVWA Docker Quick Start Guide

## Quick Start (Plug & Play)

### Prerequisites
- Docker & Docker Compose installed
- Port 8000 available (atau change di `.env`)

### 1. Clone Repository
```bash
git clone https://github.com/yourusername/dvwalast.git
cd dvwalast
```

### 2. Setup Environment (Optional)
```bash
cp .env.example .env
# Edit .env jika ingin custom port atau credentials
```

### 3. Run Docker
```bash
docker-compose up -d
```

### 4. Wait for Database
```bash
# Check if both services are healthy
docker-compose ps

# Wait until STATUS shows "healthy" (30-60 seconds)
```

### 5. Access Application
- **Indonesian:** http://localhost:8000/login_id.php
- **English:** http://localhost:8000/login.php

**Default Credentials:**
- Username: `admin`
- Password: `admin123`

### 6. Stop Application
```bash
docker-compose down
```

---

## Advanced Usage

### Custom Configuration

Create `.env` file:
```env
DB_HOST=db
DB_USER=dvwa
DB_PASSWORD=your_secure_password
DB_NAME=dvwa
WEB_PORT=9000  # Custom port
MYSQL_ROOT_PASSWORD=your_root_password
```

Then run:
```bash
docker-compose up -d
```

### View Logs
```bash
# All services
docker-compose logs -f

# Specific service
docker-compose logs -f web
docker-compose logs -f db
```

### Access Database
```bash
# Open MySQL shell
docker-compose exec db mysql -u dvwa -pdvwa123 dvwa

# View tables
SHOW TABLES;
SELECT * FROM users;
```

### Reset Database
```bash
# Stop containers
docker-compose down -v

# Start fresh (will reinit database)
docker-compose up -d
```

### Custom Port
Edit `.env`:
```env
WEB_PORT=9999
```

Then:
```bash
docker-compose up -d
# Access: http://localhost:9999/login_id.php
```

---

## Troubleshooting

### Database not initializing
```bash
# Check if init.sql exists
docker-compose logs db | grep -i "error"

# Rebuild & restart
docker-compose down -v
docker-compose up -d
```

### Port already in use
```bash
# Check what's using the port
netstat -ano | findstr :8000

# Change port in .env
WEB_PORT=8001
docker-compose up -d
```

### Connection refused
```bash
# Make sure db is healthy
docker-compose ps

# Wait 30-60 seconds for database initialization
# Then refresh browser
```

### Permission denied on volumes
```bash
# Fix ownership
docker-compose exec web chown -R www-data:www-data /var/www/html
```

---

## Production Deployment

### Security Considerations
1. ✅ Change default credentials in `.env`
2. ✅ Use strong passwords
3. ✅ Don't expose MySQL port externally
4. ✅ Use HTTPS/SSL (via reverse proxy)
5. ✅ Keep Docker images updated

### Scale Resources
```bash
# Add resource limits to docker-compose.yml
services:
  web:
    deploy:
      resources:
        limits:
          cpus: '1'
          memory: 512M
  db:
    deploy:
      resources:
        limits:
          cpus: '1'
          memory: 1G
```

### Backup Database
```bash
# Backup
docker-compose exec db mysqldump -u dvwa -pdvwa123 dvwa > backup.sql

# Restore
docker-compose exec -T db mysql -u dvwa -pdvwa123 dvwa < backup.sql
```

### Use External Database
```yaml
# In docker-compose.yml
db:
  image: mariadb:11.4
  environment:
    - MYSQL_HOST=external-db.example.com
    - MYSQL_PORT=3306
```

---

## Docker Image Building

### Build locally
```bash
docker build -t dvwa:latest .
```

### Push to Docker Hub
```bash
# Login
docker login

# Tag
docker tag dvwa:latest yourusername/dvwa:latest

# Push
docker push yourusername/dvwa:latest
```

### Run from Docker Hub
```bash
docker run -p 8000:80 \
  -e DB_HOST=db \
  -e DB_USER=dvwa \
  -e DB_PASSWORD=dvwa123 \
  yourusername/dvwa:latest
```

---

## Health Checks

Dockerfile includes health check:
```bash
# Check health
docker-compose ps

# Logs menunjukkan:
# STATUS: Up (healthy) ✅
# STATUS: Up (unhealthy) ❌
# STATUS: Up (health: starting) ⏳
```

---

## Docker Compose Files Explained

### docker-compose.yml
- **web service**: PHP + Apache container
- **db service**: MariaDB container
- **networks**: Bridge network untuk inter-container communication
- **volumes**: Persistent data storage

### Dockerfile
- **Base image**: php:8.2-apache
- **Extensions**: mysqli untuk database
- **Document root**: /var/www/html/app
- **Health check**: Monitors application availability

### .env.example
- Environment variables untuk customization
- Copy ke `.env` dan modify sesuai kebutuhan

---

## Useful Commands

```bash
# Start services
docker-compose up -d

# Stop services
docker-compose stop

# Remove containers
docker-compose down

# Remove containers & volumes
docker-compose down -v

# View logs
docker-compose logs -f

# Rebuild images
docker-compose build --no-cache

# Execute command in container
docker-compose exec web bash

# Check running containers
docker-compose ps

# Restart service
docker-compose restart web
```

---

## Performance Tips

1. **Use named volumes** for better performance
2. **Limit container resources** in docker-compose.yml
3. **Use .dockerignore** to exclude unnecessary files
4. **Cache Docker layers** - put frequently changed lines at bottom
5. **Use image versions** instead of "latest" tag

---

**Happy Containerizing! 🐳**
