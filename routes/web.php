<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\ProductOfferController; // Controller Baru
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\BastController;
use App\Http\Controllers\SkpController;

// ====================================================
// 1. DASHBOARD & HALAMAN UMUM
// ====================================================
Route::get('/', function () {
    return view('dashboard');
});

// ====================================================
// 2. MASTER DATA PRODUK (CRUD)
// ====================================================
Route::resource('daftar-harga', ProductController::class)->parameters([
    'daftar-harga' => 'product'
])->names('harga');
// Penjelasan: Route::resource otomatis membuat route index, create, store, edit, update, destroy.

// ====================================================
// 3. MENU PENAWARAN (PROYEK & PRODUK)
// ====================================================
Route::prefix('penawaran')->name('penawaran.')->group(function () {

    // A. Penawaran PROYEK (Logika Lama ada di ProductController)
    // Perhatikan: Gunakan ProductController, bukan OfferController
    Route::get('/create-project', [ProductController::class, 'createCombined'])->name('create_combined');
    Route::post('/store-project', [ProductController::class, 'storeCombined'])->name('store_combined');

    // B. Penawaran PRODUK (Logika Baru ada di ProductOfferController)
    Route::get('/create-product', [ProductOfferController::class, 'create'])->name('create_product');
    Route::post('/store-product', [ProductOfferController::class, 'store'])->name('store_product');
    // RUTE EDIT & UPDATE KHUSUS PRODUK (BARU)
    Route::get('/edit-product/{offer}', [ProductOfferController::class, 'edit'])->name('edit_product');
    Route::put('/update-product/{offer}', [ProductOfferController::class, 'update'])->name('update_product');

});

// ====================================================
// 4. HISTORI PENAWARAN (CRUD)
// ====================================================
Route::get('/histori-penawaran', [OfferController::class, 'index'])->name('histori.index');
Route::get('/penawaran/{offer}', [OfferController::class, 'show'])->name('histori.show');
Route::get('/penawaran/{offer}/edit', [OfferController::class, 'edit'])->name('histori.edit');
Route::put('/penawaran/{offer}', [OfferController::class, 'update'])->name('histori.update');
Route::delete('/penawaran/{offer}', [OfferController::class, 'destroy'])->name('histori.destroy');


// ====================================================
// 5. INVOICE
// ====================================================
Route::prefix('invoice')->name('invoice.')->group(function() {
    Route::get('/histori', [InvoiceController::class, 'index'])->name('histori');
    Route::get('/create', [InvoiceController::class, 'create'])->name('create');
    // Buat invoice dari ID Penawaran
    Route::get('/create-from-offer/{offer}', [InvoiceController::class, 'createFromOffer'])->name('create_from_offer');
    Route::post('/store-from-offer', [InvoiceController::class, 'storeFromOffer'])->name('store_from_offer');

    // CRUD Standar Invoice
    Route::get('/show/{invoice}', [InvoiceController::class, 'show'])->name('show');
    Route::get('/edit/{invoice}', [InvoiceController::class, 'edit'])->name('edit');
    Route::put('/update/{invoice}', [InvoiceController::class, 'update'])->name('update');
    Route::delete('/{invoice}', [InvoiceController::class, 'destroy'])->name('destroy');
});


// ====================================================
// 6. BAST (Berita Acara Serah Terima)
// ====================================================
Route::get('/histori-bast', [BastController::class, 'index'])->name('bast.index');
Route::get('/bast/{bast}', [BastController::class, 'show'])->name('bast.show');
Route::delete('/bast/{bast}', [BastController::class, 'destroy'])->name('bast.destroy');

// Route BAST Spesifik per Penawaran
Route::get('/penawaran/{offer}/bast/create', [BastController::class, 'create'])->name('bast.create');
Route::post('/penawaran/{offer}/bast', [BastController::class, 'store'])->name('bast.store');


// ====================================================
// 7. SKP (Surat Perintah Kerja)
// ====================================================
Route::get('/histori-skp', [SkpController::class, 'index'])->name('skp.index');
Route::get('/skp/{skp}', [SkpController::class, 'show'])->name('skp.show');
Route::get('/skp/{skp}/edit', [SkpController::class, 'edit'])->name('skp.edit');
Route::put('/skp/{skp}', [SkpController::class, 'update'])->name('skp.update');
Route::delete('/skp/{skp}', [SkpController::class, 'destroy'])->name('skp.destroy');

// Route SKP Spesifik per Penawaran
Route::get('/penawaran/{offer}/skp/create', [SkpController::class, 'create'])->name('skp.create');
Route::post('/penawaran/{offer}/skp', [SkpController::class, 'store'])->name('skp.store');