<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemPenjualanController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PemasokController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Guest Routes (belum login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/auth', [AuthController::class, 'auth'])->name('auth');

    // Lupa Password
    Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (sudah login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Profil (semua role yang sudah login)
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    /*
    |--------------------------------------------------------------------------
    | Admin Only - Pengguna
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', [PenggunaController::class, 'index'])->name('users');
        Route::get('/users/create', [PenggunaController::class, 'create'])->name('users.create');
        Route::post('/users/store', [PenggunaController::class, 'store'])->name('users.store');
        Route::get('/users/edit/{user}', [PenggunaController::class, 'edit'])->name('users.edit');
        Route::post('/users/update/{user}', [PenggunaController::class, 'update'])->name('users.update');
        Route::delete('/users/destroy/{user}', [PenggunaController::class, 'destroy'])->name('users.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Laporan
    |--------------------------------------------------------------------------
    */
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::get('/penjualan', [LaporanController::class, 'penjualan'])->name('penjualan');
        Route::get('/pembelian', [LaporanController::class, 'pembelian'])->name('pembelian');
        Route::get('/stok', [LaporanController::class, 'stok'])->name('stok');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Only - Kategori Produk
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->group(function () {
        Route::resource('kategori', KategoriController::class);
    });

    /*
    |--------------------------------------------------------------------------
    | Admin & Kasir
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin,kasir')->group(function () {

        // Produk
        Route::resource('produk', ProdukController::class);

        // Penjualan
        Route::resource('penjualan', PenjualanController::class);

        // Pemasok
        Route::resource('pemasok', PemasokController::class);

        // Pembelian
        Route::resource('pembelian', PembelianController::class)->except(['edit', 'update']);

        // Item Penjualan
        Route::resource('itempenjualan', ItemPenjualanController::class)->only([
            'store', 'update', 'destroy'
        ]);
    });
});
