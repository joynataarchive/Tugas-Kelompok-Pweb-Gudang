<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockMutationController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

// Redirect root to products or login based on auth status
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('products.index')
        : redirect()->route('login');
});

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// TEMP buat testing - HAPUS sebelum push/merge ke main
Route::get('/dev-login', function () {
    auth()->login(\App\Models\User::factory()->create());
    return redirect('/products');
});

// Routes protected by authentication
Route::middleware('auth')->group(function () {
    Route::resource('products', ProductController::class)->except('show');
    Route::resource('stock-mutations', StockMutationController::class)->only(['index', 'create', 'store']);

    // Contoh pembatasan rute per peran (Spatie RBAC middleware)
    Route::middleware('role:Super Admin')->group(function () {
        // Contoh rute khusus Super Admin
        // Route::get('/reports', [ReportController::class, 'index']);
    });

    Route::middleware('role:Super Admin|Staff Gudang')->group(function () {
        // Contoh rute yang bisa diakses Super Admin & Staff Gudang
    });
});
