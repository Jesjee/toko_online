<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Seller\DashboardController as SellerDashboard;
use App\Http\Controllers\Seller\ProductController as SellerProductController;
use App\Http\Controllers\Seller\OrderController as SellerOrderController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Support\Facades\Route;

// ─── PUBLIC ───────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

// ─── BUYER ───────────────────────────────────────────────────────
Route::middleware(['auth', 'role:buyer'])->group(function () {
    Route::get('/dashboard', [OrderController::class, 'dashboard'])->name('dashboard');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::resource('orders', OrderController::class)->only(['index', 'show']);
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // P9 — Payment
    Route::get('/orders/{order}/payment',
        [PaymentController::class, 'show'])->name('orders.payment');
    Route::post('/orders/{order}/payment',
        [PaymentController::class, 'upload'])->name('orders.payment.upload');
});

// ─── SELLER ──────────────────────────────────────────────────────
Route::middleware(['auth', 'role:seller'])->prefix('seller')->name('seller.')
    ->group(function () {
        Route::get('/dashboard', [SellerDashboard::class, 'index'])
            ->name('dashboard');

        Route::get('/products/trash', [SellerProductController::class, 'trash'])
            ->name('products.trash');
        Route::post('/products/{id}/restore', [SellerProductController::class, 'restore'])
            ->name('products.restore');

        Route::resource('products', SellerProductController::class);

        // P9 — Seller Orders
        Route::resource('orders', SellerOrderController::class)
            ->only(['index', 'show']);
        Route::post('/orders/{order}/verify',
            [SellerOrderController::class, 'verify'])->name('orders.verify');
        Route::post('/orders/{order}/reject',
            [SellerOrderController::class, 'reject'])->name('orders.reject');
        Route::post('/orders/{order}/ship',
            [SellerOrderController::class, 'ship'])->name('orders.ship');
    });

// ─── ADMIN ───────────────────────────────────────────────────────
Route::middleware('auth')->prefix('admin')->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])
            ->name('dashboard');
        Route::resource('categories', CategoryController::class);
    });

// ─── PROFILE ─────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';