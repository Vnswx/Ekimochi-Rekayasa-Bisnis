<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DashboardController;
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

Route::get('/', [DashboardController::class, 'index'])->name('home');

// Guest routes (only accessible when not authenticated)
Route::middleware('guest')->group(function () {
    // Register
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    // Google OAuth
    Route::get('/auth/google', [App\Http\Controllers\Auth\GoogleController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [App\Http\Controllers\Auth\GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');

    // Forgot Password
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    // Reset Password
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Public Catalog Routes
Route::get('/products', [App\Http\Controllers\ProductCatalogController::class, 'index'])->name('catalog.index');
Route::get('/products/{product}', [App\Http\Controllers\ProductCatalogController::class, 'show'])->name('catalog.show');

// Public Dashboard (Landing Page)
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// FAQ Page
Route::view('/faq', 'faq')->name('faq');

// About Page
Route::view('/about', 'about')->name('about');

// Outlets Page
Route::get('/outlets', function() {
    $outlets = \App\Models\Outlet::active()->with('tables')->get();
    return view('outlets.index', compact('outlets'));
})->name('outlets.index');

// Cart Routes (public)
Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::put('/cart/update/{product}', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{product}', [App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [App\Http\Controllers\CartController::class, 'clear'])->name('cart.clear');
Route::delete('/cart/custom/{index}', [App\Http\Controllers\CartController::class, 'removeCustomPackage'])->name('cart.removeCustom');

// Package Routes (public)
Route::get('/packages', [App\Http\Controllers\PackageController::class, 'index'])->name('packages.index');
Route::get('/packages/custom/builder', [App\Http\Controllers\PackageController::class, 'customBuilder'])->name('packages.custom');
Route::post('/packages/custom/add-to-cart', [App\Http\Controllers\PackageController::class, 'addCustomToCart'])->name('packages.addCustomToCart');
Route::get('/packages/{package}', [App\Http\Controllers\PackageController::class, 'show'])->name('packages.show');
Route::post('/packages/{package}/add-to-cart', [App\Http\Controllers\PackageController::class, 'addToCart'])->name('packages.addToCart');

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/email', [ProfileController::class, 'updateEmail'])->name('profile.email');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/photo', [ProfileController::class, 'uploadPhoto'])->name('profile.photo.upload');
    Route::delete('/profile/photo', [ProfileController::class, 'deletePhoto'])->name('profile.photo.delete');

    // Checkout
    Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');
    
    // QR Scan for Dine In
    Route::get('/scan/{qrToken}', function($qrToken) {
        $table = App\Models\OutletTable::where('qr_token', $qrToken)
            ->where('is_active', true)
            ->with('outlet')
            ->first();
        
        if (!$table) {
            return redirect()->route('home')->with('error', 'QR Code tidak valid');
        }
        
        // Redirect ke checkout dengan QR token
        return redirect()->route('checkout.index', ['qr' => $qrToken]);
    })->name('scan.table');

    // Payment
    Route::get('/payment/{order}', [App\Http\Controllers\PaymentController::class, 'create'])->name('payment.create');
    Route::get('/payment/{payment}/show', [App\Http\Controllers\PaymentController::class, 'show'])->name('payment.show');
    Route::get('/payment/{payment}/status', [App\Http\Controllers\PaymentController::class, 'checkStatus'])->name('payment.status');

    // Admin routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
        
        // Products
        Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
        
        // Categories
        Route::get('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('categories.destroy');
        
        // Sales / Orders
        Route::get('/sales', [\App\Http\Controllers\Admin\SalesController::class, 'index'])->name('sales.index');
        Route::get('/sales/{order}', [\App\Http\Controllers\Admin\SalesController::class, 'show'])->name('sales.show');
        Route::put('/sales/{order}/status', [\App\Http\Controllers\Admin\SalesController::class, 'updateStatus'])->name('sales.updateStatus');
        
        // Outlets & Tables
        Route::get('/outlets', function() {
            $outlets = App\Models\Outlet::with('tables')->get();
            return view('admin.outlets.index', compact('outlets'));
        })->name('outlets.index');
        
        // Users Management
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->except(['show']);
    });
});

// Midtrans Webhook (public, no CSRF)
Route::post('/payment/notification', [App\Http\Controllers\PaymentController::class, 'notification'])->name('payment.notification');
