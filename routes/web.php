<?php

use App\Http\Controllers\categoryController;
use App\Http\Controllers\teststoreController;
use App\Http\Controllers\productsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\OrderTrackingController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\StockNotificationController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\LocaleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [productsController::class, 'storefront']);

Route::get('kashoo', function () {
    return 'Access denied';
});

// Welcome page removed per project rename to gizmo_store

Route::get('ping', [teststoreController::class,'test']);

// Language/Locale routes
Route::get('/locale/{locale}', [LocaleController::class, 'setLocale'])->name('locale.set');

// Currency routes
Route::get('/currency/{currency}', [\App\Http\Controllers\CurrencyController::class, 'set'])->name('currency.set');

// Public pages
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');
Route::get('/faq', fn () => view('faq'))->name('faq');
Route::get('/track-order', [OrderTrackingController::class, 'show'])->name('order.track.show');
Route::post('/track-order', [OrderTrackingController::class, 'track'])->name('order.track');
Route::post('/newsletter', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// Password reset (forgot password)
Route::get('/password/forgot', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->middleware('throttle:5,1')->name('password.email');
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->middleware('throttle:5,1')->name('password.update');

// Admin Routes (Protected)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/orders', [OrderController::class, 'adminIndex'])->name('orders.index');
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    });
    Route::resource('category', categoryController::class);
    Route::resource('product', productsController::class);
    Route::resource('offer', OfferController::class);
    Route::resource('users', UserController::class)->except(['create', 'store', 'show']);
    Route::get('admin/coupons', [CouponController::class, 'index'])->name('admin.coupons.index');
    Route::get('admin/coupons/create', [CouponController::class, 'create'])->name('admin.coupons.create');
    Route::post('admin/coupons', [CouponController::class, 'store'])->name('admin.coupons.store');
    Route::delete('admin/coupons/{coupon}', [CouponController::class, 'destroy'])->name('admin.coupons.destroy');
});

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Cart routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
    Route::patch('/cart/update/{cartItem}', [CartController::class, 'updateQuantity'])->name('cart.update');

    // Order routes
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // Favorites routes
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/add/{product}', [FavoriteController::class, 'add'])->middleware('auth')->name('favorites.add');
    Route::delete('/favorites/remove/{product}', [FavoriteController::class, 'remove'])->middleware('auth')->name('favorites.remove');
    Route::post('/favorites/toggle/{product}', [FavoriteController::class, 'toggle'])->middleware('auth')->name('favorites.toggle');
});

// Stock notification (guest or auth)
Route::post('/product/{product}/notify-stock', [StockNotificationController::class, 'store'])->name('stock-notify.store');

// Review (auth only)
Route::post('/product/{product}/review', [ReviewController::class, 'store'])->name('review.store')->middleware('auth');
Route::put('/review/{review}', [ReviewController::class, 'update'])->name('review.update')->middleware('auth');
Route::delete('/review/{review}', [ReviewController::class, 'destroy'])->name('review.destroy')->middleware('auth');
Route::post('/review/{review}/react', [ReviewController::class, 'react'])->name('review.react')->middleware('auth');

// Public product view only
Route::get('/product/{product}', [productsController::class, 'show'])->name('product.show');
