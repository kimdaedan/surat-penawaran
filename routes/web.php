<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\ProductOfferController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\BastController;
use App\Http\Controllers\SkpController;
use App\Http\Controllers\AuthController;

// =========================================================================
// ROUTE AUTH (LOGIN & LOGOUT)
// =========================================================================
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// =========================================================================
// ROUTE TERPROTEKSI (Harus Login Dulu)
// =========================================================================
Route::middleware(['auth'])->group(function () {

    // 1. DASHBOARD
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    // 2. MASTER DATA PRODUK (DAFTAR HARGA)

    // A. Route Spesifik (Harus Paling Atas)
    Route::get('/daftar-harga/tambah', [ProductController::class, 'create'])->name('harga.create');
    Route::post('/daftar-harga', [ProductController::class, 'store'])->name('harga.store');

    // B. Route Utama (Index)
    Route::get('/daftar-harga', [ProductController::class, 'index'])->name('harga.index');

    // C. Route Dinamis (Harus Paling Bawah agar tidak bentrok)
    // Perhatikan: Jangan sampai ada route lain yang mengarah ke 'show' jika method-nya tidak ada
    Route::get('/daftar-harga/{product}/edit', [ProductController::class, 'edit'])->name('harga.edit');
    Route::put('/daftar-harga/{product}', [ProductController::class, 'update'])->name('harga.update');
    Route::delete('/daftar-harga/{product}', [ProductController::class, 'destroy'])->name('harga.destroy');
    // 3. MENU PENAWARAN
    Route::prefix('penawaran')->name('penawaran.')->group(function () {
        // Penawaran PROYEK
        Route::get('/create-project', [ProductController::class, 'createCombined'])->name('create_combined');
        Route::post('/store-project', [ProductController::class, 'storeCombined'])->name('store_combined');

        // Penawaran PRODUK
        Route::get('/create-product', [ProductOfferController::class, 'create'])->name('create_product');
        Route::post('/store-product', [ProductOfferController::class, 'store'])->name('store_product');
        Route::get('/edit-product/{offer}', [ProductOfferController::class, 'edit'])->name('edit_product');
        Route::put('/update-product/{offer}', [ProductOfferController::class, 'update'])->name('update_product');
    });

    // 4. HISTORI PENAWARAN
    Route::get('/histori-penawaran', [OfferController::class, 'index'])->name('histori.index');
    Route::get('/penawaran/{offer}', [OfferController::class, 'show'])->name('histori.show');
    Route::get('/penawaran/{offer}/edit', [OfferController::class, 'edit'])->name('histori.edit');
    Route::put('/penawaran/{offer}', [OfferController::class, 'update'])->name('histori.update');
    Route::delete('/penawaran/{offer}', [OfferController::class, 'destroy'])->name('histori.destroy');

    // 5. INVOICE
    Route::prefix('invoice')->name('invoice.')->group(function() {
        Route::get('/histori', [InvoiceController::class, 'index'])->name('histori');
        Route::get('/create', [InvoiceController::class, 'create'])->name('create');

        // Rute ini butuh parameter {offer}, ini yang menyebabkan error jika parameternya null
        Route::get('/create-from-offer/{offer}', [InvoiceController::class, 'createFromOffer'])->name('create_from_offer');
        Route::post('/store-from-offer', [InvoiceController::class, 'storeFromOffer'])->name('store_from_offer');

        Route::get('/show/{invoice}', [InvoiceController::class, 'show'])->name('show');
        Route::get('/edit/{invoice}', [InvoiceController::class, 'edit'])->name('edit');
        Route::put('/update/{invoice}', [InvoiceController::class, 'update'])->name('update');
        Route::delete('/{invoice}', [InvoiceController::class, 'destroy'])->name('destroy');
    });

    // 6. BAST
    Route::get('/histori-bast', [BastController::class, 'index'])->name('bast.index');
    Route::get('/bast/{bast}', [BastController::class, 'show'])->name('bast.show');
    Route::delete('/bast/{bast}', [BastController::class, 'destroy'])->name('bast.destroy');
    Route::get('/penawaran/{offer}/bast/create', [BastController::class, 'create'])->name('bast.create');
    Route::post('/penawaran/{offer}/bast', [BastController::class, 'store'])->name('bast.store');

    // 7. SKP
    Route::get('/histori-skp', [SkpController::class, 'index'])->name('skp.index');
    Route::get('/skp/{skp}', [SkpController::class, 'show'])->name('skp.show');
    Route::get('/skp/{skp}/edit', [SkpController::class, 'edit'])->name('skp.edit');
    Route::put('/skp/{skp}', [SkpController::class, 'update'])->name('skp.update');
    Route::delete('/skp/{skp}', [SkpController::class, 'destroy'])->name('skp.destroy');
    Route::get('/penawaran/{offer}/skp/create', [SkpController::class, 'create'])->name('skp.create');
    Route::post('/penawaran/{offer}/skp', [SkpController::class, 'store'])->name('skp.store');
});