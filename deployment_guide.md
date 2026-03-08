# ProcureFlow Server Deployment Guide

This guide provides step-by-step instructions for deploying the ProcureFlow Laravel application to a Linux server (Ubuntu/Debian recommended).

## 1. Server Requirements
Before starting, ensure your server has the following installed:
- **PHP 8.2+** with extensions: `ctype`, `curl`, `dom`, `fileinfo`, `filter`, `hash`, `mbstring`, `openssl`, `pcre`, `pdo`, `session`, `tokenizer`, `xml`, `sqlite3`
- **Nginx** or **Apache**
- **Composer** (PHP Package Manager)
- **Node.js & NPM** (for compiling assets)
- **Git**

## 2. Server Preparation

### Install Dependencies (Ubuntu Example)
```bash
sudo apt update
sudo apt install -y php8.2-fpm php8.2-mysql php8.2-sqlite3 php8.2-curl php8.2-xml php8.2-mbstring php8.2-zip unzip git nginx nodejs npm
```

## 3. Deployment Steps

### Step 1: Clone the Repository
Navigate to your web directory and clone the code.
```bash
cd /var/www
git clone https://github.com/zaidinabeel/po_workflow.git
cd po_workflow
```

### Step 2: Install Composer Dependencies
```bash
composer install --optimize-autoloader --no-dev
```

### Step 3: Configure Environment
Copy the example environment file and generate an application key.
```bash
cp .env.example .env
php artisan key:generate --ansi
```
> [!IMPORTANT]
> Edit `.env` and set `APP_ENV=production`, `APP_DEBUG=false`, and `APP_URL=https://yourdomain.com`.
> Ensure your SMTP credentials (Brevo) are correctly set in the `.env`.

### Step 4: Setup Database (SQLite)
Create the database file and set correct permissions.
```bash
touch database/database.sqlite
chmod -R 775 storage bootstrap/cache database
chown -R www-data:www-data storage bootstrap/cache database
```

### Step 5: Run Migrations
```bash
php artisan migrate --force
```

### Step 6: Compile Assets (Vite)
```bash
npm install
npm run build
```

## 4. Web Server Configuration (Nginx)
Create a new Nginx configuration file: `/etc/nginx/sites-available/procureflow`

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/po_workflow/public;

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
Enable the site and restart Nginx:
```bash
sudo ln -s /etc/nginx/sites-available/procureflow /etc/nginx/sites-enabled/
sudo nginx -t
sudo system_service nginx restart
```

## 5. SSL / HTTPS (Certbot)
It is highly recommended to use HTTPS for the email triggers and secure vendor links to work correctly.
```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d yourdomain.com
```

## 6. Optimization for Production
Once everything is working, run these commands to speed up the app:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 7. Troubleshooting
- **Permission Errors**: Ensure `www-data` (or your web user) has write access to `storage`, `bootstrap/cache`, and `database/database.sqlite`.
- **500 Errors**: Check logs at `storage/logs/laravel.log` for specific errors.
- **SQLite locked**: This happens if multiple processes try to write at once; ensure the `database` folder itself is writable by the web server.
