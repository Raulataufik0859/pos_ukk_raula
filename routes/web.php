<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemPenjualanController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProfileController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/auth', [AuthController::class, 'auth'])->name('auth');
    Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar/delete', [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.destroy');

    Route::middleware('role:admin')->group(function () {
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::get('/users', [PenggunaController::class, 'index'])->name('users');
            Route::get('/users/create', [PenggunaController::class, 'create'])->name('users.create');
            Route::post('/users/store', [PenggunaController::class, 'store'])->name('users.store');
            Route::get('/users/edit/{user}', [PenggunaController::class, 'edit'])->name('users.edit');
            Route::post('/users/update/{user}', [PenggunaController::class, 'update'])->name('users.update');
            Route::delete('/users/destroy/{user}', [PenggunaController::class, 'destroy'])->name('users.destroy');
        });

        Route::resource('kategori', KategoriController::class);

        Route::get('/produk/create', [ProdukController::class, 'create'])->name('produk.create');
        Route::post('/produk', [ProdukController::class, 'store'])->name('produk.store');
        Route::get('/produk/{produk}/edit', [ProdukController::class, 'edit'])->name('produk.edit');
        Route::put('/produk/{produk}', [ProdukController::class, 'update'])->name('produk.update');
        Route::patch('/produk/{produk}', [ProdukController::class, 'update']);
        Route::delete('/produk/{produk}', [ProdukController::class, 'destroy'])->name('produk.destroy');

        // Konfirmasi transfer (admin only)
        Route::post('/penjualan/{penjualan}/confirm-transfer', [PenjualanController::class, 'confirmTransfer'])
            ->name('penjualan.confirm-transfer');
        Route::post('/penjualan/{penjualan}/reject-transfer', [PenjualanController::class, 'rejectTransfer'])
            ->name('penjualan.reject-transfer');
    });

    Route::middleware('role:admin,kasir')->group(function () {
        Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
        Route::get('/produk/{produk}', [ProdukController::class, 'show'])->name('produk.show');

        Route::resource('penjualan', PenjualanController::class);

        Route::resource('itempenjualan', ItemPenjualanController::class)->only([
            'store', 'update', 'destroy'
        ]);
    });
});
