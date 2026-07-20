<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockMutationController;
use Illuminate\Support\Facades\Route;

// TODO Role 1 (Rava): route login/logout, redirect halaman utama ke dashboard/login
// TODO Role 3 (Haichal): route dashboard & laporan
// TODO Role 4 (Aqila): route landing page publik

// TEMP buat testing - HAPUS sebelum push/merge ke main
Route::get('/dev-login', function () {
    auth()->login(\App\Models\User::factory()->create());
    return redirect('/products');
});

Route::middleware('auth')->group(function () {
    Route::resource('products', ProductController::class)->except('show');
    Route::resource('stock-mutations', StockMutationController::class)->only(['index', 'create', 'store']);
});
