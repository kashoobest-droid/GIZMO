# API Documentation

Complete reference for all application endpoints including request/response formats, authentication requirements, and examples.

## Table of Contents

1. [Authentication](#authentication)
2. [Products](#products)
3. [Cart](#cart)
4. [Orders](#orders)
5. [Reviews](#reviews)
6. [Favorites](#favorites)
7. [Users](#users) *(Admin only)*
8. [Profile](#profile)
9. [Response Formats](#response-formats)
10. [Error Handling](#error-handling)

---

## Authentication

All operations are either public or require authentication via Laravel session.

### Session-Based Auth
- Login: `POST /login`
- Logout: `POST /logout`
- Register: `POST /register`

**Admin operations require `is_admin=1` flag on user account.**

---

## Products

### GET `/products` (Product Listing - Admin)
List all products in the system (admin dashboard).

**Authentication:** Required (admin only)

**Response:**
```php
view('product_index', [
  'products' => Collection<Product>
])
```

**Fields per product:**
- id, name, description, price, quantity
- total, Category_id, created_at, updated_at
- Loaded: images, category, offer

---

### GET `/products/create` (Create Form - Admin)
Show form to add new product.

**Authentication:** Required (admin only)

**Response:**
```php
view('product_add', [
  'categories' => Collection<Category>
])
```

---

### POST `/products` (Create Product - Admin)
Create new product with images.

**Authentication:** Required (admin only)

**Request:**
```
Content-Type: multipart/form-data

name (required): "Gaming Laptop"
description (optional): "High-performance gaming laptop"
price (required): 1299.99
quantity (required): 5
category_id (required): 1
images[] (required): [file, file, ...] (1-6 images max)
```

**Validation:**
- name: required, string, max 255
- price: required, numeric, >= 0
- quantity: required, integer, >= 0
- category_id: required, exists in categories
- images[]: required, image type, JPEG/PNG/JPG/GIF/SVG

**Response (Success):**
```
Redirect to /products
Header: success = "Product added successfully!"
```

**Response (Error):**
```
Redirect back with validation errors
'images' => "At least one valid image is required."
```

**Image Storage:**
- Location: `public/upload/`.
- Filename: `prodimg_{uniqid}.ext`
- Max 6 images per product

---

### GET `/products/{id}` (View Product - Public)
Display single product with reviews and related items.

**Authentication:** Not required

**Route:** `product.show`

**Parameters:**
- `id` (integer): Product ID

**Response:**
```php
view('product_show', [
  'product' => Product (with images, category, offer, reviews),
  'relatedProducts' => Collection<Product> (4 items from same category)
])
```

**Related products:**
- Same category, max 4 items
- Ordered by ID descending (newest first)
- Excludes current product

---

### GET `/products/{id}/edit` (Edit Form - Admin)
Show product edit form.

**Authentication:** Required (admin only)

**Route:** `product.edit`

**Parameters:**
- `id` (integer): Product ID

**Response:**
```php
view('product_update', [
  'product' => Product (with current images),
  'categories' => Collection<Category>
])
```

---

### PUT/PATCH `/products/{id}` (Update Product - Admin)
Update product details and manage images.

**Authentication:** Required (admin only)

**Route:** `product.update`

**Parameters:**
- `id` (integer): Product ID

**Request:**
```
Content-Type: multipart/form-data

name (required): "Updated Name"
description (optional): "New description"
price (required): 999.99
quantity (required): 10
category_id (required): 2
images[] (optional): [file, file, ...] (up to 6 total)
remove_images[] (optional): [image_id_1, image_id_2, ...]
```

**Behavior:**
- Removes specified images first
- Adds new images (capped at 6 total)
- Enforces minimum 1 image
- Auto-deletes physical files when removing images
- Triggers stock notification if product goes from out-of-stock → in-stock

**Response (Success):**
```
Redirect to /products
Header: success = "Product updated successfully!"
```

---

### DELETE `/products/{id}` (Delete Product - Admin)
Permanently remove product from catalog.

**Authentication:** Required (admin only)

**Route:** `product.destroy`

**Parameters:**
- `id` (integer): Product ID

**Response (Success):**
```
Redirect to /products
Header: success = "Product deleted successfully!"
```

**Cascade behavior:**
- Deletes product images
- Deletes all cart items with this product
- Deletes all favorites of this product
- Deletes reviews and reactions
- Deletes offer links
- Stock notifications removed

---

### GET `/` (Storefront - Public)
Display user-facing product catalog with search, filtering, sorting.

**Authentication:** Not required (optional for cart/favorite features)

**Route:** `storefront`

**Query Parameters:**
```
q (optional): "gaming laptop" - Search term
category (optional): 1 - Category ID
sort (optional): "newest|price_asc|price_desc|name_asc|name_desc" (default: newest)
page (optional): 2 - Pagination page
```

**Examples:**
- `GET / - Homepage with newest products`
- `GET /?q=laptop - Search results`
- `GET /?category=1 - Category filter`
- `GET /?sort=price_asc&page=2 - Sort and paginate`

**Response:**
```php
view('gizmo_store', [
  'products' => Paginator<Product> (12 per page),
  'categories' => Collection<Category>,
  'q' => ?string,
  'categoryId' => ?integer,
  'categoryForBreadcrumb' => ?Category,
  'sort' => 'newest',
  'cartProductIds' => array (flipped for O(1) lookup),
  'favoriteProductIds' => array,
  'cartQuantities' => array,
  'cartCount' => integer
])
```

**Search behavior:**
- Searches product name and description with LIKE
- Also searches category names
- Case-insensitive

**Sorting:**
- newest: Order by ID descending
- price_asc: Lowest price first
- price_desc: Highest price first
- name_asc: A-Z alphabetical
- name_desc: Z-A reverse alphabetical

---

## Cart

### GET `/cart` (View Cart)
Display user's shopping cart.

**Authentication:** Required

**Route:** `cart.index`

**Response:**
```php
view('cart', [
  'cartItems' => Collection<CartItem> (with product.images, product.category),
  'cartCount' => integer (total items)
])
```

---

### POST `/cart/add/{product_id}` (Add to Cart)
Add product to user's cart.

**Authentication:** Required

**Route:** `cart.add`

**Parameters:**
- `product_id` (integer): Product to add

**Request:**
```
Content-Type: application/x-www-form-urlencoded

quantity (optional): 1 (defaults to 1)
```

**Behavior:**
- Validates product is in stock
- Caps quantity at available stock
- If item already in cart, adds quantity
- Minimum quantity: 1, Maximum: product.quantity

**Response (Stock available):**
```json
HTML: Redirect with message: "Product added to cart!"
JSON: {"success": true, "message": "Product added to cart!"}
```

**Response (Out of stock):**
```json
HTML: Redirect back with error: "This product is out of stock."
JSON: {"error": "This product is out of stock."}
```

---

### DELETE `/cart/{cart_item_id}` (Remove from Cart)
Remove item from user's cart.

**Authentication:** Required

**Route:** `cart.remove`

**Parameters:**
- `cart_item_id` (integer): CartItem ID to remove

**Authorization:** Must be own cart item, or 403 Forbidden

**Response:**
```json
HTML: Redirect with message: "Item removed from cart."
JSON: {"success": true}
```

---

### PUT/PATCH `/cart/{cart_item_id}/quantity` (Update Quantity)
Change quantity of item in cart.

**Authentication:** Required

**Route:** `cart.updateQuantity`

**Parameters:**
- `cart_item_id` (integer): CartItem ID

**Request:**
```
quantity (optional): 5 (defaults to 1)
```

**Behavior:**
- Quantity bounded: min 1, max product.quantity
- Updates database
- Returns new quantity in JSON response

**Response:**
```json
HTML: Redirect back
JSON: {"success": true, "quantity": 5}
```

---

## Orders

### GET `/checkout` (Checkout Page - Public)
Display checkout form with cart review and payment.

**Authentication:** Required

**Route:** `order.checkout`

**Response:**
```php
view('checkout', [
  'cartItems' => Collection<CartItem>,
  'couponDiscount' => float,
  'total' => float
])
```

---

### POST `/orders` (Create Order)
Submit order with payment and address details.

**Authentication:** Required

**Route:** `order.store`

**Request:**
```
Content-Type: application/x-www-form-urlencoded

first_name (required): "John"
last_name (required): "Doe"
email (required): "john@example.com"
phone (required): "1234567890"
address (required): "123 Main St"
city (required): "New York"
country (required): "USA"
coupon_code (optional): "SAVE10"
card_number (required): "4111111111111111"
card_expiry (required): "12/25"
card_cvv (required): "123"
```

**Processing steps:**
1. Validates all fields
2. Verifies stock availability
3. Formats phone number
4. Applies coupon discount (if provided)
5. Calculates total: (sum(item_price × quantity) - coupon) + tax
6. Processes payment (Stripe integration)
7. Creates order with items (database transaction)
8. Sends order confirmation email
9. Clears cart
10. Updates product stock

**Response (Success):**
```
Redirect to /orders/{order_id}
```

**Response (Validation error):**
```
Redirect back with validation errors
```

**Response (Payment failed):**
```
Redirect back with payment error message
```

---

### GET `/orders` (Order History)
List user's orders.

**Authentication:** Required

**Route:** `order.index`

**Response:**
```php
view('orders.index', [
  'orders' => Paginator<Order> (10 per page),
  'cartCount' => integer
])
```

**Pagination:** 10 orders per page

---

### GET `/orders/{order_id}` (View Order)
Display order details and items.

**Authentication:** Required

**Route:** `order.show`

**Parameters:**
- `order_id` (integer): Order ID

**Authorization:** Must be order owner or 403 Forbidden (IDOR protection)

**Response:**
```php
view('order.show', [
  'order' => Order (with items, user),
  'cartCount' => integer
])
```

---

### GET `/admin/orders` (Order Management - Admin)
List all orders with statistics.

**Authentication:** Required (admin only)

**Route:** `order.adminIndex`

**Response:**
```php
view('admin.orders', [
  'orders' => Paginator<Order> (20 per page),
  'stats' => [
    'totalOrders' => integer,
    'totalRevenue' => float,
    'averageOrderValue' => float,
    'pendingCount' => integer
  ]
])
```

**Statistics:**
- Total orders in system
- Sum of all order totals
- Average order value
- Number of pending orders

---

### PUT/PATCH `/admin/orders/{order_id}/status` (Update Status - Admin)
Change order status and send notifications.

**Authentication:** Required (admin only)

**Route:** `order.updateStatus`

**Parameters:**
- `order_id` (integer): Order ID

**Request:**
```
status (required): "pending|processing|shipped|delivered|cancelled"
```

**Valid statuses:**
- pending: Order received, awaiting processing
- processing: Order being packed/prepared
- shipped: Order in transit
- delivered: Order received by customer (final)
- cancelled: Order cancelled, refund issued (final)

**Response:**
```
Redirect back with success: "Order status updated."
```

---

## Reviews

### POST `/products/{product_id}/reviews` (Create Review)
Submit review for purchased product.

**Authentication:** Required

**Route:** `review.store`

**Parameters:**
- `product_id` (integer): Product being reviewed

**Request:**
```
rating (required): 1-5
comment (optional): "Great product!" (max 1000 chars)
```

**Authorization:**
- Must be verified buyer (hasPurchasedBy check)
- Returns error if user hasn't purchased product

**Business logic:**
- updateOrCreate pattern: one review per user per product
- If user already reviewed: update existing review
- If new: create new review

**Response (Success):**
```
Redirect back with success: "Thank you for your review!"
```

**Response (Not verified buyer):**
```
Redirect back with error: "Only verified buyers can leave reviews. Please purchase this product first."
```

---

### PUT/PATCH `/reviews/{review_id}` (Update Review)
Edit own review.

**Authentication:** Required

**Route:** `review.update`

**Parameters:**
- `review_id` (integer): Review ID

**Request:**
```
rating (required): 1-5
comment (optional): "Updated comment" (max 1000)
```

**Authorization:** Must be review author or error

**Response:**
```
Redirect back with success: "Your review has been updated!"
```

---

### DELETE `/reviews/{review_id}` (Delete Review)
Remove own review.

**Authentication:** Required

**Route:** `review.destroy`

**Parameters:**
- `review_id` (integer): Review ID

**Authorization:** Must be review author or error

**Response:**
```
Redirect back with success: "Your review has been deleted!"
```

**Cascade:** All ReviewReaction records deleted

---

### POST `/reviews/{review_id}/react` (React to Review)
Mark review as helpful or not helpful.

**Authentication:** Required

**Route:** `review.react`

**Parameters:**
- `review_id` (integer): Review being reacted to

**Request:**
```
reaction_type (required): "helpful|not_helpful"
```

**Behavior (3 cases):**
1. No existing reaction → Create new
   - Response: "Thank you for your feedback!"
2. Same reaction exists → Remove (toggle)
   - Response: "Reaction removed!"
3. Different reaction exists → Update it
   - Response: "Reaction updated!"

**Response:**
```
Redirect back with success message
```

---

## Favorites

### GET `/favorites` (View Favorites)
Display user's favorited products.

**Authentication:** Required

**Route:** `favorite.index`

**Response:**
```php
view('favorites', [
  'favorites' => Collection<Favorite> (with product.images, product.category),
  'cartCount' => integer
])
```

---

### POST `/favorites/add/{product_id}` (Add to Favorites)
Add product to favorites (idempotent).

**Authentication:** Required

**Route:** `favorite.add`

**Parameters:**
- `product_id` (integer): Product to favorite

**Response:**
```json
HTML: Redirect with message: "Added to favorites!"
JSON: {"success": true, "message": "Added to favorites!"}
```

---

### POST `/favorites/remove/{product_id}` (Remove from Favorites)
Remove product from favorites.

**Authentication:** Required

**Route:** `favorite.remove`

**Parameters:**
- `product_id` (integer): Product to un-favorite

**Response:**
```json
HTML: Redirect with message: "Removed from favorites."
JSON: {"success": true, "message": "Removed from favorites."}
```

---

### POST `/favorites/toggle/{product_id}` (Toggle Favorite)
Add if not favorited, remove if already favorited.

**Authentication:** Required

**Route:** `favorite.toggle`

**Parameters:**
- `product_id` (integer): Product to toggle

**Response:**
```json
HTML: Redirect with appropriate message
JSON: {
  "success": true,
  "added": true|false,
  "message": "Added to favorites!" | "Removed from favorites!"
}
```

**Advantage:** JSON response includes `added` flag for UI updates

---

## Users

### GET `/users` (User Management - Admin)
List all users in system.

**Authentication:** Required (admin only)

**Route:** `users.index`

**Response:**
```php
view('users.index', [
  'users' => Collection<User>
])
```

---

### GET `/users/{user_id}/edit` (Edit User - Admin)
Show user edit form.

**Authentication:** Required (admin only)

**Route:** `users.edit`

**Parameters:**
- `user_id` (integer): User ID

**Authorization:** Must be admin (UserPolicy)

**Response:**
```php
view('users.edit', [
  'user' => User,
  'categories' => Collection<Category>
])
```

---

### PUT/PATCH `/users/{user_id}` (Update User - Admin)
Modify user account details and admin role.

**Authentication:** Required (admin only)

**Route:** `users.update`

**Parameters:**
- `user_id` (integer): User ID

**Authorization:** Must be admin (UserPolicy)

**Request:**
```
name (required): "John Doe"
email (required): "john@example.com"
phone (optional): "1234567890"
country (optional): "USA"
street_name (optional): "123 Main St"
building_name (optional): "Building A"
floor_apartment (optional): "Apt 5"
landmark (optional): "Near Park"
city_area (optional): "Downtown"
is_admin (optional): on|off (checkbox)
```

**Validation:**
- name: required, string, max 255
- email: required, unique (excluding this user), max 255
- Other address fields: optional, max 255 each

**Admin role:**
- Checkbox returns 'on' if checked
- Converted to 1 if checked, 0 if unchecked
- Allows admins to grant/revoke admin role

**Response:**
```
Redirect to users.index with success: "User updated successfully."
```

---

### DELETE `/users/{user_id}` (Delete User - Admin)
Permanently remove user account.

**Authentication:** Required (admin only)

**Route:** `users.destroy`

**Parameters:**
- `user_id` (integer): User ID to delete

**Authorization:** Must be admin (UserPolicy)

**Response:**
```
Redirect to users.index with success: "User deleted successfully."
```

**Cascade deletions:**
- Cart items
- Favorites
- Orders (possibly kept for history?)
- Reviews

---

## Profile

### GET `/profile` (View Profile)
Show current user's profile edit page.

**Authentication:** Required

**Route:** `profile.edit`

**Response:**
```php
view('profile', [
  'user' => Auth::user()
])
```

---

### PUT/PATCH `/profile` (Update Profile)
Modify current user's personal info and avatar.

**Authentication:** Required

**Route:** `profile.update`

**Request:**
```
Content-Type: multipart/form-data

name (required): "John Doe"
email (required): "john@example.com"
phone (optional): "1234567890"
country (optional): "USA"
street_name (optional): "123 Main St"
building_name (optional)
floor_apartment (optional)
landmark (optional)
city_area (optional)
avatar (optional): image file (JPEG/PNG/JPG/GIF/SVG, max 2MB)
```

**Validation:**
- name: required, string, max 255
- email: required, unique (excluding current user), max 255
- avatar: optional, image, JPEG/PNG/JPG/GIF/SVG, max 2048 KB

**Avatar processing:**
1. Validates image type
2. Deletes previous avatar if exists
3. Creates directory if missing
4. Generates filename: avatar_{user_id}_{uniqid}.ext
5. Stores in public/upload/avatars/

**Response:**
```
Redirect to profile.edit with success: "Profile updated successfully."
```

---

### PUT/PATCH `/profile/password` (Update Password)
Change current user's password.

**Authentication:** Required

**Route:** `profile.updatePassword`

**Request:**
```
current_password (required): "OldPassword123"
password (required): "NewPassword456"
password_confirmation (required): "NewPassword456"
```

**Password validation:**
- current_password: Must match current bcrypt hash
- password: Required, must be confirmed, must match rules:
  * Minimum 8 characters
  * Must contain uppercase letters
  * Must contain lowercase letters
  * Must contain numbers

**Verification:**
- Hash::check() verifies current password
- Password and confirmation must match
- Invalid current password returns error without changing

**Response (Success):**
```
Redirect to profile.edit with success: "Password updated successfully."
```

**Response (Wrong current password):**
```
Redirect back with error: "The current password is incorrect."
```

---

## Response Formats

### Success Response (HTML)
```
HTTP/1.1 302 Found
Location: /path
Set-Cookie: success=gg0m8vgjjb9cg98cp

Redirect with flashed message
```

### Success Response (JSON)
```json
{
  "success": true,
  "message": "Operation completed successfully"
}
```

### Validation Error Response (HTML)
```
HTTP/1.1 302 Found
Location: /back
Set-Cookie: errors=...

Form validation errors preserved for re-display
```

### Validation Error Response (JSON)
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password must be at least 8 characters."]
  }
}
```

### Authorization Error
```
HTTP/1.1 403 Forbidden

'You are not authorized to perform this action.'
or JSON:
{
  "message": "This action is unauthorized."
}
```

### Not Found Error
```
HTTP/1.1 404 Not Found

'Page or resource not found'
or JSON:
{
  "message": "No query results found"
}
```

---

## Error Handling

### Common HTTP Status Codes

| Code | Meaning | Example |
|------|---------|---------|
| 200 | Success (GET) | Product loaded successfully |
| 201 | Created (POST) | Product created |
| 302 | Redirect | Form submitted successfully, redirecting |
| 400 | Bad Request | Invalid query parameters |
| 403 | Forbidden | User lacks authorization (IDOR protection) |
| 404 | Not Found | Product ID doesn't exist |
| 422 | Unprocessable Entity | Validation failed |
| 500 | Server Error | Database connection failed |

### Common Validation Errors

```json
{
  "name": ["The name field is required."],
  "email": ["The email has already been taken."],
  "price": ["The price must be a number."],
  "images": ["At least one valid image is required."]
}
```

### Authorization Errors

```
IDOR Protection:
- Accessing /orders/123 when you didn't create it → 403
- Editing user/5 when you're not admin → 403
- Deleting review/10 when you didn't write it → 403
```

---

## Rate Limiting

Currently not implemented. Recommended for production:
- Login attempts: 5 per minute per IP
- API requests: 60 per minute per user
- Checkout: 1 per minute per user

---

## CORS

Not currently enabled (session-based only). To enable REST API:
- Configure CORS in `config/cors.php`
- Add allowed origins
- Configure Sanctum for API token authentication

---

## Best Practices

### Request/Response Patterns

1. **Always validate input** - All controllers validate requests
2. **Use transactions** - Order creation uses DB transaction
3. **Check authorization** - Every modifying action has checks
4. **Eager load relations** - Prevent N+1 query problems
5. **Hash sensitive data** - Passwords hashed with Bcrypt
6. **Redirect after POST** - Prevent resubmission (POST-Redirect-GET pattern)

### JSON API Support

Methods check `request()->expectsJson()`:
- If true → JSON response
- If false → HTML redirect

Allows same endpoint to serve both HTML and JSON clients.

---

## Examples

### Complete Checkout Flow
```
1. GET /checkout - Load form
2. POST /orders - Submit form with card details
3. Validate all fields
4. Process payment
5. Create order and items
6. Send confirmation email
7. Redirect to /orders/{id}
```

### Product Search and Filter
```
GET /?q=laptop&category=1&sort=price_asc&page=2

Response: 12 products (page 2)
Showing laptops in category 1
Sorted by price ascending
```

### Review Management Flow
```
1. User purchases product
2. User submits rating and comment
3. Review created via updateOrCreate
4. Other users mark review as helpful
5. Reviewer can edit or delete their review
```

---

Last Updated: February 16, 2026
