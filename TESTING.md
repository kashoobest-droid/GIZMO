# Testing Guide

Comprehensive testing strategy and examples for Tech Store App.

## Table of Contents

1. [Testing Overview](#testing-overview)
2. [Setup & Configuration](#setup--configuration)
3. [Unit Tests](#unit-tests)
4. [Feature Tests](#feature-tests)
5. [Testing Commands](#testing-commands)
6. [Test Coverage](#test-coverage)
7. [Common Test Patterns](#common-test-patterns)
8. [CI/CD Integration](#cicd-integration)

---

## Testing Overview

### Why Testing Matters

- **Confidence**: Verify code works before deployment
- **Regression prevention**: Catch bugs from code changes
- **Documentation**: Tests show how code should work
- **Refactoring safety**: Change code without breaking features
- **Quality**: Reduce bugs in production

### Test Types

| Type | Scope | Speed | Coverage |
|------|-------|-------|----------|
| **Unit** | Single class/method | Fast | Deep |
| **Feature** | User scenarios | Medium | Behavioral |
| **Integration** | Multiple systems | Slow | Complete |
| **E2E** | Full application flow | Slowest | Real-world |

### Current Testing Status

**Required tests (not yet implemented):**
- [ ] User authentication tests
- [ ] Product catalog tests
- [ ] Cart functionality tests
- [ ] Order creation tests
- [ ] Authorization tests (IDOR protection)
- [ ] Review system tests
- [ ] Favorite system tests

**Recommended coverage goal:** 70%+ of critical paths

---

## Setup & Configuration

### 1. Install Testing Dependencies

```bash
composer require --dev phpunit/phpunit
composer require --dev laravel/dusk
composer require --dev laravel/telescope
```

These are typically pre-installed in Laravel projects.

### 2. Configure Testing Environment

**File:** `.env.testing` (create from `.env`)

```env
APP_ENV=testing
APP_DEBUG=true
DB_DATABASE=tech_store_test
DB_CONNECTION=testing

# In-memory SQLite for fast testing
DB_CONNECTION=sqlite
DB_DATABASE=:memory:

# No mail sent during testing
MAIL_DRIVER=log
```

### 3. Database Setup

**For testing, Laravel uses transactions to rollback after each test.**

Create test database:
```bash
php artisan migrate --env=testing
```

### 4. PHPUnit Configuration

**File:** `phpunit.xml`

```xml
<phpunit
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:noNamespaceSchemaLocation="https://schema.phpunit.de/9.5/phpunit.xsd"
    bootstrap="bootstrap/app.php"
    cacheDirectory=".phpunit.cache"
    executionOrder="depends,defects"
    failOnRisky="true"
    failOnWarning="true"
    verbose="true">

    <testsuites>
        <testsuite name="Unit">
            <directory suffix="Test.php">./tests/Unit</directory>
        </testsuite>
        <testsuite name="Feature">
            <directory suffix="Test.php">./tests/Feature</directory>
        </testsuite>
    </testsuites>

    <coverage processUncoveredFiles="true">
        <include>
            <directory suffix=".php">./app</directory>
        </include>
        <exclude>
            <directory>./app/Console</directory>
        </exclude>
    </coverage>

    <php>
        <ini name="display_errors" value="Off" />
        <ini name="error_reporting" value="-1" />
    </php>
</phpunit>
```

---

## Unit Tests

Unit tests verify individual components in isolation.

### Test Structure

```php
<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Product;

class ProductTest extends TestCase
{
    /**
     * Test that product calculates total correctly
     * 
     * @test
     * @return void
     */
    public function test_product_total_calculation()
    {
        // Arrange
        $product = new Product([
            'price' => 100,
            'quantity' => 5
        ]);

        // Act
        $total = $product->getTotal();

        // Assert
        $this->assertEquals(500, $total);
    }
}
```

### Example: Product Model Tests

**File:** `tests/Unit/ProductTest.php`

```php
<?php

namespace Tests\Unit;

use App\Models\products;
use App\Models\category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test product can be created
     */
    public function test_product_creation()
    {
        $category = category::create(['name' => 'Electronics']);
        
        $product = products::create([
            'name' => 'Gaming Laptop',
            'price' => 1299.99,
            'quantity' => 5,
            'Category_id' => $category->id,
            'total' => 1299.99 * 5
        ]);

        $this->assertNotNull($product->id);
        $this->assertEquals('Gaming Laptop', $product->name);
    }

    /**
     * Test product is out of stock when quantity < 1
     */
    public function test_product_out_of_stock()
    {
        $category = category::create(['name' => 'Electronics']);
        
        $product = products::create([
            'name' => 'Keyboard',
            'price' => 99.99,
            'quantity' => 0,
            'Category_id' => $category->id,
            'total' => 0
        ]);

        $this->assertFalse($product->isInStock());
    }

    /**
     * Test product is in stock when quantity >= 1
     */
    public function test_product_in_stock()
    {
        $category = category::create(['name' => 'Electronics']);
        
        $product = products::create([
            'name' => 'Mouse',
            'price' => 29.99,
            'quantity' => 10,
            'Category_id' => $category->id,
            'total' => 29.99 * 10
        ]);

        $this->assertTrue($product->isInStock());
    }
}
```

### Example: User Model Tests

**File:** `tests/Unit/UserTest.php`

```php
<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user password is hashed when created
     */
    public function test_user_password_hashing()
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('Password123'),
            'is_admin' => false
        ]);

        $this->assertTrue(Hash::check('Password123', $user->password));
    }

    /**
     * Test user admin flag
     */
    public function test_user_admin_flag()
    {
        $adminUser = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('Password123'),
            'is_admin' => true
        ]);

        $this->assertTrue($adminUser->is_admin);
    }

    /**
     * Test user can have related relationships
     */
    public function test_user_relationships()
    {
        $user = User::create([
            'name' => 'John',
            'email' => 'john@example.com',
            'password' => Hash::make('Password123')
        ]);

        $this->assertNotNull($user->cartItems());
        $this->assertNotNull($user->favorites());
        $this->assertNotNull($user->orders());
    }
}
```

---

## Feature Tests

Feature tests verify complete user scenarios and workflows.

### Test Structure

```php
<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test a basic feature
     *
     * @test
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
}
```

### Example: Authentication Tests

**File:** `tests/Feature/AuthTest.php`

```php
<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user can register
     */
    public function test_user_can_register()
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com'
        ]);
    }

    /**
     * Test user can login
     */
    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('Password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'Password123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test login fails with invalid credentials
     */
    public function test_login_fails_with_invalid_credentials()
    {
        $response = $this->post('/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'WrongPassword',
        ]);

        $response->assertRedirect();
        $this->assertGuest();
    }

    /**
     * Test user can logout
     */
    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
```

### Example: Product Feature Tests

**File:** `tests/Feature/ProductTest.php`

```php
<?php

namespace Tests\Feature;

use App\Models\products;
use App\Models\category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['is_admin' => true]);
    }

    /**
     * Test user can view product
     */
    public function test_user_can_view_product()
    {
        $category = category::create(['name' => 'Electronics']);
        $product = products::create([
            'name' => 'Test Product',
            'price' => 99.99,
            'quantity' => 10,
            'Category_id' => $category->id,
            'total' => 999.90
        ]);

        $response = $this->get(route('product.show', $product));

        $response->assertStatus(200);
        $response->assertSee('Test Product');
        $response->assertSee('99.99');
    }

    /**
     * Test admin can create product (requires file upload)
     */
    public function test_admin_can_create_product()
    {
        $category = category::create(['name' => 'Electronics']);

        $response = $this->actingAs($this->admin)
            ->post(route('product.store'), [
                'name' => 'New Laptop',
                'description' => 'High performance',
                'price' => 1299.99,
                'quantity' => 5,
                'category_id' => $category->id,
                'images' => [\Illuminate\Http\UploadedFile::fake()->image('image.jpg')]
            ]);

        $this->assertDatabaseHas('products', [
            'name' => 'New Laptop',
            'price' => 1299.99
        ]);
    }

    /**
     * Test non-admin cannot create product
     */
    public function test_non_admin_cannot_create_product()
    {
        $user = User::factory()->create(['is_admin' => false]);
        $category = category::create(['name' => 'Electronics']);

        $response = $this->actingAs($user)
            ->get(route('product.create'));

        // Should get 403 Forbidden or redirect
        $response->assertStatus(403);
    }

    /**
     * Test storefront displays products
     */
    public function test_storefront_displays_products()
    {
        $category = category::create(['name' => 'Electronics']);
        products::create([
            'name' => 'Product 1',
            'price' => 99.99,
            'quantity' => 5,
            'Category_id' => $category->id,
            'total' => 499.95
        ]);
        products::create([
            'name' => 'Product 2',
            'price' => 149.99,
            'quantity' => 3,
            'Category_id' => $category->id,
            'total' => 449.97
        ]);

        $response = $this->get(route('storefront'));

        $response->assertStatus(200);
        $response->assertSee('Product 1');
        $response->assertSee('Product 2');
    }

    /**
     * Test storefront search functionality
     */
    public function test_storefront_search()
    {
        $category = category::create(['name' => 'Electronics']);
        products::create([
            'name' => 'Gaming Laptop',
            'price' => 1299.99,
            'quantity' => 2,
            'Category_id' => $category->id,
            'total' => 2599.98
        ]);

        $response = $this->get(route('storefront') . '?q=gaming');

        $response->assertStatus(200);
        $response->assertSee('Gaming Laptop');
    }
}
```

### Example: Cart Tests

**File:** `tests/Feature/CartTest.php`

```php
<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\products;
use App\Models\category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $product;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $category = category::create(['name' => 'Test']);
        $this->product = products::create([
            'name' => 'Test Product',
            'price' => 99.99,
            'quantity' => 10,
            'Category_id' => $category->id,
            'total' => 999.90
        ]);
    }

    /**
     * Test user can view cart
     */
    public function test_user_can_view_cart()
    {
        $response = $this->actingAs($this->user)
            ->get(route('cart.index'));

        $response->assertStatus(200);
    }

    /**
     * Test user can add product to cart
     */
    public function test_user_can_add_to_cart()
    {
        $response = $this->actingAs($this->user)
            ->post(route('cart.add', $this->product));

        $this->assertDatabaseHas('cart_items', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 1
        ]);
    }

    /**
     * Test user cannot add out-of-stock item
     */
    public function test_user_cannot_add_out_of_stock_item()
    {
        $outOfStock = products::create([
            'name' => 'Out of Stock',
            'price' => 49.99,
            'quantity' => 0,
            'Category_id' => $this->product->Category_id,
            'total' => 0
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('cart.add', $outOfStock));

        $response->assertSessionHasErrors();
        $this->assertDatabaseMissing('cart_items', [
            'product_id' => $outOfStock->id
        ]);
    }

    /**
     * Test user can remove item from cart
     */
    public function test_user_can_remove_from_cart()
    {
        // Add to cart first
        $this->actingAs($this->user)
            ->post(route('cart.add', $this->product));

        $cartItem = $this->user->cartItems()->first();

        $response = $this->actingAs($this->user)
            ->delete(route('cart.remove', $cartItem));

        $this->assertDatabaseMissing('cart_items', [
            'id' => $cartItem->id
        ]);
    }

    /**
     * Test user cannot remove other user's cart item
     */
    public function test_user_cannot_remove_other_users_item()
    {
        // First user adds to cart
        $this->actingAs($this->user)
            ->post(route('cart.add', $this->product));

        $cartItem = $this->user->cartItems()->first();

        // Second user tries to delete
        $other = User::factory()->create();
        $response = $this->actingAs($other)
            ->delete(route('cart.remove', $cartItem));

        $response->assertStatus(403);
    }
}
```

### Example: Authorization Tests

**File:** `tests/Feature/AuthorizationTest.php`

```php
<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test non-admin cannot view user management
     */
    public function test_non_admin_cannot_view_users()
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)
            ->get(route('users.index'));

        $response->assertStatus(403);
    }

    /**
     * Test admin can view user management
     */
    public function test_admin_can_view_users()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)
            ->get(route('users.index'));

        $response->assertStatus(200);
    }

    /**
     * Test non-admin cannot edit user (IDOR protection)
     */
    public function test_non_admin_cannot_edit_user()
    {
        $user1 = User::factory()->create(['is_admin' => false]);
        $user2 = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user1)
            ->get(route('users.edit', $user2));

        $response->assertStatus(403);
    }

    /**
     * Test admin can edit other user
     */
    public function test_admin_can_edit_user()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();

        $response = $this->actingAs($admin)
            ->get(route('users.edit', $user));

        $response->assertStatus(200);
    }
}
```

---

## Testing Commands

### Run All Tests

```bash
# Run all tests
php artisan test

# Run with verbose output
php artisan test --verbose

# Run specific test file
php artisan test tests/Feature/ProductTest.php

# Run specific test method
php artisan test tests/Feature/ProductTest.php --filter=test_user_can_view_product
```

### Test Coverage

```bash
# Generate coverage report
php artisan test --coverage

# Generate HTML coverage report
php artisan test --coverage --coverage-html=coverage/

# View coverage in browser
open coverage/index.html  # Mac
start coverage/index.html # Windows
```

### Parallel Testing

```bash
# Run tests in parallel (faster)
php artisan test --parallel

# Run with custom worker count
php artisan test --parallel --workers=4
```

---

## Test Coverage

### Current Status

| Component | Status | Coverage |
|-----------|--------|----------|
| Product Model | Not started | 0% |
| User Model | Not started | 0% |
| Cart Feature | Not started | 0% |
| Order Feature | Not started | 0% |
| Authorization | Not started | 0% |
| Reviews | Not started | 0% |

### Coverage Goals

| Component | Target |
|-----------|--------|
| Models | 80% |
| Controllers | 70% |
| Business Logic | 85% |
| Overall | 75% |

### View Coverage Report

```bash
# Generate report
php artisan test --coverage --coverage-html=coverage/

# Open in browser
# coverage/index.html shows line-by-line coverage
```

---

## Common Test Patterns

### 1. Testing with Factories

```php
// Create single model
$user = User::factory()->create();

// Create multiple models
$products = products::factory(10)->create();

// Create with specific values
$admin = User::factory()->create(['is_admin' => true]);

// Create without saving
$user = User::factory()->make();
```

### 2. Testing Relationships

```php
// Test user has cart items
$user = User::factory()->create();
$this->assertEquals(0, $user->cartItems()->count());

// Add cart item and test
$user->cartItems()->create(['product_id' => 1, 'quantity' => 2]);
$this->assertEquals(1, $user->cartItems()->count());
```

### 3. Testing Authentication

```php
// Act as user
$response = $this->actingAs($user)->get('/profile');

// Assert authenticated
$this->assertAuthenticatedAs($user);

// Assert guest
$this->assertGuest();
```

### 4. Testing Database

```php
// Assert database has record
$this->assertDatabaseHas('products', ['name' => 'Laptop']);

// Assert database missing record
$this->assertDatabaseMissing('products', ['name' => 'Deleted']);

// Count records
$count = Product::count();
$this->assertEquals(5, $count);
```

### 5. Testing File Uploads

```php
$response = $this->post('/products', [
    'image' => \Illuminate\Http\UploadedFile::fake()->image('image.jpg')
]);

// Assert file exists
Storage::disk('public')->assertExists('products/image.jpg');
```

### 6. Testing JSON API

```php
$response = $this->postJson('/api/products', [
    'name' => 'Product',
    'price' => 99.99
]);

$response->assertStatus(201);
$response->assertJson(['success' => true]);
$response->assertJsonStructure(['data' => ['id', 'name', 'price']]);
```

### 7. Testing Email

```php
Mail::fake();

// Perform action that sends email
$this->post('/checkout', [...]);

// Assert email sent
Mail::assertSent(OrderConfirmed::class);
```

### 8. Testing Validation

```php
$response = $this->post('/products', [
    'name' => '',  // Missing required field
    'price' => 'invalid'  // Invalid format
]);

$response->assertSessionHasErrors(['name', 'price']);
```

---

## CI/CD Integration

### GitHub Actions Example

**File:** `.github/workflows/tests.yml`

```yaml
name: Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest

    services:
      mysql:
        image: mysql:8.0
        env:
          MYSQL_DATABASE: tech_store_test
          MYSQL_ROOT_PASSWORD: root
        options: >-
          --health-cmd="mysqladmin ping"
          --health-interval=10s
          --health-timeout=5s
          --health-retries=3

    steps:
    - uses: actions/checkout@v3

    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.1'
        extensions: mbstring, xml, json

    - name: Install dependencies
      run: composer install --no-ansi --no-interaction --no-progress --no-scripts

    - name: Generate key
      run: php artisan key:generate --env=testing

    - name: Run migrations
      run: php artisan migrate --env=testing

    - name: Run tests
      run: php artisan test

    - name: Upload coverage
      uses: codecov/codecov-action@v3
      with:
        files: ./coverage.xml
```

---

## Best Practices

1. **Test names are descriptive**: `test_user_can_add_product_to_cart`
2. **Use Arrange-Act-Assert pattern**
3. **One assertion per test when possible**
4. **Use factories for test data**
5. **Test happy path and error cases**
6. **Mock external services** (email, payment)
7. **Run tests before committing**
8. **Keep tests fast** (< 5 seconds preferred)
9. **Test behavior, not implementation**
10. **Refactor tests like production code**

---

## Running Your First Test

```bash
# Create new test
php artisan make:test ProductTest --feature

# Run the test
php artisan test tests/Feature/ProductTest.php

# Run with coverage
php artisan test tests/Feature/ProductTest.php --coverage
```

---

## Resources

- [Laravel Testing Documentation](https://laravel.com/docs/9.x/testing)
- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [Testing Best Practices](https://phpunit.de/documentation.html)

---

Last Updated: February 16, 2026
