# คู่มือการติดตั้งบน Server

## ขั้นตอนที่ 1: เตรียม Server

### ความต้องการของระบบ
- PHP 8.2 หรือสูงกว่า
- Composer
- SQLite (หรือ MySQL/PostgreSQL)
- Web Server (Apache/Nginx)
- Git (ถ้าใช้ Git deployment)
- Node.js & NPM (สำหรับ build assets)

### ติดตั้ง PHP Extensions
```bash
# Ubuntu/Debian
sudo apt update
sudo apt install -y php8.2 php8.2-fpm php8.2-cli php8.2-common php8.2-mysql \
  php8.2-zip php8.2-gd php8.2-mbstring php8.2-curl php8.2-xml php8.2-bcmath \
  php8.2-sqlite3 php8.2-intl

# CentOS/RHEL
sudo yum install php82 php82-fpm php82-cli php82-common php82-mysqlnd \
  php82-zip php82-gd php82-mbstring php82-curl php82-xml php82-bcmath \
  php82-sqlite3 php82-intl
```

---

## ขั้นตอนที่ 2: อัพโหลดไฟล์

### วิธีที่ 1: ใช้ Git (แนะนำ)
```bash
# บน server
cd /var/www
git clone https://github.com/yourusername/idhub.git
cd idhub
```

### วิธีที่ 2: อัพโหลดไฟล์โดยตรง
```bash
# บนเครื่อง local - สร้างไฟล์ zip (ไม่รวม node_modules, vendor)
# แล้ว upload ขึ้น server

# บน server
cd /var/www
unzip idhub.zip
cd idhub
```

---

## ขั้นตอนที่ 3: ติดตั้ง Dependencies

```bash
# ติดตั้ง PHP dependencies
composer install --optimize-autoloader --no-dev

# ติดตั้ง Node dependencies และ build assets
npm install
npm run build
```

---

## ขั้นตอนที่ 4: ตั้งค่า Environment

```bash
# คัดลอกไฟล์ .env
cp .env.example .env

# แก้ไขไฟล์ .env
nano .env
```

### ตัวอย่างการตั้งค่า .env สำหรับ Production:
```env
APP_NAME="NextAuth SSO"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database (SQLite)
DB_CONNECTION=sqlite
# หรือใช้ MySQL
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=idhub
# DB_USERNAME=idhub_user
# DB_PASSWORD=secure_password

# Session & Cache
SESSION_DRIVER=database
CACHE_STORE=database

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

### สร้าง Application Key:
```bash
php artisan key:generate
```

---

## ขั้นตอนที่ 5: ตั้งค่า Database

### สำหรับ SQLite:
```bash
# สร้างไฟล์ database
touch database/database.sqlite

# Run migrations
php artisan migrate --force
```

### สำหรับ MySQL:
```bash
# สร้าง database บน MySQL
mysql -u root -p
CREATE DATABASE idhub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'idhub_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON idhub.* TO 'idhub_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Run migrations
php artisan migrate --force
```

---

## ขั้นตอนที่ 6: ตั้งค่า Permissions

```bash
# ตั้งค่า ownership
sudo chown -R www-data:www-data /var/www/idhub

# ตั้งค่า permissions
sudo find /var/www/idhub -type f -exec chmod 644 {} \;
sudo find /var/www/idhub -type d -exec chmod 755 {} \;

# ตั้งค่า writable directories
sudo chmod -R 775 /var/www/idhub/storage
sudo chmod -R 775 /var/www/idhub/bootstrap/cache
```

---

## ขั้นตอนที่ 7: ตั้งค่า Web Server

### Nginx Configuration:
```bash
sudo nano /etc/nginx/sites-available/idhub
```

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/idhub/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

```bash
# เปิดใช้งาน site
sudo ln -s /etc/nginx/sites-available/idhub /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

### Apache Configuration (.htaccess มีอยู่แล้วใน public/):
```bash
sudo nano /etc/apache2/sites-available/idhub.conf
```

```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    DocumentRoot /var/www/idhub/public

    <Directory /var/www/idhub/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/idhub_error.log
    CustomLog ${APACHE_LOG_DIR}/idhub_access.log combined
</VirtualHost>
```

```bash
# เปิดใช้งาน site และ rewrite module
sudo a2ensite idhub
sudo a2enmod rewrite
sudo systemctl restart apache2
```

---

## ขั้นตอนที่ 8: ติดตั้ง SSL (Let's Encrypt)

```bash
# ติดตั้ง Certbot
sudo apt install certbot python3-certbot-nginx  # สำหรับ Nginx
# หรือ
sudo apt install certbot python3-certbot-apache  # สำหรับ Apache

# ขอ SSL Certificate
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
# หรือ
sudo certbot --apache -d yourdomain.com -d www.yourdomain.com

# ทดสอบ auto-renewal
sudo certbot renew --dry-run
```

---

## ขั้นตอนที่ 9: Cache & Optimize

```bash
# Clear และสร้าง cache ใหม่
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

---

## ขั้นตอนที่ 10: ตั้งค่า Cron Jobs (ถ้ามี)

```bash
sudo crontab -e -u www-data
```

เพิ่มบรรทัดนี้:
```cron
* * * * * cd /var/www/idhub && php artisan schedule:run >> /dev/null 2>&1
```

---

## ขั้นตอนที่ 11: ตั้งค่า Supervisor (สำหรับ Queue Workers)

```bash
sudo apt install supervisor

sudo nano /etc/supervisor/conf.d/idhub-worker.conf
```

```ini
[program:idhub-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/idhub/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/idhub/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start idhub-worker:*
```

---

## ขั้นตอนที่ 12: สร้าง Super Admin

เข้าไปที่ `https://yourdomain.com/setup/admin` และสร้าง Super Admin account

---

## การอัพเดทระบบ

```bash
# Pull code ใหม่
git pull origin main

# อัพเดท dependencies
composer install --optimize-autoloader --no-dev
npm install && npm run build

# Run migrations
php artisan migrate --force

# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Recreate cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart services
sudo supervisorctl restart idhub-worker:*
sudo systemctl restart php8.2-fpm  # หรือ apache2
```

---

## Checklist การติดตั้ง

- [ ] ติดตั้ง PHP และ extensions ครบถ้วน
- [ ] ติดตั้ง Composer
- [ ] Clone/Upload โปรเจกต์
- [ ] Run `composer install`
- [ ] Run `npm install && npm run build`
- [ ] Copy และแก้ไขไฟล์ `.env`
- [ ] Run `php artisan key:generate`
- [ ] สร้าง database และ run migrations
- [ ] ตั้งค่า permissions ของ storage และ bootstrap/cache
- [ ] ตั้งค่า web server (Nginx/Apache)
- [ ] ติดตั้ง SSL certificate
- [ ] Cache config และ routes
- [ ] ตั้งค่า cron jobs (ถ้ามี)
- [ ] ตั้งค่า supervisor สำหรับ queue workers (ถ้ามี)
- [ ] สร้าง Super Admin account
- [ ] ทดสอบการทำงาน

---

## Troubleshooting

### ปัญหา Permission Denied
```bash
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
```

### ปัญหา 500 Internal Server Error
```bash
# ดู error logs
tail -f storage/logs/laravel.log
tail -f /var/log/nginx/error.log  # หรือ apache2
```

### ปัญหา Database Connection
- ตรวจสอบ credentials ใน .env
- ตรวจสอบว่า database server รันอยู่
- ตรวจสอบ firewall settings

### Clear All Cache
```bash
php artisan optimize:clear
```

---

## Security Best Practices

1. **อัพเดท Dependencies เป็นประจำ**
   ```bash
   composer update
   npm update
   ```

2. **ตั้งค่า Firewall**
   ```bash
   sudo ufw allow 22/tcp    # SSH
   sudo ufw allow 80/tcp    # HTTP
   sudo ufw allow 443/tcp   # HTTPS
   sudo ufw enable
   ```

3. **Backup Database เป็นประจำ**
   ```bash
   # SQLite
   cp database/database.sqlite database/backups/database-$(date +%Y%m%d).sqlite
   
   # MySQL
   mysqldump -u idhub_user -p idhub > backup-$(date +%Y%m%d).sql
   ```

4. **Monitor Logs**
   ```bash
   tail -f storage/logs/laravel.log
   ```

5. **ตั้งค่า Rate Limiting** (มีอยู่แล้วใน Laravel)

6. **ใช้ HTTPS เท่านั้น** - บังคับ redirect HTTP → HTTPS
