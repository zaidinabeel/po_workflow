# ProcureFlow Server Deployment Guide

This guide provides step-by-step instructions for deploying the ProcureFlow Laravel application to a Linux server or **Hostinger Shared Hosting**.

## 🚀 Fast Track (Ubuntu/VPS Copy-Paste)
If you are on a fresh Ubuntu server, run these as root/sudo:
```bash
# 1. Install PHP 8.2 & Nginx
sudo apt update && sudo apt install -y php8.2-fpm php8.2-sqlite3 php8.2-curl php8.2-xml php8.2-mbstring php8.2-zip unzip git nginx nodejs npm

# 2. Clone & Install
cd /var/www && git clone https://github.com/zaidinabeel/po_workflow.git
cd po_workflow && composer install --optimize-autoloader --no-dev

# 3. Environment & Database
cp .env.example .env && php artisan key:generate
touch database/database.sqlite
chmod -R 775 storage bootstrap/cache database && chown -R www-data:www-data .

# 4. Migrate & Build
php artisan migrate --force
npm install && npm run build
```

---

## 🏨 Hostinger Shared Hosting Deployment
Deploying to Hostinger (hPanel) is slightly different as you don't have full root access.

### Step 1: Uploading Files
1.  **Option A (Git)**: Use the "Advanced" > "GIT" section in hPanel to clone: `https://github.com/zaidinabeel/po_workflow.git`.
2.  **Option B (Manual)**: Zip your local project (excluding `vendor` and `node_modules`), upload it via "File Manager", and extract it.

### Step 2: Set PHP Version
Go to **"Advanced" > "PHP Configuration"** and ensure **PHP 8.2** or higher is selected.

### Step 3: Configure .env
1.  In File Manager, find `.env.example`, rename it to `.env`.
2.  Edit `.env` and update:
    - `APP_ENV=production`
    - `APP_DEBUG=false`
    - `APP_URL=https://your-domain.com`
    - **SMTP Settings**: Ensure your Brevo credentials from the previous task are entered.

### Step 4: Run Artisan Commands (SSH)
Hostinger provides SSH access. Connect via terminal and run:
```bash
# Navigate to project
cd domains/your-domain.com/public_html/po_workflow

# Install dependencies (if not done)
composer install --no-dev

# Generate Key & Migrate
php artisan key:generate
php artisan migrate --force

# Link storage
php artisan storage:link
```
*Note: If `php` command fails, use the full path provided in hPanel (e.g., `/usr/local/bin/php8.2 artisan ...`).*

### Step 5: Handling the "public" folder
On Hostinger Shared hosting, you often need the contents of the `public` folder to be in `public_html`.
1.  **Recommended**: Keep the project in a subdirectory and use an `.htaccess` file in your `public_html` to point to `po_workflow/public`.
2.  Alternatively, update your Domain's **Base Directory** in Hostinger to point to `/po_workflow/public`.

---

## 🛠 Required Commands Reference

| Action | Command |
| :--- | :--- |
| **Setup Env** | `cp .env.example .env && php artisan key:generate` |
| **Clear Cache** | `php artisan config:clear && php artisan cache:clear` |
| **Production Cache** | `php artisan config:cache && php artisan route:cache && php artisan view:cache` |
| **Run Migrations** | `php artisan migrate --force` |
| **Symlink Storage** | `php artisan storage:link` |
| **Fix Permissions** | `chmod -R 775 storage database && chown -R www-data:www-data .` |

---

## 📧 Email Configuration Troubleshooting
If emails are not sending:
1.  Run `php artisan config:clear`.
2.  Check `storage/logs/laravel.log` for SMTP timeout errors.
3.  Ensure your Brevo account is active and not in "Suspended" or "New Account Review" mode.
