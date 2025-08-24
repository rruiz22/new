# 🚀 Deployment Guide - Documentación Completa

## 📋 **Información General**

Esta guía detalla el proceso completo de deployment del sistema MDA, desde desarrollo hasta producción, incluyendo configuraciones específicas para diferentes entornos y proveedores de hosting.

---

## 🏗️ **Arquitectura de Deployment**

### **Entornos Disponibles**
```yaml
Development:
  - Local development con XAMPP/LAMP
  - Hot reload y debugging habilitado
  - Base de datos local
  - Archivos locales (no S3)

Staging:
  - Ambiente de pruebas
  - Configuración similar a producción
  - Base de datos de testing
  - Integraciones limitadas

Production:
  - Ambiente live
  - Optimizaciones completas
  - SSL/TLS obligatorio
  - Monitoreo completo
```

### **Stack Tecnológico**
```php
// Requisitos del sistema
$requirements = [
    'php' => '8.1+',
    'database' => 'MySQL 5.7+ / MariaDB 10.3+',
    'web_server' => 'Apache 2.4+ / Nginx 1.18+',
    'memory_limit' => '256M minimum, 512M recommended',
    'max_execution_time' => '300 seconds',
    'extensions' => [
        'intl', 'json', 'mbstring', 'mysqlnd', 'xml', 'curl',
        'fileinfo', 'gd', 'zip', 'openssl'
    ]
];
```

---

## 📦 **Preparación para Deployment**

### **1. Configuración de Entorno (.env)**
```env
#--------------------------------------------------------------------
# ENVIRONMENT
#--------------------------------------------------------------------
CI_ENVIRONMENT = production

#--------------------------------------------------------------------
# APP
#--------------------------------------------------------------------
app.baseURL = 'https://yourdomain.com/'
app.forceGlobalSecureRequests = true
app.sessionDriver = 'CodeIgniter\Session\Handlers\DatabaseHandler'
app.sessionCookieName = 'mda_session'
app.sessionSavePath = 'mda_sessions'

#--------------------------------------------------------------------
# DATABASE
#--------------------------------------------------------------------
database.default.hostname = your_db_host
database.default.database = your_db_name
database.default.username = your_db_user
database.default.password = your_secure_password
database.default.DBDriver = MySQLi
database.default.DBPrefix = 
database.default.port = 3306

#--------------------------------------------------------------------
# ENCRYPTION
#--------------------------------------------------------------------
encryption.key = your_32_character_encryption_key_here

#--------------------------------------------------------------------
# EMAIL CONFIGURATION
#--------------------------------------------------------------------
email.fromEmail = noreply@yourdomain.com
email.fromName = 'MDA System'
email.SMTPHost = your_smtp_host
email.SMTPUser = your_smtp_user
email.SMTPPass = your_smtp_password
email.SMTPPort = 587
email.SMTPCrypto = tls

#--------------------------------------------------------------------
# AWS S3 CONFIGURATION
#--------------------------------------------------------------------
AWS_ACCESS_KEY_ID = your_aws_access_key
AWS_SECRET_ACCESS_KEY = your_aws_secret_key
AWS_DEFAULT_REGION = us-east-1
AWS_BUCKET = your-s3-bucket-name

#--------------------------------------------------------------------
# TWILIO CONFIGURATION
#--------------------------------------------------------------------
TWILIO_ACCOUNT_SID = your_twilio_account_sid
TWILIO_AUTH_TOKEN = your_twilio_auth_token
TWILIO_PHONE_NUMBER = +1234567890

#--------------------------------------------------------------------
# MDA LINKS API
#--------------------------------------------------------------------
LIMA_API_KEY = your_mda_links_api_key
LIMA_BRANDED_DOMAIN = mda.to

#--------------------------------------------------------------------
# PUSHER CONFIGURATION
#--------------------------------------------------------------------
PUSHER_APP_ID = your_pusher_app_id
PUSHER_APP_KEY = your_pusher_key
PUSHER_APP_SECRET = your_pusher_secret
PUSHER_APP_CLUSTER = us2

#--------------------------------------------------------------------
# SECURITY
#--------------------------------------------------------------------
TURNSTILE_SITE_KEY = your_turnstile_site_key
TURNSTILE_SECRET_KEY = your_turnstile_secret_key
```

### **2. Optimización de Composer**
```bash
# Instalar dependencias para producción
composer install --no-dev --optimize-autoloader

# Limpiar cache de Composer
composer clear-cache

# Generar autoloader optimizado
composer dump-autoload --optimize --classmap-authoritative
```

### **3. Optimización de Assets**
```bash
# Instalar dependencias de Node.js
npm ci --production

# Compilar y minificar assets
npm run build

# Generar versiones comprimidas
npm run compress
```

---

## 🌐 **Configuración del Servidor Web**

### **Apache Configuration**
```apache
# .htaccess principal
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Forzar HTTPS en producción
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
    
    # Redireccionar a public/
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>

# Security headers
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>

# Compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>

# Cache control
<IfModule mod_expires.c>
    ExpiresActive on
    ExpiresByType text/css "access plus 1 year"
    ExpiresByType application/javascript "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/svg+xml "access plus 1 year"
</IfModule>
```

### **Nginx Configuration**
```nginx
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/mda/public;
    index index.php;

    # SSL Configuration
    ssl_certificate /path/to/certificate.crt;
    ssl_certificate_key /path/to/private.key;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512;
    ssl_prefer_server_ciphers off;

    # Security Headers
    add_header X-Content-Type-Options nosniff;
    add_header X-Frame-Options DENY;
    add_header X-XSS-Protection "1; mode=block";
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains";

    # Gzip Compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css text/xml text/javascript application/javascript application/xml+rss application/json;

    # Main location
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP handling
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    # Static files caching
    location ~* \.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    # Block access to sensitive files
    location ~ /\. {
        deny all;
    }
}
```

---

## 🗄️ **Configuración de Base de Datos**

### **MySQL/MariaDB Optimization**
```sql
-- Configuración optimizada para producción
[mysqld]
innodb_buffer_pool_size = 1G
innodb_log_file_size = 256M
innodb_flush_log_at_trx_commit = 2
innodb_flush_method = O_DIRECT

max_connections = 200
query_cache_size = 128M
query_cache_type = 1

slow_query_log = 1
slow_query_log_file = /var/log/mysql/slow.log
long_query_time = 2

# Character set
character_set_server = utf8mb4
collation_server = utf8mb4_unicode_ci
```

### **Database Migration Script**
```bash
#!/bin/bash
# deploy-database.sh

echo "Starting database migration..."

# Backup current database
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME > backup_$(date +%Y%m%d_%H%M%S).sql

# Run migrations
php spark migrate

# Run seeders (if needed)
php spark db:seed DatabaseSeeder

# Verify migration
php spark migrate:status

echo "Database migration completed!"
```

---

## 📁 **Estructura de Archivos en Producción**

### **Directory Structure**
```
/var/www/mda/
├── app/                    # Application code
├── public/                 # Web root (DocumentRoot)
│   ├── index.php
│   ├── assets/
│   └── uploads/           # Local uploads (if not using S3)
├── writable/              # Writable directory (755)
│   ├── cache/
│   ├── logs/
│   ├── session/
│   └── uploads/
├── vendor/                # Composer dependencies
├── .env                   # Environment configuration (600)
├── composer.json
├── package.json
└── deployment/            # Deployment scripts
    ├── deploy.sh
    ├── backup.sh
    └── rollback.sh
```

### **File Permissions**
```bash
# Set correct permissions
chown -R www-data:www-data /var/www/mda
chmod -R 755 /var/www/mda
chmod -R 775 /var/www/mda/writable
chmod 600 /var/www/mda/.env
chmod +x /var/www/mda/deployment/*.sh
```

---

## 🔧 **Scripts de Deployment**

### **Main Deployment Script**
```bash
#!/bin/bash
# deploy.sh

set -e

PROJECT_ROOT="/var/www/mda"
BACKUP_DIR="/var/backups/mda"
DATE=$(date +%Y%m%d_%H%M%S)

echo "Starting deployment at $(date)"

# Create backup
echo "Creating backup..."
mkdir -p $BACKUP_DIR
tar -czf $BACKUP_DIR/backup_$DATE.tar.gz -C $PROJECT_ROOT .

# Pull latest code
echo "Pulling latest code..."
cd $PROJECT_ROOT
git pull origin main

# Install/update dependencies
echo "Updating dependencies..."
composer install --no-dev --optimize-autoloader
npm ci --production
npm run build

# Run migrations
echo "Running database migrations..."
php spark migrate

# Clear caches
echo "Clearing caches..."
php spark cache:clear
php spark route:clear

# Optimize application
echo "Optimizing application..."
php spark optimize

# Set permissions
echo "Setting permissions..."
chown -R www-data:www-data $PROJECT_ROOT
chmod -R 755 $PROJECT_ROOT
chmod -R 775 $PROJECT_ROOT/writable
chmod 600 $PROJECT_ROOT/.env

# Restart services
echo "Restarting services..."
systemctl reload nginx
systemctl restart php8.1-fpm

echo "Deployment completed successfully at $(date)"
```

### **Rollback Script**
```bash
#!/bin/bash
# rollback.sh

set -e

PROJECT_ROOT="/var/www/mda"
BACKUP_DIR="/var/backups/mda"

if [ -z "$1" ]; then
    echo "Usage: $0 <backup_timestamp>"
    echo "Available backups:"
    ls -la $BACKUP_DIR/backup_*.tar.gz
    exit 1
fi

BACKUP_FILE="$BACKUP_DIR/backup_$1.tar.gz"

if [ ! -f "$BACKUP_FILE" ]; then
    echo "Backup file not found: $BACKUP_FILE"
    exit 1
fi

echo "Rolling back to backup: $1"

# Stop services
systemctl stop nginx
systemctl stop php8.1-fpm

# Restore backup
cd $PROJECT_ROOT
rm -rf ./*
tar -xzf $BACKUP_FILE

# Set permissions
chown -R www-data:www-data $PROJECT_ROOT
chmod -R 755 $PROJECT_ROOT
chmod -R 775 $PROJECT_ROOT/writable

# Start services
systemctl start php8.1-fpm
systemctl start nginx

echo "Rollback completed successfully"
```

---

## 🎯 **Deployment por Proveedor**

### **SiteGround Deployment**
```bash
# SiteGround specific deployment
#!/bin/bash

# SiteGround paths
SITE_ROOT="/home/username/public_html"
TEMP_DIR="/home/username/tmp"

echo "Deploying to SiteGround..."

# Upload files via Git or FTP
cd $SITE_ROOT
git pull origin main

# Install dependencies (if Composer is available)
if command -v composer &> /dev/null; then
    composer install --no-dev --optimize-autoloader
fi

# Set SiteGround specific permissions
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
chmod 755 writable/
chmod 644 .env

echo "SiteGround deployment completed"
```

### **AWS Deployment (EC2 + RDS)**
```yaml
# docker-compose.yml for AWS deployment
version: '3.8'
services:
  app:
    build: .
    ports:
      - "80:80"
      - "443:443"
    environment:
      - CI_ENVIRONMENT=production
      - DATABASE_HOST=${RDS_ENDPOINT}
      - DATABASE_NAME=${DB_NAME}
      - DATABASE_USER=${DB_USER}
      - DATABASE_PASS=${DB_PASS}
    volumes:
      - ./writable:/var/www/html/writable
    depends_on:
      - redis

  redis:
    image: redis:alpine
    command: redis-server --appendonly yes
    volumes:
      - redis_data:/data

volumes:
  redis_data:
```

### **DigitalOcean Deployment**
```bash
# DigitalOcean Droplet deployment
#!/bin/bash

# Install required packages
apt update && apt upgrade -y
apt install -y nginx php8.1-fpm php8.1-mysql php8.1-xml php8.1-curl php8.1-zip php8.1-gd php8.1-mbstring php8.1-intl

# Configure PHP-FPM
sed -i 's/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/' /etc/php/8.1/fpm/php.ini

# Configure Nginx
cp /path/to/nginx.conf /etc/nginx/sites-available/mda
ln -s /etc/nginx/sites-available/mda /etc/nginx/sites-enabled/
rm /etc/nginx/sites-enabled/default

# Start services
systemctl start nginx php8.1-fpm
systemctl enable nginx php8.1-fpm

echo "DigitalOcean deployment completed"
```

---

## 📊 **Monitoring y Health Checks**

### **Health Check Script**
```bash
#!/bin/bash
# health-check.sh

SITE_URL="https://yourdomain.com"
LOG_FILE="/var/log/mda/health-check.log"

echo "$(date): Starting health check" >> $LOG_FILE

# Check web server response
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" $SITE_URL)
if [ $HTTP_CODE -eq 200 ]; then
    echo "$(date): Web server OK" >> $LOG_FILE
else
    echo "$(date): Web server ERROR - HTTP $HTTP_CODE" >> $LOG_FILE
    # Send alert
fi

# Check database connection
DB_STATUS=$(php -r "
    try {
        \$pdo = new PDO('mysql:host=$DB_HOST;dbname=$DB_NAME', '$DB_USER', '$DB_PASS');
        echo 'OK';
    } catch (Exception \$e) {
        echo 'ERROR';
    }
")

if [ "$DB_STATUS" = "OK" ]; then
    echo "$(date): Database OK" >> $LOG_FILE
else
    echo "$(date): Database ERROR" >> $LOG_FILE
    # Send alert
fi

# Check disk space
DISK_USAGE=$(df -h / | awk 'NR==2 {print $5}' | sed 's/%//')
if [ $DISK_USAGE -gt 90 ]; then
    echo "$(date): Disk space WARNING - ${DISK_USAGE}% used" >> $LOG_FILE
fi

echo "$(date): Health check completed" >> $LOG_FILE
```

### **Log Rotation**
```bash
# /etc/logrotate.d/mda
/var/www/mda/writable/logs/*.log {
    daily
    missingok
    rotate 30
    compress
    delaycompress
    notifempty
    copytruncate
    postrotate
        systemctl reload nginx
    endscript
}
```

---

## 🔒 **Security en Producción**

### **SSL/TLS Configuration**
```bash
# Obtener certificado Let's Encrypt
certbot --nginx -d yourdomain.com -d www.yourdomain.com

# Auto-renewal cron job
echo "0 12 * * * /usr/bin/certbot renew --quiet" | crontab -
```

### **Firewall Configuration**
```bash
# UFW configuration
ufw default deny incoming
ufw default allow outgoing
ufw allow ssh
ufw allow 'Nginx Full'
ufw enable
```

### **Security Headers**
```php
// app/Config/Security.php
public array $headers = [
    'X-Content-Type-Options' => 'nosniff',
    'X-Frame-Options' => 'DENY',
    'X-XSS-Protection' => '1; mode=block',
    'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains',
    'Referrer-Policy' => 'strict-origin-when-cross-origin',
    'Content-Security-Policy' => "default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'"
];
```

---

## 🔄 **CI/CD Pipeline**

### **GitHub Actions Workflow**
```yaml
# .github/workflows/deploy.yml
name: Deploy to Production

on:
  push:
    branches: [ main ]

jobs:
  deploy:
    runs-on: ubuntu-latest
    
    steps:
    - uses: actions/checkout@v2
    
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.1'
        extensions: mbstring, xml, ctype, iconv, intl, pdo_mysql
        
    - name: Install Composer dependencies
      run: composer install --no-dev --optimize-autoloader
      
    - name: Install Node dependencies
      run: npm ci --production
      
    - name: Build assets
      run: npm run build
      
    - name: Deploy to server
      uses: appleboy/ssh-action@v0.1.5
      with:
        host: ${{ secrets.HOST }}
        username: ${{ secrets.USERNAME }}
        key: ${{ secrets.SSH_KEY }}
        script: |
          cd /var/www/mda
          git pull origin main
          ./deployment/deploy.sh
```

---

## 📈 **Performance Optimization**

### **PHP OPcache Configuration**
```ini
; /etc/php/8.1/fpm/conf.d/10-opcache.ini
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=4000
opcache.revalidate_freq=60
opcache.fast_shutdown=1
opcache.enable_cli=1
```

### **Redis Configuration**
```conf
# /etc/redis/redis.conf
maxmemory 256mb
maxmemory-policy allkeys-lru
save 900 1
save 300 10
save 60 10000
```

---

## 🔮 **Advanced Deployment Strategies**

### **Blue-Green Deployment**
```bash
#!/bin/bash
# blue-green-deploy.sh

CURRENT_ENV=$(readlink /var/www/current)
if [[ $CURRENT_ENV == *"blue"* ]]; then
    NEW_ENV="/var/www/mda-green"
    OLD_ENV="/var/www/mda-blue"
else
    NEW_ENV="/var/www/mda-blue"
    OLD_ENV="/var/www/mda-green"
fi

echo "Deploying to $NEW_ENV"

# Deploy to new environment
rsync -av --exclude='.git' /tmp/mda-release/ $NEW_ENV/

# Test new environment
if curl -f http://localhost:8080 > /dev/null 2>&1; then
    # Switch traffic
    ln -sfn $NEW_ENV /var/www/current
    systemctl reload nginx
    echo "Deployment successful"
else
    echo "Deployment failed - rolling back"
    exit 1
fi
```

### **Container Deployment**
```dockerfile
# Dockerfile
FROM php:8.1-fpm-alpine

# Install dependencies
RUN apk add --no-cache \
    nginx \
    mysql-client \
    zip \
    unzip \
    git

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Copy application
COPY . /var/www/html
WORKDIR /var/www/html

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

EXPOSE 80 443

CMD ["php-fpm"]
```

---

**Esta guía de deployment proporciona todos los elementos necesarios para una implementación exitosa y segura del sistema MDA en producción.**

---

*Documentación actualizada: 2025-01-19*  
*Versión de deployment: MDA v2.0*  
*Para más información: [Volver a Documentación Principal](../../claude.md)*


