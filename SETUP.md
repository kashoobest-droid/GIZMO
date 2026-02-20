# Installation & Setup Guide

Complete step-by-step guide to install and configure the KS Tech Store e-commerce platform.

## Prerequisites

Before installation, ensure you have:

- **PHP 8.2+** - Check with `php -v`
- **MySQL 8.0+** - Check with `mysql --version`
- **Composer** - Check with `composer --version`
- **Git** - Check with `git --version`
- **Node.js & npm** (optional) - For asset compilation

### System Requirements
- Linux/Windows/macOS
- 2GB+ RAM recommended
- 500MB+ disk space
- Internet connection

## Step 1: Clone the Repository

```bash
# Clone the project
git clone <repository-url> tech_store_app
cd tech_store_app

# Verify Laravel installation
php artisan --version
```

## Step 2: Install PHP Dependencies

```bash
# Install Composer dependencies
composer install

# This installs:
# - Laravel framework & packages
# - Database ORM (Eloquent)
# - Mail system
# - Authentication
# - Queue workers (optional)
```

### If Composer fails:
```bash
# Clear cache and retry
composer clear-cache
composer install
```

## Step 3: Environment Configuration

```bash
# Copy example environment file
cp .env.example .env

# Generate application key (encryption)
php artisan key:generate

# Verify the key was generated
grep APP_KEY .env
```

### Edit `.env` file with your settings:

```dotenv
# Application
APP_NAME="KS Tech Store"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database Connection
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tech_store_app
DB_USERNAME=root
DB_PASSWORD=your_password

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=noreply@store.local
MAIL_FROM_NAME="${APP_NAME}"

# Language Settings
APP_LOCALE=en
APP_FALLBACK_LOCALE=en
```

## Step 4: Database Setup

### Create database and user:

```bash
# Login to MySQL
mysql -u root -p

# Create database and user
CREATE DATABASE tech_store_app;
CREATE USER 'tech_store'@'localhost' IDENTIFIED BY 'SecurePassword123';
GRANT ALL PRIVILEGES ON tech_store_app.* TO 'tech_store'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### Update `.env` with credentials:
```dotenv
DB_DATABASE=tech_store_app
DB_USERNAME=tech_store
DB_PASSWORD=SecurePassword123
```

### Run migrations:

```bash
# Create database tables
php artisan migrate

# Seed demo data (optional)
php artisan db:seed

# Check migration status
php artisan migrate:status
```

### Database Tables Created:
- `users` - Customer accounts
- `products` - Product catalog
- `categories` - Product categories
- `cart_items` - Shopping cart
- `orders` - Customer orders
- `order_items` - Items in orders
- `reviews` - Product reviews
- `favorites` - Wishlist items
- `coupons` - Discount codes
- `offers` - Promotional offers
- `stock_notifications` - Out-of-stock alerts
- And more...

## Step 5: File & Directory Permissions

```bash
# Set proper permissions
chmod -R 755 storage bootstrap/cache

# If you use a web server (not Laravel's built-in):
sudo chown -R www-data:www-data storage bootstrap/cache
```

## Step 6: Storage Setup

```bash
# Create symbolic link from public to storage
php artisan storage:link

# Verify the link was created
ls -la public/storage

# Creates: public/storage -> ../../storage/app/public
```

This allows image uploads to be publicly accessible.

## Step 7: Install Frontend Dependencies (Optional)

```bash
# Install npm packages
npm install

# Build assets for development
npm run dev

# Build minified assets for production
npm run build

# Watch for changes during development
npm run watch
```

## Step 8: Start Development Server

```bash
# Method 1: Laravel built-in server (recommended for development)
php artisan serve

# Runs at http://localhost:8000

# Method 2: Manually specify port
php artisan serve --port=8080

# Method 3: With npm watch in separate terminal
# Terminal 1:
php artisan serve

# Terminal 2:
npm run watch
```

## Step 9: Verify Installation

Visit these URLs to verify:

1. **Homepage** - http://localhost:8000
   - Should show product listing page
   - Dark mode toggle visible in navbar

2. **Login Page** - http://localhost:8000/login
   - Login form displays correctly
   - Language toggle works

3. **Admin Dashboard** - http://localhost:8000/admin
   - Login with: `admin@example.com` / `Password123`
   - Dashboard shows statistics
   - Navigation menu visible

4. **Product Details** - http://localhost:8000/product/1
   - Product images display
   - Dark mode CSS applied
   - Mobile menu slides correctly

## Configuration Details

### Language Configuration

Edit `config/app.php`:

```php
'locale' => env('APP_LOCALE', 'en'),
'fallback_locale' => 'en',
'supported_locales' => ['en', 'ar'],
```

### Mail Configuration

For development, use **Mailtrap**:

1. Sign up at https://mailtrap.io
2. Copy SMTP credentials to `.env`
3. Test email sending:

```bash
php artisan tinker
Mail::raw('Test email', fn($m) => $m->to('test@example.com'));
exit
```

### Cache & Session

### Sending email via Brevo (Sendinblue)

To send verification emails using Brevo's SMTP relay, set these values in your `.env`:

```dotenv
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=your_brevo_smtp_username
MAIL_PASSWORD=your_brevo_smtp_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="Gizmo Store"
```

After updating `.env` run:

```bash
php artisan config:clear
php artisan cache:clear
```

Brevo also provides an HTTP API if you prefer to send emails via their API key instead of SMTP. The application uses Laravel's `Mail` API for verification emails, so SMTP configuration is sufficient.

Default configuration uses:
- **Session Store:** `file` (or `database`)
- **Cache Driver:** `file` (or `redis` for production)

To use database sessions:

```bash
# Create sessions table
php artisan session:table
php artisan migrate
```

Then edit `.env`:
```dotenv
SESSION_DRIVER=database
```

## Production Deployment

For production, see [DEPLOYMENT.md](./DEPLOYMENT.md)

### Quick Production Checklist

```bash
# Set to production
APP_ENV=production
APP_DEBUG=false

# Optimize autoloader
composer install --optimize-autoloader --no-dev

# Clear and cache configs
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations (production database)
php artisan migrate --force

# Seed initial data
php artisan db:seed --force
```

## Troubleshooting

### Port Already in Use
```bash
# Use different port
php artisan serve --port=8001

# Or kill process using port 8000
sudo lsof -ti:8000 | xargs kill -9
```

### Database Connection Failed
```bash
# Check MySQL is running
sudo systemctl start mysql

# Test connection
php artisan db
```

### Permission Denied Errors
```bash
# Check permissions
ls -la storage/
ls -la bootstrap/cache/

# Fix permissions
sudo chown -R $USER:$USER storage bootstrap
chmod -R 755 storage bootstrap/cache
```

### Composer Issues
```bash
# Clear cache
composer clear-cache

# Update packages
composer update

# Check for issues
composer diagnose
```

### Migration Errors
```bash
# Rollback and retry
php artisan migrate:rollback
php artisan migrate

# Reset completely (⚠️ deletes data!)
php artisan migrate:fresh --seed
```

## Next Steps

1. **Read Documentation**
   - [ARCHITECTURE.md](./ARCHITECTURE.md) - Project structure
   - [API_DOCUMENTATION.md](./API_DOCUMENTATION.md) - API endpoints
   - [SECURITY.md](./SECURITY.md) - Security practices

2. **Create Admin Account**
   - Change default credentials immediately
   - Create additional admin users as needed

3. **Configure Settings**
   - Upload store logo/images
   - Set up email notifications
   - Configure payment gateway (if needed)

4. **Test Features**
   - Add sample products
   - Test checkout flow
   - Verify email sending
   - Test dark mode and languages

## Support

- Check [TROUBLESHOOTING.md](./TROUBLESHOOTING.md) for error solutions
- Review [API_DOCUMENTATION.md](./API_DOCUMENTATION.md) for endpoint details
- Run `php artisan help` for artisan commands help

---

**Last Updated:** February 16, 2026
