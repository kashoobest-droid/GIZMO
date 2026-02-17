# Troubleshooting Guide

Common issues, their causes, and solutions.

## Table of Contents

1. [Installation Issues](#installation-issues)
2. [Database Issues](#database-issues)
3. [Authentication Issues](#authentication-issues)
4. [Performance Issues](#performance-issues)
5. [File Upload Issues](#file-upload-issues)
6. [Email Issues](#email-issues)
7. [Payment Issues](#payment-issues)
8. [Security Issues](#security-issues)
9. [Deployment Issues](#deployment-issues)
10. [Development Issues](#development-issues)

---

## Installation Issues

### Composer: No compatible PHP version found

**Error:**
```
Your requirements could not be resolved to an installable set of packages.

Problem 1
  - laravel/framework[v11.0.0, ...] require php ^8.2 but your php version (8.1.0) does not satisfy that requirement.
```

**Solution:**
```bash
# Check PHP version
php -v

# Update PHP to 8.2 or higher
# Windows: Update via XAMPP/WAMP
# macOS: brew upgrade php@8.2
# Linux: sudo apt install php8.2

# Or use Docker
docker run -it php:8.2-fpm
```

---

### Permission denied: storage/logs

**Error:**
```
file_put_contents(/storage/logs/laravel.log): Failed to open stream: Permission denied
```

**Solution:**
```bash
# Fix permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Or in Docker
docker exec app chmod -R 775 storage bootstrap/cache
```

---

### Package not found: Laravel

**Error:**
```
Could not find package laravel/framework in any version, there may be a typo...
```

**Solution:**
```bash
# Clear Composer cache
composer clearcache

# Update Composer
composer self-update

# Reinstall dependencies
rm composer.lock
composer install
```

---

## Database Issues

### SQLSTATE[HY000]: General error - Error on line 1

**Error:**
```
SQLSTATE[HY000] [General error] General error
```

**Causes:**
- Database doesn't exist
- User doesn't have permissions
- MySQL service not running
- Wrong database name in `.env`

**Solution:**
```bash
# Check MySQL is running
sudo systemctl status mysql

# Start MySQL if stopped
sudo systemctl start mysql

# Test connection
mysql -u root -p
> SHOW DATABASES;

# Create database
> CREATE DATABASE tech_store;

# Create user
> CREATE USER 'tech_user'@'localhost' IDENTIFIED BY 'password';
> GRANT ALL ON tech_store.* TO 'tech_user'@'localhost';
> FLUSH PRIVILEGES;

# Or in development
# Run migrations
php artisan migrate
```

---

### SQLSTATE[42000]: Syntax error - Unknown column

**Error:**
```
SQLSTATE[42000]: Syntax error or access violation: 1054 Unknown column 'products.Category_id'
```

**Causes:**
- Migration not run
- Column name mismatch (Case sensitivity)
- Wrong table name

**Solution:**
```bash
# Run fresh migrations
php artisan migrate:fresh

# Check database structure
php artisan tinker
> \DB::select("DESCRIBE products");

# Fix column names in model if needed
# Example: Category_id (with capital C)
```

---

### Cannot find driver

**Error:**
```
could not find driver (SQL: select * from `products`)
in /app/vendor/doctrine/dbal/lib/Doctrine/DBAL/Driver/PDOConnection.php:82
```

**Causes:**
- PDO MySQL extension not installed
- Wrong database driver in `.env`

**Solution:**
```bash
# Install PHP MySQL extension
# Windows: Enable in php.ini - uncomment extension=pdo_mysql
# macOS: brew install php@8.2
# Linux: sudo apt install php8.2-mysql

# Restart PHP
sudo systemctl restart php8.2-fpm

# Verify extension installed
php -m | grep pdo
```

---

### Access denied for user 'root'@'localhost'

**Error:**
```
Access denied for user 'root'@'localhost' (using password: YES)
```

**Causes:**
- Wrong password in `.env`
- MySQL not accepting remote connections
- User doesn't exist

**Solution:**
```bash
# Test credentials
mysql -u root -p'your_password' -h localhost

# Reset MySQL root password (if forgotten)
# Ubuntu/Debian:
sudo systemctl stop mysql
sudo mysqld_safe --skip-grant-tables &
mysql -u root
> FLUSH PRIVILEGES;
> ALTER USER 'root'@'localhost' IDENTIFIED BY 'new_password';
> EXIT;
sudo systemctl restart mysql

# Update .env with correct credentials
nano .env
# DB_PASSWORD=new_password
```

---

## Authentication Issues

### "These credentials do not match our records"

**Error:**
After entering credentials, sees error message.

**Causes:**
- User doesn't exist
- Password is wrong
- User is soft-deleted

**Solution:**
```bash
# Check if user exists
php artisan tinker
> User::where('email', 'test@example.com')->first()

# Create user if missing
> User::create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => Hash::make('Password123'),
  ])

# Reset password
> $user = User::where('email', 'test@example.com')->first();
> $user->update(['password' => Hash::make('NewPassword123')]);
```

---

### CSRF token mismatch

**Error:**
```
419 | Page Expired
CSRF token mismatch
```

**Causes:**
- Session expired
- Form submit from old page
- Cookie settings wrong

**Solution:**
```bash
# Clear browser cookies for localhost
# Or refresh page and resubmit

# Check CSRF middleware is active
# In app/Http/Middleware/VerifyCsrfToken.php

# Ensure session driver is configured
# .env: SESSION_DRIVER=cookie

# Generate new app key if needed
php artisan key:generate
```

---

### "Unauthenticated" error on protected routes

**Error:**
```
User cannot access /dashboard (requires authentication)
Redirects to login page
```

**Solution:**
```bash
# Verify user is logged in
# In browser developer console
localStorage.getItem('auth_token')

# Check middleware is applied
# In routes/web.php
Route::get('/dashboard', function() {...})->middleware('auth');

# Clear session and login again
php artisan tinker
> Session::flush()

# Restart development server
php artisan serv
```

---

## Performance Issues

### Page load takes 10+ seconds

**Causes:**
- N+1 query problems (loading relationships individually)
- Large dataset without pagination
- Slow database queries
- Missing indexes

**Solution:**
```bash
# Check queries with Debugbar
# Press 'Queries' tab in bottom bar

# Identify slow queries
php artisan tinker
> \DB::enableQueryLog();
> $products = Product::all();
> \DB::getQueryLog();

# Use eager loading
// Before: N+1 problem
$products = Product::all();
foreach ($products as $product) {
    $product->category; // Additional query per product!
}

// After: Single query
$products = Product::with('category')->get();

# Add database indexes
php artisan tinker
Schema::table('products', function (Blueprint $table) {
    $table->index('category_id');
    $table->fullText('name', 'description');
});
```

---

### MySQL using 100% CPU

**Causes:**
- Long-running query
- Missing indexes
- Caching issue
- Memory leak

**Solution:**
```bash
# Check running processes
SHOW PROCESSLIST;

# Kill slow query
KILL <process_id>;

# Find slow queries
SHOW VARIABLES LIKE 'slow_query%';

# Add indexes to frequently queried columns
ALTER TABLE products ADD INDEX idx_category (category_id);
ALTER TABLE orders ADD INDEX idx_user (user_id);

# Optimize table
OPTIMIZE TABLE products;
OPTIMIZE TABLE orders;

# Clear cache
php artisan cache:clear
```

---

### Application memory limit exceeded

**Error:**
```
Allowed memory size of X bytes exhausted (tried to allocate Y bytes)
```

**Causes:**
- Loading too much data at once
- Memory leak in code
- Large file uploads

**Solution:**
```bash
# Increase memory limit
# .env or php.ini
memory_limit = 512M

# Or in code
ini_set('memory_limit', '512M');

# Use pagination for large datasets
$products = Product::paginate(15);

# Don't load all records
// Bad:
$allProducts = Product::all();

// Good:
$products = Product::with('images', 'category')
    ->select(['id', 'name', 'price'])
    ->paginate(20);

# Chunk large operations
Product::chunk(100, function($products) {
    foreach ($products as $product) {
        // Process product
    }
});
```

---

## File Upload Issues

### "Uploaded file exceeded maximum file size"

**Error:**
```
The uploaded file is too large
```

**Causes:**
- File size exceeds `upload_max_filesize` in php.ini
- File size exceeds Laravel validation limit
- Nginx `client_max_body_size` too small

**Solution:**
```bash
# Check PHP configuration
php -i | grep -E "upload_max_filesize|post_max_size"

# Update php.ini
sudo nano /etc/php/8.2/fpm/php.ini
# upload_max_filesize = 100M
# post_max_size = 100M

# Update Nginx configuration
sudo nano /etc/nginx/nginx.conf
# client_max_body_size 100M;

# Restart services
sudo systemctl restart php8.2-fpm
sudo systemctl restart nginx

# Check Laravel validation in controller
// Current: max:2048 (KB)
'image' => 'required|image|max:2048'

// Change to accept larger files
'image' => 'required|image|max:10240' // 10MB
```

---

### Image not displaying after upload

**Causes:**
- File uploaded to wrong directory
- Permission issues
- Wrong path in database
- File extension not allowed

**Solution:**
```bash
# Check upload directory exists and is writable
ls -la public/upload/
chmod 775 public/upload

# Check file permissions
chmod 644 public/upload/*

# Verify path in database
php artisan tinker
> ProductImage::first()->image_path

# Check file exists
> file_exists('public/' . ProductImage::first()->image_path)

# Fix path if needed
> ProductImage::where('image_path', 'like', '%//%)
    ->update(['image_path' => DB::raw("REPLACE(image_path, '//', '/')")])
```

---

### "file does not exist" error

**Error:**
```
File does not exist at path: upload/image.jpg
```

**Solution:**
```bash
# Create upload directory structure
mkdir -p public/upload/avatars
mkdir -p public/upload/products

# Set permissions
chmod -R 775 public/upload
chown -R www-data:www-data public/upload

# Use Storage facade for file operations
Storage::disk('public')->exists('image.jpg')

// Don't do:
// file_exists('upload/image.jpg')

// Instead:
// file_exists(public_path('upload/image.jpg'))
```

---

## Email Issues

### "No transport agent" error when sending email

**Error:**
```
Unable to send email. No transport agent available.
```

**Causes:**
- MAIL_DRIVER not configured in `.env`
- Email provider credentials invalid
- PHP mail() function disabled

**Solution:**
```bash
# Configure in .env
MAIL_DRIVER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_NAME="Tech Store"

# For Gmail app passwords:
# 1. Enable 2-factor authentication
# 2. Go to Google Account → Security → App passwords
# 3. Copy the 16-character password
# 4. Paste in MAIL_PASSWORD (no spaces)

# Test email sending
php artisan tinker
> Mail::raw('Test email', function($m) {
    $m->to('your@email.com')->subject('Test');
});

# Verify output
> 'Message sent!'
```

---

### Emails not sending in production

**Causes:**
- Firewall blocking SMTP port 587/465
- Queue not running
- Mail service not configured

**Solution:**
```bash
# Test email connection
telnet smtp.gmail.com 587

# Check queue is configured
php artisan queue:work
# Or with supervisor (see DEPLOYMENT.md)

# Update .env for production
MAIL_DRIVER=smtp
MAIL_HOST=smtp.sendgrid.net or smtp.gmail.com
MAIL_PORT=587
# Use environment-specific password

# Clear Laravel cache
php artisan cache:clear
php artisan config:clear
```

---

## Payment Issues

### Stripe "Invalid API Key" error

**Error:**
```
Invalid API Key provided
```

**Causes:**
- STRIPE_SECRET_KEY not set in `.env`
- Using test key in production or vice versa
- Missing Stripe package

**Solution:**
```bash
# Install Stripe package
composer require stripe/stripe-php

# Set Stripe keys in .env
STRIPE_PUBLIC_KEY=pk_test_xxxxx (development)
STRIPE_SECRET_KEY=sk_test_xxxxx (development)

# OR for production:
STRIPE_PUBLIC_KEY=pk_live_xxxxx
STRIPE_SECRET_KEY=sk_live_xxxxx

# Test payment processing
php artisan tinker
> $token = \Stripe\Token::create([
    'card' => [
        'number' => '4242424242424242',
        'exp_month' => 12,
        'exp_year' => 2025,
        'cvc' => '123'
    ]
  ]);
```

---

## Security Issues

### SQL Injection vulnerability

**Error:**
```
User input directly inserted in query
```

**Solution:**
```php
// Bad: SQL injection vulnerability
$products = DB::select("SELECT * FROM products WHERE category_id = " . $request->category);

// Good: Use parameterized queries
$products = Product::where('category_id', $request->category)->get();

// Or explicitly parameterize
$products = DB::select("SELECT * FROM products WHERE category_id = ?", [$request->category]);

// Never do string concatenation with user input!
```

---

### IDOR - Accessing other user's data

**Error:**
```
User can view/edit orders of other users
```

**Solution:**
```php
// Bad: No ownership check
Route::get('/orders/{id}', function(Order $order) {
    return $order; // Anyone can access any order!
});

// Good: Check ownership
Route::get('/orders/{order}', function(Order $order) {
    Gate::authorize('view', $order); // Check authorization
    return $order;
});

// In policy:
public function view(User $user, Order $order) {
    return $user->id === $order->user_id || $user->is_admin;
}
```

---

### XSS - User input displayed unsafely

**Error:**
```
User input shown without escaping: <script>alert('hacked')</script>
```

**Solution:**
```blade
<!-- Bad: HTML not escaped -->
{{ $user->bio }}

<!-- Good: HTML escaped (default in Blade) -->
{{ $user->bio }}

<!-- If you need HTML, sanitize first -->
{!! Purifier::clean($user->bio) !!}

<!-- Always escape user input in JavaScript too -->
<script>
const userInput = "{{ json_encode($userInput) }}";
// Not: const userInput = '{{ $userInput }}';
</script>
```

---

## Deployment Issues

### Application shows blank page after deployment

**Causes:**
- APP_DEBUG=false but error not logged
- Missing .env file or wrong values
- Database migration not run
- PHP version mismatch

**Solution:**
```bash
# Check Laravel logs
tail -50 /var/www/html/tech_store_app/storage/logs/laravel.log

# Verify .env exists and configured
cat /var/www/html/tech_store_app/.env | grep APP_

# Run migrations
php artisan migrate --force

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:cache

# Check PHP version
php -v  # Should be 8.1 or higher

# Restart services
sudo systemctl restart php8.2-fpm
sudo systemctl restart nginx
```

---

### "Class not found" error after deployment

**Error:**
```
Class 'App\Models\Product' not found
```

**Solution:**
```bash
# Run Composer autoload optimization
composer install --no-dev --optimize-autoloader

# Or manually dump autoloader
composer dump-autoload -o

# Verify the class file exists
ls -la app/Models/products.php

# Check namespace in class matches file path
// File: app/Models/products.php
// Should have: namespace App\Models;
```

---

### 503 Service Unavailable

**Causes:**
- No workers processing queue
- PHP-FPM not running
- Database connection issue

**Solution:**
```bash
# Check service status
sudo systemctl status php8.2-fpm
sudo systemctl status nginx
sudo systemctl status mysql

# Start if stopped
sudo systemctl start php8.2-fpm

# Check error logs
tail -50 /var/log/php-fpm.log
tail -50 /var/log/nginx/error.log

# Restart services
sudo systemctl restart php8.2-fpm
sudo systemctl restart nginx
```

---

## Development Issues

### "Port 8000 already in use"

**Error:**
```
Address already in use
```

**Causes:**
- Another app using port 8000
- Old `php artisan serve` still running

**Solution:**
```bash
# Find process using port
lsof -i :8000
# or
netstat -plant | grep 8000

# Kill process
kill -9 <PID>

# Or use different port
php artisan serve --port=8001

# On Windows:
netstat -ano | findstr :8000
taskkill /PID <PID> /F
```

---

### Hot reload not working with Vite

**Error:**
```
Vite HMR not connecting
CSS/JS changes not reflecting
```

**Solution:**
```bash
# Ensure Vite dev server is running in separate terminal
npm run dev

# Check Vite configuration in vite.config.js
export default defineConfig({
    plugins: [
        laravel(['resources/css/app.css', 'resources/js/app.js']),
    ],
    server: {
        hmr: {
            host: 'localhost',
            port: 5173,
        }
    }
});

# Force refresh browser
# Ctrl+Shift+R (Windows/Linux)
# Cmd+Shift+R (Mac)

# Clear browser cache
# Dev Tools → Application → Cache Storage
```

---

### "Too many open files" error

**Error:**
```
fopen(): Too many open files
```

**Causes:**
- File descriptors limit exceeded
- Connection pools exhausted
- File handles not closed

**Solution:**
```bash
# Check current limit
ulimit -n

# Increase limit temporarily
ulimit -n 65536

# Increase permanently
sudo nano /etc/security/limits.conf
# Add: * soft nofile 65536
# Add: * hard nofile 65536

# Restart terminal/service

# In code, always close resources
$file = fopen('path/to/file', 'r');
try {
    // Use file
} finally {
    fclose($file);
}
```

---

### Session issues - data lost between requests

**Error:**
```
Session variables disappearing
Cart items lost on page reload
Login session expires too quickly
```

**Solution:**
```bash
# Check SESSION_DRIVER in .env
SESSION_DRIVER=cookie  # Default, stored in browser
SESSION_DRIVER=database  # Requires sessions table

# Create sessions table if using database
php artisan session:table
php artisan migrate

# Verify cookie configuration in config/session.php
'secure' => env('SESSION_SECURE_COOKIES', false), // Set true for HTTPS
'http_only' => true,
'same_site' => 'lax',

# Check session lifetime
'lifetime' => env('SESSION_LIFETIME', 120), // Minutes

# Clear browser cookies
Dev Tools → Application → Cookies → Delete all

# Test localStorage for dark mode
localStorage.setItem('gizmo-store-dark-mode', 'true');
localStorage.getItem('gizmo-store-dark-mode');
```

---

### Git merge conflicts

**Error:**
```
Conflict (content)
Both added: file.php
Auto-merging failed
```

**Solution:**
```bash
# View conflicts
git status

# Open file and fix
nano file.php
# Remove <<<<<<, ======, >>>>>> markers
# Choose which version to keep

# Mark as resolved
git add file.php

# Complete merge
git commit -m "Resolved merge conflicts"

# Or abort merge
git merge --abort
```

---

## Getting Help

### Check These First
1. Error message and full stack trace
2. Recent code changes (git diff)
3. Browser console (DevTools)
4. Laravel logs: `storage/logs/laravel.log`
5. Server logs: `tail /var/log/nginx/error.log`

### Resources
- [Laravel Docs](https://laravel.com/docs)
- [Stack Overflow](https://stackoverflow.com/questions/tagged/laravel)
- [GitHub Issues](https://github.com/laravel/framework/issues)
- [Discord Community](https://discord.gg/laravel)

---

Last Updated: February 16, 2026
