<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Seller\DashboardController as SellerDashboard;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Support\Facades\Route;

// Route home
Route::get('/', [HomeController::class, 'index']);

// Route dashboard buyer
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [OrderController::class, 'dashboard'])
        ->name('dashboard');
});

// Route dashboard seller
Route::middleware('auth')->prefix('seller')->name('seller.')
    ->group(function () {
        Route::get('/dashboard', [SellerDashboard::class, 'index'])
            ->name('dashboard');
    });

// Route dashboard admin + categories
Route::middleware('auth')->prefix('admin')->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])
            ->name('dashboard');
        Route::resource('categories', CategoryController::class);
    });

// Route profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';