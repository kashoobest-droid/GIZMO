# Deployment Guide

Complete instructions for deploying Tech Store App to production environments.

## Table of Contents

1. [Pre-Deployment Checklist](#pre-deployment-checklist)
2. [Environment Setup](#environment-setup)
3. [Deployment Steps](#deployment-steps)
4. [Post-Deployment Verification](#post-deployment-verification)
5. [Monitoring & Maintenance](#monitoring--maintenance)
6. [Troubleshooting](#troubleshooting)
7. [Rollback Procedure](#rollback-procedure)

---

## Pre-Deployment Checklist

### Code Quality
- [ ] All tests passing locally
- [ ] No debugging code or `dd()` statements
- [ ] No `console.log()` in JavaScript
- [ ] Sensitive data removed from codebase
- [ ] `.env.example` updated with all variables
- [ ] Git repository clean (no uncommitted changes)

### Security Audit
- [ ] All passwords hashed with Bcrypt
- [ ] HTTPS enabled on domain
- [ ] CSRF tokens present on all forms
- [ ] IDOR vulnerabilities fixed (UserPolicy in place)
- [ ] SQL injection prevented (parameterized queries)
- [ ] XSS prevention (Blade escaping enabled)
- [ ] API authentication configured
- [ ] File upload validation enabled
- [ ] Environment variables secured

### Documentation
- [ ] README.md complete
- [ ] SETUP.md installation guide written
- [ ] API_DOCUMENTATION.md created
- [ ] SECURITY.md reviewed
- [ ] Database schema documented
- [ ] Deployment runbook approved

### Performance
- [ ] Database indexes added for frequently queried columns
- [ ] Eager loading configured (prevent N+1 queries)
- [ ] Caching strategy in place
- [ ] Assets minified (CSS/JavaScript)
- [ ] Images optimized for web
- [ ] Database queries profiled

### Backups
- [ ] Backup strategy documented
- [ ] Database backup tested
- [ ] File backup tested
- [ ] Recovery procedure documented
- [ ] Backup storage secured

---

## Environment Setup

### 1. Create Production Server

**Recommended specs:**
- OS: Ubuntu 22.04 LTS (or similar)
- RAM: Minimum 2GB (4GB recommended)
- Storage: 20GB SSD minimum
- CPU: 1v CPU minimum (2v recommended)
- Ports: 80 (HTTP), 443 (HTTPS), 22 (SSH)

**Infrastructure options:**
- Railway (recommended for beginners)
- DigitalOcean
- Linode
- AWS EC2
- Google Cloud
- Azure VM

### 2. Install Dependencies

```bash
# Connect to server
ssh root@your_server_ip

# Update system packages
sudo apt update && sudo apt upgrade -y

# Install PHP 8.1+ with extensions
sudo apt install -y php8.1-fpm php8.1-mysql php8.1-mbstring \
  php8.1-xml php8.1-curl php8.1-json php8.1-tokenizer \
  php8.1-bcmath php8.1-gd

# Install Nginx
sudo apt install -y nginx

# Install MySQL 8.0+
sudo apt install -y mysql-server

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js and npm (for frontend assets)
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs
```

### 3. Configure Web Server

**Create Nginx config** (`/etc/nginx/sites-available/tech-store`):

```nginx
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;

    # Redirect to HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;

    # SSL certificate paths (get from Let's Encrypt)
    ssl_certificate /etc/letsencrypt/live/yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/yourdomain.com/privkey.pem;

    root /var/www/html/tech_store_app/public;
    index index.php index.html;

    # Logging
    access_log /var/log/nginx/tech-store-access.log;
    error_log /var/log/nginx/tech-store-error.log;

    # Security headers
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;

    # File uploads limit
    client_max_body_size 10M;

    # Compression
    gzip on;
    gzip_types text/plain text/css text/xml application/json application/javascript;
    gzip_vary on;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Deny access to sensitive files
    location ~ /\.env {
        deny all;
    }
    location ~ /\.git {
        deny all;
    }
}
```

**Enable site:**
```bash
sudo ln -s /etc/nginx/sites-available/tech-store /etc/nginx/sites-enabled/
sudo rm /etc/nginx/sites-enabled/default
sudo nginx -t  # Test config
sudo systemctl restart nginx
```

### 4. Set Up SSL Certificate

Using Let's Encrypt (free):

```bash
# Install Certbot
sudo apt install -y certbot python3-certbot-nginx

# Get certificate
sudo certbot certonly --nginx -d yourdomain.com -d www.yourdomain.com

# Auto-renewal
sudo systemctl enable certbot.timer
sudo systemctl start certbot.timer
```

### 5. Configure MySQL

```bash
# Secure installation
sudo mysql_secure_installation
# (Remove anonymous user, disable remote root, etc.)

# Create database and user
sudo mysql -u root -p
```

```sql
CREATE DATABASE tech_store;
CREATE USER 'tech_store_user'@'localhost' IDENTIFIED BY 'strong_password_here';
GRANT ALL PRIVILEGES ON tech_store.* TO 'tech_store_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 6. Create Application Directory

```bash
# Create directory
sudo mkdir -p /var/www/html
cd /var/www/html

# Set permissions
sudo chown -R www-data:www-data /var/www/html
```

---

## Deployment Steps

### 1. Clone Repository

```bash
cd /var/www/html
sudo git clone https://github.com/yourusername/tech_store_app.git
cd tech_store_app
```

### 2. Install Dependencies

```bash
# Backend dependencies
composer install --no-dev --optimize-autoloader

# Frontend dependencies
npm install --production
npm run build
```

### 3. Configure Environment

```bash
# Copy and edit environment file
cp .env.example .env
nano .env
```

**Key environment variables:**

```env
# Application
APP_NAME="Tech Store"
APP_ENV=production
APP_KEY=base64:xxxxxxxxxxxxx
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=tech_store
DB_USERNAME=tech_store_user
DB_PASSWORD=strong_password_here

# Mail (using service like Mailtrap or SendGrid)
MAIL_DRIVER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=app_specific_password
MAIL_FROM_NAME="Tech Store"

# Storage
FILESYSTEM_DISK=public

# Cache
CACHE_DRIVER=file

# Session
SESSION_DRIVER=cookie

# Queue (optional)
QUEUE_CONNECTION=database

# Stripe (if using payment)
STRIPE_PUBLIC_KEY=pk_live_xxxxx
STRIPE_SECRET_KEY=sk_live_xxxxx
```

**Generate application key:**
```bash
php artisan key:generate
```

### 4. Set File Permissions

```bash
# Make directories writable
sudo chmod -R 775 storage bootstrap/cache public/upload
sudo chown -R www-data:www-data storage bootstrap/cache public/upload
```

### 5. Run Database Migrations

```bash
# Run migrations with fresh database
php artisan migrate --force

# Or if database exists with old schema:
# php artisan migrate:refresh --force

# Seed database (optional)
php artisan db:seed
```

### 6. Clear und Cache

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### 7. Set Up Cron Job for Queue

```bash
# Edit crontab
sudo crontab -e

# Add this line (for Laravel queue worker):
* * * * * cd /var/www/html/tech_store_app && php artisan schedule:run >> /dev/null 2>&1
```

### 8. Set Up Supervisor (for long-running queue workers)

```bash
# Install Supervisor
sudo apt install -y supervisor

# Create config
sudo nano /etc/supervisor/conf.d/tech-store.conf
```

```ini
[program:tech-store-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/html/tech_store_app/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
numprocs=2
redirect_stderr=true
stdout_logfile=/var/log/tech-store-worker.log
stopasgroup=true
```

```bash
# Start Supervisor
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start tech-store-worker:*
```

### 9. Configure Log Rotation

```bash
sudo nano /etc/logrotate.d/tech-store
```

```
/var/www/html/tech_store_app/storage/logs/*.log {
    daily
    missingok
    rotate 7
    compress
    delaycompress
    notifempty
    create 0640 www-data www-data
    sharedscripts
    postrotate
        systemctl reload nginx > /dev/null 2>&1 || true
    endscript
}
```

---

## Post-Deployment Verification

### 1. Application Health Check

```bash
# Check if application loads
curl -I https://yourdomain.com
# Should return 200 OK

# Check if login works
curl https://yourdomain.com/login
```

### 2. Database Verification

```bash
# Connect to application
php artisan tinker

# Check user count
User::count()

# Check products
Product::count()

# Exit
exit
```

### 3. File Permissions Verification

```bash
# Check storage directory
ls -la /var/www/html/tech_store_app/storage/

# Check upload directory
ls -la /var/www/html/tech_store_app/public/upload/
```

### 4. Test Key Features

- [ ] Homepage loads
- [ ] Product catalog displays
- [ ] Search functionality works
- [ ] User registration works
- [ ] User login works
- [ ] Add to cart works
- [ ] Checkout process works
- [ ] Email notifications send
- [ ] Admin dashboard accessible
- [ ] File uploads work (product images, avatars)
- [ ] Review system works
- [ ] Favorites system works

### 5. Security Verification

```bash
# Check HTTPS works
curl -I https://yourdomain.com

# Check HSTS header
curl -I https://yourdomain.com | grep Strict-Transport

# Check security headers
curl -I https://yourdomain.com | grep -E "X-Content-Type|X-Frame-Options|X-XSS"

# Check .env is not accessible
curl https://yourdomain.com/.env
# Should return 403 Forbidden
```

### 6. Performance Check

```bash
# Check page load time
time curl https://yourdomain.com > /dev/null

# Monitor server resources
top
free -h
df -h

# Check Nginx is running
sudo systemctl status nginx

# Check PHP-FPM is running
sudo systemctl status php8.1-fpm

# Check MySQL is running
sudo systemctl status mysql
```

---

## Monitoring & Maintenance

### 1. Error Logging

**View application errors:**
```bash
tail -f /var/www/html/tech_store_app/storage/logs/laravel.log

# Or use systemd journal
journalctl -u php8.1-fpm -f
```

**View Nginx errors:**
```bash
tail -f /var/log/nginx/tech-store-error.log
```

### 2. Server Monitoring

**Recommended tools:**
- **Uptime monitoring**: UptimeRobot (free)
- **Performance monitoring**: New Relic
- **Error tracking**: Sentry
- **Log management**: ELK Stack or Papertrail
- **Server monitoring**: Netdata

**Install Netdata (free server monitoring):**
```bash
curl https://get.netdata.cloud/kickstart.sh > /tmp/netdata-kickstart.sh
sh /tmp/netdata-kickstart.sh --stable-channel --disable-telemetry
# Access at http://localhost:19999
```

### 3. Database Backups

**Manual backup:**
```bash
mysqldump -u tech_store_user -p tech_store > backup_$(date +%Y%m%d).sql
```

**Automated backup script** (`/home/backup.sh`):
```bash
#!/bin/bash
BACKUP_DIR="/backups/mysql"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
FILENAME="tech_store_$TIMESTAMP.sql"

# Create backup
mysqldump -u tech_store_user -p${DB_PASSWORD} tech_store > $BACKUP_DIR/$FILENAME

# Keep only last 7 days
find $BACKUP_DIR -type f -name "*.sql" -mtime +7 -delete

# Upload to cloud storage (optional)
# aws s3 cp $BACKUP_DIR/$FILENAME s3://your-backup-bucket/
```

**Schedule with cron:**
```bash
# Backup daily at 2 AM
0 2 * * * /home/backup.sh
```

### 4. Regular Maintenance

**Weekly:**
- [ ] Check error logs
- [ ] Verify backups completed
- [ ] Monitor disk space
- [ ] Check failed logins

**Monthly:**
- [ ] Review server resources
- [ ] Update OS packages: `sudo apt update && sudo apt upgrade`
- [ ] Check database size
- [ ] Verify SSL certificate expiration

**Quarterly:**
- [ ] Security audit
- [ ] Performance review
- [ ] Database optimization
- [ ] Update dependencies

### 5. Health Monitoring Command

```bash
# Quick server health check
sudo systemctl status nginx mysql php8.1-fpm supervisor
ps aux | grep -E "nginx|mysql|php|supervisor"
free -h && df -h
curl -I https://yourdomain.com
tail -20 /var/www/html/tech_store_app/storage/logs/laravel.log
```

---

## Troubleshooting

### 500 Internal Server Error

```bash
# Check logs
tail -50 /var/www/html/tech_store_app/storage/logs/laravel.log
tail -50 /var/log/nginx/tech-store-error.log

# Check permissions
sudo chown -R www-data:www-data /var/www/html

# Clear cache
php artisan cache:clear
php artisan config:clear
```

### 502 Bad Gateway

```bash
# PHP-FPM not running
sudo systemctl start php8.1-fpm
sudo systemctl status php8.1-fpm

# Check PHP-FPM socket
ls -la /var/run/php/php8.1-fpm.sock

# Restart Nginx
sudo systemctl restart nginx
```

### Database Connection Error

```bash
# Check MySQL is running
sudo systemctl status mysql

# Test connection
mysql -u tech_store_user -p -h localhost tech_store -e "SELECT 1;"

# Check credentials in .env
nano /var/www/html/tech_store_app/.env
```

### High Memory Usage

```bash
# Identify memory-hungry processes
top -o %MEM

# Restart PHP-FPM
sudo systemctl restart php8.1-fpm

# Increase server RAM or optimize queries
```

### Email Not Sending

```bash
# Check mail configuration
php artisan tinker
> config('mail')

# Test email sending
Mail::raw('test', function($m) { 
    $m->to('your@email.com')->subject('Test'); 
});

# Check mail logs
tail -50 /var/www/html/tech_store_app/storage/logs/laravel.log
```

### Slow Performance

```bash
# Profile database queries
php artisan db:seed --class=QueryDebugSeeder

# Check indexes
EXPLAIN SELECT * FROM products WHERE category_id = 1;

# Optimize database
mysqlcheck -u tech_store_user -p --optimize --all-databases

# Clear cache
php artisan cache:clear
php artisan optimize:clear
```

---

## Rollback Procedure

If deployment fails, follow these steps:

### 1. Stop the Application

```bash
sudo systemctl stop nginx
sudo systemctl stop php8.1-fpm
```

### 2. Restore Previous Code

```bash
cd /var/www/html/tech_store_app

# If using Git
git log --oneline | head -5
git revert HEAD~1..HEAD
# Or restore from backup
tar -xzf /backups/tech_store_app_$(date -d "1 day ago" +%Y%m%d).tar.gz
```

### 3. Restore Previous Database

```bash
# Drop current database
mysql -u tech_store_user -p tech_store < /dev/null
mysql -e "DROP DATABASE tech_store;"

# Restore from backup
mysql -u tech_store_user -p < /backups/mysql/tech_store_YYYYMMDD.sql
```

### 4. Clear Cache

```bash
cd /var/www/html/tech_store_app
php artisan cache:clear
```

### 5. Restart Services

```bash
sudo systemctl start php8.1-fpm
sudo systemctl start nginx
sudo systemctl restart supervisor
```

### 6. Verify Rollback

```bash
# Test application
curl -I https://yourdomain.com

# Check logs
tail -50 /var/www/html/tech_store_app/storage/logs/laravel.log
```

---

## Deployment Checklist

Before going live, verify:

- [ ] Database migrations run successfully
- [ ] .env file is configured correctly
- [ ] Storage directory is writable
- [ ] HTTPS certificate installed and valid
- [ ] Email service is configured
- [ ] File uploads working
- [ ] Admin account created with strong password
- [ ] All key features tested
- [ ] Error logging enabled
- [ ] Backup system configured
- [ ] Monitoring enabled
- [ ] Security headers configured
- [ ] Rate limiting enabled (optional)
- [ ] Database backed up
- [ ] Rollback procedure documented
- [ ] Team trained on deployment

---

## Support

For issues:
1. Check logs: `tail /var/www/html/tech_store_app/storage/logs/laravel.log`
2. Check system status: `sudo systemctl status {service}`
3. Review Nginx config: `sudo nginx -t`
4. Consult TROUBLESHOOTING.md
5. Check GitHub issues: If using GitHub repository

---

Last Updated: February 16, 2026
