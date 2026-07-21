<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockMutationController;
use Illuminate\Support\Facades\Route;

// ---------------------------------------------------------------------------
// Public routes
// ---------------------------------------------------------------------------

// Landing page — dapat diakses tanpa login
Route::get('/landing', function () {
    return view('landing');
})->name('landing');

// Root: redirect ke dashboard (jika sudah login) atau ke login
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

// ---------------------------------------------------------------------------
// Authentication routes (only for guests)
// ---------------------------------------------------------------------------
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// TEMP buat testing - HAPUS sebelum push/merge ke main
Route::get('/dev-login', function () {
    auth()->login(\App\Models\User::factory()->create());
    return redirect()->route('dashboard');
});

// ---------------------------------------------------------------------------
// Authenticated routes
// ---------------------------------------------------------------------------
Route::middleware('auth')->group(function () {
    // Dashboard — semua role yang sudah login
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Produk & Mutasi Stok (Role 2)
    Route::resource('products', ProductController::class)->except('show');
    Route::resource('stock-mutations', StockMutationController::class)->only(['index', 'create', 'store']);

    // Laporan — semua role yang sudah login boleh melihat preview
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // Ekspor PDF — hanya Super Admin
    Route::get('/reports/export-pdf', [ReportController::class, 'exportPdf'])
        ->name('reports.export-pdf')
        ->middleware('role:Super Admin');
});

