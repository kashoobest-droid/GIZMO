# Architecture & Project Structure

Complete overview of the KS Tech Store project architecture, design patterns, and code organization.

## System Architecture

### High-Level Architecture Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                    Web Browser / Mobile                      │
│              (HTTP Request / Response Cycle)                 │
└────────────────────────┬────────────────────────────────────┘
                         │
┌────────────────────────▼────────────────────────────────────┐
│                      Web Server                              │
│        (Apache / Nginx / Laravel Built-in)                  │
└────────────────────────┬────────────────────────────────────┘
                         │
┌────────────────────────▼────────────────────────────────────┐
│           Laravel Framework (Routing & Middleware)           │
│  Handles authentication, CSRF, rate limiting, etc.          │
└──┬─────────────────┬──────────────────┬────────────────────┘
   │                 │                  │
   ▼                 ▼                  ▼
┌──────────┐    ┌──────────┐      ┌──────────┐
│Controller│    │Middleware│      │ Routes   │
│(Business │    │(Filters) │      │(Mapping) │
│  Logic)  │    │          │      │          │
└────┬─────┘    └──────────┘      └──────────┘
     │
┌────▼──────────────────────────────────────────────────────┐
│              Models (Eloquent ORM)                         │
│   ├── User, Product, Order, Review, etc.                  │
│   └── Database relationships & scopes                     │
└────┬──────────────────────────────────────────────────────┘
     │
┌────▼──────────────────────────────────────────────────────┐
│              MySQL Database                                │
│   ├── users, products, orders, cart_items                 │
│   ├── reviews, favorites, coupons, offers                 │
│   └── migrations (schema version control)                 │
└───────────────────────────────────────────────────────────┘
```

## Directory Structure

```
tech_store_app/
│
├── app/                          # Application code
│   ├── Http/
│   │   ├── Controllers/          # Request handlers (30+ controllers)
│   │   │   ├── ProductsController.php       # Product CRUD
│   │   │   ├── OrderController.php          # Order management
│   │   │   ├── UserController.php           # User management
│   │   │   ├── ReviewController.php         # Reviews & ratings
│   │   │   ├── CartController.php           # Shopping cart
│   │   │   ├── FavoriteController.php       # Wishlist
│   │   │   ├── ProfileController.php        # User profile
│   │   │   ├── DashboardController.php      # Admin dashboard
│   │   │   └── [+20 more controllers]
│   │   │
│   │   ├── Middleware/          # Request filters
│   │   │   ├── Authenticate.php
│   │   │   ├── Admin.php
│   │   │   └── Custom middleware
│   │   │
│   │   └── Kernel.php           # HTTP kernel configuration
│   │
│   ├── Models/                  # Eloquent Models
│   │   ├── User.php             # User account model
│   │   ├── Product.php          # Product catalog
│   │   ├── Order.php            # Orders
│   │   ├── OrderItem.php        # Order line items
│   │   ├── Review.php           # Product reviews
│   │   ├── CartItem.php         # Shopping cart
│   │   ├── Favorite.php         # Wishlist
│   │   ├── Category.php         # Categories
│   │   ├── Coupon.php           # Discount codes
│   │   ├── Offer.php            # Promotions
│   │   └── [+5 more models]
│   │
│   ├── Policies/                # Authorization policies
│   │   └── UserPolicy.php       # User resource authorization
│   │
│   ├── Mail/                    # Mailable classes
│   │   ├── OrderConfirmed.php   # Order confirmation email
│   │   ├── StockBackNotification.php
│   │   └── [Custom mailables]
│   │
│   ├── Jobs/                    # Queued jobs
│   │   └── NotifyStockBackJob.php
│   │
│   ├── Exceptions/              # Custom exceptions
│   │   └── Handler.php
│   │
│   └── Providers/               # Service providers
│       ├── AppServiceProvider.php
│       ├── AuthServiceProvider.php  # Policy registration
│       ├── RouteServiceProvider.php
│       ├── EventServiceProvider.php
│       └── BroadcastServiceProvider.php
│
├── database/                    # Database files
│   ├── migrations/              # Schema version control
│   │   ├── 2024_01_create_users_table.php
│   │   ├── 2024_01_create_products_table.php
│   │   ├── 2024_01_create_orders_table.php
│   │   ├── 2024_01_create_cart_items_table.php
│   │   ├── 2024_01_create_reviews_table.php
│   │   └── [+10 more migrations]
│   │
│   ├── seeders/                 # Data seeders
│   │   ├── DatabaseSeeder.php   # Master seeder
│   │   ├── UserSeeder.php
│   │   ├── ProductSeeder.php
│   │   └── CategorySeeder.php
│   │
│   └── factories/               # Model factories for testing
│       └── UserFactory.php
│
├── resources/                   # Frontend assets
│   ├── views/                   # Blade templates (HTML)
│   │   ├── layouts/
│   │   │   └── app.blade.php    # Main layout template
│   │   ├── auth/                # Login/register pages
│   │   ├── products/            # Product pages
│   │   ├── cart.blade.php       # Shopping cart
│   │   ├── checkout.blade.php   # Checkout form
│   │   ├── orders/              # Order pages
│   │   ├── profile.blade.php    # User profile
│   │   ├── admin/               # Admin pages
│   │   ├── partials/            # Reusable components
│   │   └── [+15 more views]
│   │
│   ├── lang/                    # Localization files
│   │   ├── en/
│   │   │   └── messages.php     # English translations
│   │   └── ar/
│   │       └── messages.php     # Arabic translations
│   │
│   ├── css/                     # Stylesheets
│   │   └── app.css
│   │
│   └── js/                      # JavaScript files
│       └── app.js
│
├── routes/                      # Route definitions
│   ├── web.php                  # Web application routes
│   ├── api.php                  # API routes (optional)
│   ├── channels.php            # Broadcasting channels
│   └── console.php             # Console commands
│
├── config/                      # Configuration files
│   ├── app.php                  # Application configuration
│   ├── database.php             # Database connection config
│   ├── auth.php                 # Authentication config
│   ├── mail.php                 # Mail configuration
│   ├── filesystem.php           # File storage config
│   ├── session.php              # Session configuration
│   ├── cache.php                # Cache driver config
│   └── [+10 more configs]
│
├── public/                      # Publicly accessible files
│   ├── index.php                # Application entry point
│   ├── css/                     # Compiled CSS
│   ├── js/                      # Compiled JavaScript
│   ├── images/                  # Static images
│   ├── upload/                  # User uploaded files
│   │   ├── avatars/             # User profile pictures
│   │   ├── products/            # Product images
│   │   └── offers/              # Offer images
│   └── robots.txt               # SEO robots file
│
├── storage/                     # Application storage
│   ├── app/                     # Application files
│   │   ├── public/              # Public user uploads
│   │   └── private/             # Private files
│   ├── framework/               # Framework cache
│   ├── logs/                    # Application logs
│   └── temp/                    # Temporary files
│
├── bootstrap/                   # Bootstrap files
│   ├── app.php                  # Service container bootstrap
│   └── cache/                   # Bootstrap cache
│
├── tests/                       # Test files
│   ├── Unit/                    # Unit tests
│   ├── Feature/                 # Feature tests
│   ├── TestCase.php             # Base test case
│   └── CreatesApplication.php
│
├── vendor/                      # Composer dependencies
│   └── [Laravel & 50+ packages]
│
├── node_modules/                # NPM dependencies
│
├── .env                         # Environment variables (local)
├── .env.example                 # Environment template
├── .gitignore                   # Git ignore rules
├── artisan                      # Laravel CLI entry point
├── composer.json                # PHP dependencies
├── composer.lock                # Locked dependency versions
├── package.json                 # JavaScript dependencies
├── package-lock.json            # Locked npm versions
├── webpack.mix.js               # Asset compilation config
├── phpunit.xml                  # PHPUnit configuration
├── README.md                    # Project documentation
├── SETUP.md                     # Installation guide
├── ARCHITECTURE.md              # This file
├── API_DOCUMENTATION.md         # API reference
├── SECURITY.md                  # Security practices
├── DEPLOYMENT.md                # Deployment guide
└── LICENSE                      # MIT License
```

## Design Patterns Used

### 1. **MVC (Model-View-Controller)**
- **Models** - Data layer (Eloquent ORM)
- **Views** - Presentation layer (Blade templates)
- **Controllers** - Logic layer (HTTP handlers)

```
Request → Router → Middleware → Controller → Model → View → Response
```

### 2. **Repository Pattern** (Partial)
Controllers directly access models for simplicity, but can be extended with repositories for complex queries.

### 3. **Service Provider Pattern**
- `AppServiceProvider` - Application services
- `AuthServiceProvider` - Authentication & authorization
- `RouteServiceProvider` - Route registration

### 4. **Authorization Policies**
Built into Laravel:
```php
// UserPolicy enforces who can edit users
$this->authorize('update', $user);  // Checks UserPolicy::update()
```

### 5. **Factory Pattern**
Database seeders use factories to generate test data:
```php
// Create 50 users with fake data
User::factory()->count(50)->create();
```

## Request-Response Cycle

### Example: Add Product to Cart

```
1. User clicks "Add to Cart"
   ↓
2. POST /cart/add/{product}
   ↓
3. Routing: Routes to CartController@add()
   ↓
4. Middleware checks:
   - Is user authenticated?
   - CSRF token valid?
   - Rate limit exceeded?
   ↓
5. Controller logic:
   - Validate product exists
   - Check stock available
   - Get user from Auth facade
   ↓
6. Model interaction:
   - Find or create CartItem
   - Update quantity
   - Save to database
   ↓
7. Response:
   - Return success message OR
   - Redirect with flash message
```

## Key Design Decisions

### 1. Authentication
- Uses Laravel's built-in **Guard** system
- Sessions stored in database/file
- Password hashing with **Bcrypt**

### 2. Authorization
- **Policies** for resource-level authorization
- **Middleware** for route-level authorization
- Checks like `Auth::check()` and `$user->is_admin`

### 3. Database Relationships
```

User (1) ──→ (Many) Order
User (1) ──→ (Many) Review
User (1) ──→ (Many) CartItem
Product (1) ──→ (Many) Review
Product (1) ──→ (Many) CartItem
Product (1) ──→ (Many) OrderItem
Product (Many) ──→ (Many) Category
Order (1) ──→ (Many) OrderItem
```

### 4. Language Support
- Translation files: `resources/lang/{en,ar}/messages.php`
- Route middleware: `SetLocale` to set `app()->setLocale()`
- Session + Cookie storage for persistence

### 5. Dark Mode
- CSS class: `html.dark-mode` applied to root
- LocalStorage: `gizmo-store-dark-mode` boolean
- Stylesheet: Duplicate selectors for dark variants

## File Upload Flow

```
User selects image
      ↓
Form submitted to /profile/update
      ↓
Controller validates:
  - Is file image?
  - Size < 2MB?
  - Extension allowed?
      ↓
File moved to storage/app/public/
      ↓
Path saved to database (users.avatar)
      ↓
Symlink: public/storage → storage/app/public
      ↓
Display: <img src="/storage/avatars/avatar_1_xyz.jpg">
```

## Payment Gateway Integration (Future)

```
Order created
   ↓
Redirect to payment gateway (Stripe/PayPal)
   ↓
User fills payment details
   ↓
Webhook callback to /webhooks/payment
   ↓
Update order status to 'paid'
   ↓
Trigger email notification
```

## Email Notification System

```
Event triggered (e.g., order.created)
   ↓
Event listener executes
   ↓
Mailable class created (OrderConfirmed)
   ↓
If QUEUE_DRIVER=sync: Send immediately
   If QUEUE_DRIVER=database: Queue job
      ↓
      Worker picks up job
      ↓
   Send via SMTP
      ↓
Queue job marked complete
```

## Caching Strategy

```
Database query
   ↓
Check cache first: cache()->get('key')
   ↓
If cached: Return cached result
If not: Query database
   ↓
Store result: cache()->put('key', $result)
   ↓
Cache expires after set duration
   ↓
Next request uses fresh cache
```

## Security Layers

```
Input  ↓
       → Validation (requ|email|unique rules)
       → Sanitization (htmlspecialchars, etc.)
Database ↓
       → Parameterized queries (Eloquent)
       → SQL injection prevention
Authorization ↓
       → Role checks ($user->is_admin)
       → Policies (UserPolicy)
       → IDOR protection
Output ↓
       → Blade escaping {{ $variable }}
       → CSRF tokens in forms
```

## Performance Considerations

### Database Optimization
- **Indexes** on frequently queried columns
- **Eager loading** with `->with()` to prevent N+1 queries
- **Pagination** to limit result sets
- **Caching** for expensive queries

### Frontend Optimization
- **CSS/JS minification** via Mix
- **Image optimization** (resize on upload)
- **Lazy loading** for product images
- **CDN** for static assets (optional)

## Scalability Considerations

For scaling to 100K+ users:
1. **Database:** Add read replicas, implement sharding
2. **Cache:** Move to Redis or Memcached
3. **Sessions:** Centralize in Redis
4. **Queue:** Use Laravel Horizon
5. **Search:** Implement Elasticsearch
6. **Storage:** Use S3 for file uploads

## Testing Strategy

```
Unit Tests
   └── Test individual methods
       └── UserTest::testPasswordHashing()

Feature Tests
   └── Test full request-response
       └── OrderTest::testCheckoutFlow()

Database Tests
   └── Test model relationships
       └── ProductTest::testProductHasReviews()
```

---

**Last Updated:** February 16, 2026
