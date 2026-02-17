# Security Best Practices & Guidelines

Comprehensive security documentation for the KS Tech Store project.

## Overview

This project implements multiple security layers to protect against common web vulnerabilities:

- ✅ SQL Injection
- ✅ Cross-Site Scripting (XSS)
- ✅ Cross-Site Request Forgery (CSRF)
- ✅ Insecure Direct Object Reference (IDOR)
- ✅ Cross-Origin Resource Sharing (CORS)
- ✅ Password Security
- ✅ Session Hijacking

## 1. IDOR (Insecure Direct Object Reference) Protection

### What is IDOR?
Attacking a resource by guessing or manipulating IDs in URLs without proper authorization.

### Example Vulnerability:
```php
// ❌ VULNERABLE
public function show(Order $order) {
    return view('order.show', compact('order'));  // No auth check!
}
// Any user can view any order by changing URL: /orders/1, /orders/2, etc.
```

### Protected Implementation:
```php
// ✅ SECURE
public function show(Order $order) {
    // Method 1: Direct ownership check
    if ($order->user_id !== Auth::id() && !Auth::user()->is_admin) {
        abort(403);  // Forbidden
    }
    return view('order.show', compact('order'));
}

// Method 2: Using policies (recommended)
public function show(Order $order) {
    $this->authorize('view', $order);  // Checks OrderPolicy::view()
    return view('order.show', compact('order'));
}
```

### Protected Resources in This Project:
1. **Orders** - `UserPolicy` ensures users see only their orders
2. **Reviews** - Users can only edit/delete their own reviews
3. **Cart Items** - Ownership verified before deletion
4. **User Management** - Only admins can edit/delete users
5. **Profiles** - Users edit only their own profile

### Policy Implementation:
```php
// app/Policies/UserPolicy.php
public function update(User $user, User $model): bool {
    return $user->is_admin;  // Only admins can update users
}

// Usage in controller:
$this->authorize('update', $user);  // Checks policy before proceeding
```

## 2. SQL Injection Prevention

### What is SQL Injection?
Inserting malicious SQL code through user input to access/modify database.

### Vulnerable Code:
```php
// ❌ NEVER DO THIS
$user = User::whereRaw("email = '" . $email . "'");  // Vulnerable!
// User could input: ' OR '1'='1' -- to bypass authentication
```

### Protected Code:
```php
// ✅ CORRECT - Parameterized queries
$user = User::where('email', $email)->first();

// Or with DB::statement
$user = User::where('email', '=', $email)->first();

// Parameter binding prevents SQL injection
// Laravel automatically escapes values
```

### Safe Patterns Used:
```php
// All safe - values are parameterized
User::where('email', $request->email)->first();
Order::where('user_id', Auth::id())->get();
Product::whereIn('id', $ids)->get();
```

## 3. Cross-Site Scripting (XSS) Prevention

### What is XSS?
Injecting JavaScript code that executes in other users' browsers.

### Vulnerable Code:
```blade
// ❌ VULNERABLE - Unescaped output
{{ $comment }}  <!-- If comment = "<script>alert('hacked')</script>" -->
<p>{{ $review->text }}</p>  <!-- Script will execute! -->
```

### Protected Code:
```blade
// ✅ CORRECT - Escaped output (default in Laravel)
{!! $comment !!}  <!-- Only use if HTML is safe/from database -->
{{ $product->name }}  <!-- Automatically escaped -->

// Escape HTML entities
{{ htmlspecialchars($title) }}
```

### Rules:
1. **Always use `{{ }}` for user input** - Automatically escapes
2. **Only use `{!! !!}` for trusted HTML** - From database, not user
3. **Validate & sanitize input** - Use `strip_tags()` if needed

### Current Implementation:
All Blade templates use proper escaping:
```blade
<!-- SAFE -->
<h2>{{ $product->name }}</h2>
<p>{{ $product->description }}</p>

<!-- SAFE - From database only -->
{!! $offer->description !!}
```

## 4. Cross-Site Request Forgery (CSRF) Protection

### What is CSRF?
Tricking a user into performing actions they don't intend (e.g., transferring money).

### Example Attack:
```
1. User logs into bank.com
2. User visits attacker.com (in another tab)
3. attacker.com secretly posts to bank.com/transfer
4. Bank processes transfer because user is logged in!
```

### Protection Method:
```php
// All forms MUST include CSRF token
<form method="POST" action="/orders">
    @csrf  <!-- Laravel generates token -->
    <input type="text" name="...">
</form>

// Token validation happens automatically in
// App\Http\Middleware\VerifyCsrfToken middleware
```

### How It Works:
```
1. Form includes CSRF token: <input name="_token" value="abc123xyz">
2. Token stored in session: $_SESSION['csrf_token'] = "abc123xyz"
3. Post request includes token
4. Laravel verifies tokens match
5. If tokens don't match → Request rejected (419 error)
```

### Current Implementation:
All forms include `@csrf` directive:
```blade
<!-- Forms are protected -->
<form method="POST" action="{{ route('orders.store') }}">
    @csrf
    <!-- form fields -->
</form>
```

### AJAX Requests:
```javascript
// Include token in headers
headers: {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
}
```

## 5. Password Security

### Requirements:
- Minimum **8 characters**
- **Uppercase & lowercase** letters
- **At least one number**
- **Bcrypt hashing** (Laravel default)

### Implementation:
```php
// Password validation rule
$request->validate([
    'password' => [
        'required',
        'confirmed',
        Password::min(8)
            ->letters()
            ->numbers()
            ->mixedCase(),
    ],
]);

// Hashing (automatic in UserModel)
$user->password = Hash::make($request->password);
```

### Password Reset:
```php
// 1. User requests reset
POST /password/email → Email sent with reset link

// 2. Reset link contains token
/password/reset/{token}

// 3. Token expires in 60 minutes
// 4. Can't be reused - replaced on next request
```

### Common Mistakes to Avoid:
```php
// ❌ DON'T - Storing plaintext
$user->password = $request->password;  // NEVER!

// ❌ DON'T - Custom hashing
$user->password = md5($request->password);  // Outdated!

// ✅ DO - Use Laravel's Hash facade
$user->password = Hash::make($request->password);

// ✅ DO - Verify with Hash::check()
if (Hash::check($plaintext, $hashed)) {
    // Password is correct
}
```

## 6. Session Security

### Session Configuration:
```php
// config/session.php
'lifetime' => 120,  // Minutes before expiration
'expire_on_close' => false,  // Don't expire on browser close
'secure' => env('SESSION_SECURE_COOKIES', false),  // HTTPS only
'http_only' => true,  // Not accessible via JavaScript
'same_site' => 'lax',  // CSRF protection
```

### Session Storage:
```dotenv
# .env - Choose one:
SESSION_DRIVER=file      # Local file storage (development)
SESSION_DRIVER=database  # Database storage (production preferred)
SESSION_DRIVER=redis     # Redis (high-performance)
```

### Current Setup:
Using **file sessions** for development, switch to **database** for production:

```bash
# Production setup
php artisan session:table
php artisan migrate
# Edit .env: SESSION_DRIVER=database
```

## 7. Database Security

### Connection Security:
```php
// .env - Use environment variables
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tech_store_app
DB_USERNAME=tech_store
DB_PASSWORD=SecurePassword123

// NEVER hardcode credentials in code!
```

### SQL Injection Prevention:
All Eloquent queries use parameterized statements:
```php
// ✅ SAFE - Values are parameterized
$user = User::where('email', $email)->first();

// ✅ SAFE - Arrays are parameterized too
$users = User::whereIn('id', [1, 2, 3])->get();
```

### Sensitive Data:
```php
// Hide passwords from API responses
class User extends Model {
    protected $hidden = ['password'];  // Never output password
}

// Encrypt sensitive fields
public $casts = [
    'phone' => 'encrypted',
];
```

## 8. Authentication Security

### Login Process:
```
1. User submits email + password
2. Find user by email
3. Verify password: Hash::check($input, $stored)
4. Create session/token
5. Set auth cookie (httpOnly)
6. Redirect to dashboard
```

### Login Rate Limiting:
```php
// Throttle login attempts
Route::post('/login', [LoginController::class, 'login'])
    ->middleware('throttle:5,1');  // 5 attempts per minute
```

### Two-Factor Authentication (Future):
Consider adding 2FA for admin accounts:
- SMS code
- Authenticator app (Google Authenticator)
- Backup codes

## 9. File Upload Security

### Validation:
```php
$request->validate([
    'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    'file' => 'file|mimes:pdf,doc|max:10240',
]);

// Checks:
// - Is it an image?
// - Is it a safe format?
// - Is size under limit?
```

### Storage:
```php
// Store outside public directory (private files)
Storage::disk('private')->put('file.pdf', $contents);

// Store in public but with hash filename
$filename = hash('sha256', microtime()) . '.jpg';
$path = 'uploads/' . $filename;
```

### Display:
```blade
<!-- Use full URL from database -->
<img src="{{ asset($user->avatar) }}" alt="Profile">

<!-- Configure symlink properly -->
<!-- public/storage → storage/app/public -->
```

## 10. API Security (If You Build an API)

### Authentication:
```php
// Use Laravel Sanctum for API tokens
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function () {
        return Auth::user();
    });
});
```

### CORS (Cross-Origin):
```php
// config/cors.php
'allowed_origins' => ['https://example.com'],
'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE'],
'max_age' => 3600,
```

## 11. Environment Variables

### Never Commit Sensitive Data:
```bash
# ✅ DO - Use .env file (in .gitignore)
APP_KEY=base64:abcd...
DB_PASSWORD=secret

# ❌ DON'T - Commit credentials
git add .env  # NEVER!

# ✅ DO - Commit template only
git add .env.example
```

### Secure Defaults:
```dotenv
# .env (production)
APP_ENV=production
APP_DEBUG=false            # Never expose errors in production
MAIL_FROM_ADDRESS=safe@example.com  # Use secure sender
```

## 12. Security Checklist

### Before Deployment:
- [ ] Change default admin credentials
- [ ] Set `APP_DEBUG=false`
- [ ] Set `APP_ENV=production`
- [ ] Use HTTPS (SSL certificate)
- [ ] Update all dependencies: `composer update`
- [ ] Run tests: `php artisan test`
- [ ] Run security scan: `composer audit`
- [ ] Set up proper database backups
- [ ] Enable CORS only for trusted origins
- [ ] Disable file listing: `Options -Indexes` in .htaccess
- [ ] Set proper file permissions: `755` directories, `644` files
- [ ] Configure firewall (UFW, iptables)
- [ ] Monitor logs: `tail -f storage/logs/laravel.log`
- [ ] Set up backup strategy

### Regular Maintenance:
- [ ] Review access logs weekly
- [ ] Update dependencies monthly
- [ ] Run security audits quarterly
- [ ] Rotate admin credentials every 90 days
- [ ] Test disaster recovery procedures
- [ ] Review user permissions and roles

## 13. Reporting Security Issues

If you find a vulnerability:
1. **DO NOT** post it publicly
2. Email: `security@example.com`
3. Include:
   - Vulnerability description
   - Steps to reproduce
   - Potential impact
   - Suggested fix (if known)

**Responsible Disclosure Timeline:**
- Day 1: Report vulnerability
- Day 7: Acknowledgment required
- Day 30: Patch should be available
- Day 60: Public disclosure (if not patched)

## Resources

- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [Laravel Security](https://laravel.com/docs/security)
- [CWE - Common Weakness Enumeration](https://cwe.mitre.org/)
- [PortSwigger Web Security](https://portswigger.net/web-security)

---

**Last Updated:** February 16, 2026
**Security Level:** Production-Ready
