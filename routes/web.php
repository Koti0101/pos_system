<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\SalesHistoryController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PasswordController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {

    // === EVERYONE (Admin + Cashier) ===
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // POS (Everyone can make sales)
    Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
    Route::post('/pos/checkout', [POSController::class, 'checkout'])->name('pos.checkout');
    Route::get('/pos/receipt/{id}', [POSController::class, 'receipt'])->name('pos.receipt');
    Route::post('/pos/barcode', [POSController::class, 'searchByBarcode'])->name('pos.barcode');

    // Sales History (Everyone can view)
    Route::get('/sales', [SalesHistoryController::class, 'index'])->name('sales.index');
    Route::get('/sales/{id}', [SalesHistoryController::class, 'show'])->name('sales.show');

    // Products (Read-only for Cashier)
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

    // Categories (Read-only for Cashier)
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

    // Reports (Everyone can view and export)
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportsController::class, 'export'])->name('reports.export');

    // Profile (Everyone can edit their own)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');

    // === ADMIN ONLY ===
    Route::middleware(['admin'])->group(function () {

        // Product Management (Create, Edit, Delete)
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

        // Category Management
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        // User Management
        Route::resource('users', UserController::class);

        // Sales Deletion (if needed)
        Route::delete('/sales/{id}', [SalesHistoryController::class, 'destroy'])->name('sales.destroy');
    });
});

require __DIR__.'/auth.php';