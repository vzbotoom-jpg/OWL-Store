<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;

// ===== PUBLIC =====
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/category/{slug}', [ProductController::class, 'category'])->name('products.category');
Route::view('/about', 'pages.about')->name('about');
Route::view('/panduan', 'pages.panduan')->name('panduan');
Route::view('/faq', 'pages.faq')->name('faq');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

// ===== CART (Guest & User) =====
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.apply-coupon');
Route::post('/cart/remove-coupon', [CartController::class, 'removeCoupon'])->name('cart.remove-coupon');

// ===== CHECKOUT =====
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::post('/checkout/shipping', [CheckoutController::class, 'getShipping'])->name('checkout.shipping');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/payment/{order}', [CheckoutController::class, 'payment'])->name('checkout.payment');
    Route::post('/checkout/confirm-payment/{order}', [CheckoutController::class, 'confirmPayment'])->name('checkout.confirm-payment');
});

// ===== AUTH =====
Route::get('/admin/login', [LoginController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [LoginController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [LoginController::class, 'logout'])->name('admin.logout');

// ===== USER AUTH =====
Route::get('/login', [App\Http\Controllers\Auth\UserLoginController::class, 'showLogin'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\UserLoginController::class, 'login'])->name('login.post');
Route::post('/logout', [App\Http\Controllers\Auth\UserLoginController::class, 'logout'])->name('logout');
Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegister'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register'])->name('register.post');

// ===== PASSWORD RESET =====
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);
    
    $status = Password::sendResetLink(
        $request->only('email')
    );
    
    return $status === Password::RESET_LINK_SENT
        ? back()->with(['status' => __($status)])
        : back()->withErrors(['email' => __($status)]);
})->name('password.email');

// ===== USER DASHBOARD =====
Route::prefix('user')->name('user.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\User\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [App\Http\Controllers\User\ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\User\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/settings', [App\Http\Controllers\User\SettingsController::class, 'index'])->name('settings');
    Route::get('/orders', [App\Http\Controllers\User\OrderController::class, 'index'])->name('orders');
    Route::get('/orders/{id}', [App\Http\Controllers\User\OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/cancel', [App\Http\Controllers\User\OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{id}/confirm', [App\Http\Controllers\User\OrderController::class, 'confirmReceived'])->name('orders.confirm');
    Route::get('/chat', [App\Http\Controllers\User\ChatController::class, 'index'])->name('chat');
    Route::get('/chat/{id}', [App\Http\Controllers\User\ChatController::class, 'room'])->name('chat.room');
    Route::post('/chat/{id}/send', [App\Http\Controllers\User\ChatController::class, 'sendMessage'])->name('chat.send');
    Route::post('/chat/start', [App\Http\Controllers\User\ChatController::class, 'startChat'])->name('chat.start');
    Route::get('/addresses', [App\Http\Controllers\User\AddressController::class, 'index'])->name('addresses');
    Route::get('/addresses/{id}/edit', [App\Http\Controllers\User\AddressController::class, 'edit'])->name('addresses.edit');
    Route::post('/addresses', [App\Http\Controllers\User\AddressController::class, 'store'])->name('addresses.store');
    Route::put('/addresses/{id}', [App\Http\Controllers\User\AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/addresses/{id}', [App\Http\Controllers\User\AddressController::class, 'destroy'])->name('addresses.destroy');
    Route::patch('/addresses/{id}/default', [App\Http\Controllers\User\AddressController::class, 'setDefault'])->name('addresses.default');
    Route::get('/security', [App\Http\Controllers\User\SecurityController::class, 'index'])->name('security');
    Route::post('/security/password', [App\Http\Controllers\User\SecurityController::class, 'changePassword'])->name('security.password');
    Route::post('/security/two-factor/enable', [App\Http\Controllers\User\SecurityController::class, 'enableTwoFactor'])->name('security.two-factor.enable');
    Route::post('/security/two-factor/disable', [App\Http\Controllers\User\SecurityController::class, 'disableTwoFactor'])->name('security.two-factor.disable');
    Route::get('/wishlist', [App\Http\Controllers\User\WishlistController::class, 'index'])->name('wishlist');
    Route::post('/wishlist/add', [App\Http\Controllers\User\WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/wishlist/{id}', [App\Http\Controllers\User\WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::post('/wishlist/move-to-cart/{id}', [App\Http\Controllers\User\WishlistController::class, 'moveToCart'])->name('wishlist.move-to-cart');
    Route::delete('/wishlist/clear', [App\Http\Controllers\User\WishlistController::class, 'clear'])->name('wishlist.clear');
    Route::post('/wishlist/bulk-remove', [App\Http\Controllers\User\WishlistController::class, 'bulkRemove'])->name('wishlist.bulk-remove');
    Route::get('/wishlist/check/{productId}', [App\Http\Controllers\User\WishlistController::class, 'check'])->name('wishlist.check');
});

// ===== ADMIN =====
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Products
    Route::resource('products', AdminProductController::class);
    Route::patch('/products/{id}/toggle-status', [AdminProductController::class, 'toggleStatus'])->name('products.toggle-status');
    Route::get('/products/export', [AdminProductController::class, 'export'])->name('products.export');
    
    // Categories
    Route::resource('categories', CategoryController::class);
    Route::post('/categories/reorder', [CategoryController::class, 'reorder'])->name('categories.reorder');
    
    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
    Route::patch('/orders/{id}/resi', [OrderController::class, 'updateResi'])->name('orders.resi');
    Route::patch('/orders/{id}/mark-paid', [OrderController::class, 'markAsPaid'])->name('orders.mark-paid');
    Route::get('/orders/{id}/print', [OrderController::class, 'printInvoice'])->name('orders.print');
    Route::get('/orders/{id}/export', [OrderController::class, 'exportOrder'])->name('orders.export');
    
    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::patch('/users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::get('/users/export', [UserController::class, 'export'])->name('users.export');
    
    // Reviews
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::patch('/reviews/{id}/toggle-status', [ReviewController::class, 'toggleStatus'])->name('reviews.toggle-status');
    Route::post('/reviews/{id}/reply', [ReviewController::class, 'reply'])->name('reviews.reply');
    
    // Reports
    Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/sales-data', [ReportController::class, 'salesData'])->name('reports.sales-data');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
    Route::get('/reports/top-products-export', [ReportController::class, 'exportTopProducts'])->name('reports.top-products-export');
    
    // Discounts (Coupons)
    Route::get('/discounts', [DiscountController::class, 'index'])->name('discounts.index');
    Route::get('/discounts/data', [DiscountController::class, 'data'])->name('discounts.data');
    Route::get('/discounts/create', [DiscountController::class, 'create'])->name('discounts.create');
    Route::post('/discounts', [DiscountController::class, 'store'])->name('discounts.store');
    Route::get('/discounts/{id}/edit', [DiscountController::class, 'edit'])->name('discounts.edit');
    Route::put('/discounts/{id}', [DiscountController::class, 'update'])->name('discounts.update');
    Route::delete('/discounts/{id}', [DiscountController::class, 'destroy'])->name('discounts.destroy');
    
    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::put('/settings/payment', [SettingController::class, 'updatePayment'])->name('settings.payment.update');
    Route::put('/settings/shipping', [SettingController::class, 'updateShipping'])->name('settings.shipping.update');
    Route::put('/settings/notification', [SettingController::class, 'updateNotification'])->name('settings.notification.update');
    Route::put('/settings/seo', [SettingController::class, 'updateSeo'])->name('settings.seo.update');
    
    // Bank Accounts
    Route::get('/banks', [SettingController::class, 'banks'])->name('banks.index');
    Route::post('/banks', [SettingController::class, 'storeBank'])->name('banks.store');
    Route::delete('/banks', [SettingController::class, 'destroyBank'])->name('banks.destroy');
});