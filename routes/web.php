<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemRequestController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StockMutationController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\VehicleLoanController;
use Illuminate\Support\Facades\Route;

// ---------------------------------------------------------------------------
// Public routes
// ---------------------------------------------------------------------------
Route::get('/landing', fn() => view('landing'))->name('landing');

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

// TEMP — HAPUS sebelum push/merge ke main
Route::get('/dev-login', function () {
    auth()->login(\App\Models\User::factory()->create());
    return redirect()->route('dashboard');
});

// ---------------------------------------------------------------------------
// Authenticated routes
// ---------------------------------------------------------------------------
Route::middleware('auth')->group(function () {

    // Dashboard & Laporan
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export-pdf', [ReportController::class, 'exportPdf'])
        ->name('reports.export-pdf')
        ->middleware('role:Super Admin');

    // Produk & Mutasi Stok (Role 2 — jangan ubah)
    Route::resource('products', ProductController::class)->except('show');
    Route::resource('stock-mutations', StockMutationController::class)->only(['index', 'create', 'store']);

    // Profil — semua role
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // -----------------------------------------------------------------------
    // Super Admin & Staff Gudang only
    // -----------------------------------------------------------------------
    Route::middleware('role:Super Admin|Staff Gudang')->group(function () {
        Route::resource('categories', CategoryController::class)->except('show');
        Route::resource('suppliers', SupplierController::class)->except('show');
        Route::resource('users', UserController::class)->except('show');
        Route::resource('vehicles', VehicleController::class)->except('show');
    });

    // -----------------------------------------------------------------------
    // Super Admin only
    // -----------------------------------------------------------------------
    Route::middleware('role:Super Admin')->group(function () {
        Route::resource('roles', RoleController::class)->except('show');
    });

    // -----------------------------------------------------------------------
    // Semua role (authenticated)
    // -----------------------------------------------------------------------

    // Permintaan Barang
    Route::resource('item-requests', ItemRequestController::class)->except(['show', 'edit', 'update', 'destroy']);
    Route::patch('/item-requests/{itemRequest}/verify', [ItemRequestController::class, 'verify'])
        ->name('item-requests.verify')
        ->middleware('role:Super Admin|Staff Gudang');

    // Kendaraan — peminjaman & pengembalian (semua role)
    Route::resource('vehicle-loans', VehicleLoanController::class)->only(['index', 'create', 'store']);
    Route::patch('/vehicle-loans/{vehicleLoan}/return', [VehicleLoanController::class, 'markReturned'])
        ->name('vehicle-loans.return');

    // Keranjang & Transaksi
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::resource('transactions', TransactionController::class)->only(['index', 'show']);
});

