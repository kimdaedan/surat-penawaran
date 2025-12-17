<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\BastController;
use App\Http\Controllers\SkpController;

// Rute untuk menampilkan Dashboard
Route::get('/', function () {
    return view('dashboard');
});

// Rute untuk menampilkan form pembuatan surat (Manual/Lama)
Route::get('/penawaran/buat', function() {
    return view('penawaran.create');
})->name('penawaran.create');

// Rute untuk memproses data dari formulir dan menampilkan hasilnya (Manual/Lama)
Route::post('/buat-surat', function (Request $request) {
    $data = $request->all();
    return view('hasil_surat', ['data' => $data]);
});

// === RUTE HARGA PRODUK ===
Route::get('/daftar-harga', [ProductController::class, 'index'])->name('harga.index');
Route::get('/daftar-harga/tambah', [ProductController::class, 'create'])->name('harga.create');
Route::post('/daftar-harga', [ProductController::class, 'store'])->name('harga.store');
Route::delete('/daftar-harga/{product}', [ProductController::class, 'destroy'])->name('harga.destroy');
Route::get('/daftar-harga/{product}/edit', [ProductController::class, 'edit'])->name('harga.edit');
Route::put('/daftar-harga/{product}', [ProductController::class, 'update'])->name('harga.update');

// === RUTE PENAWARAN (OFFER) ===
// Form penawaran produk + jasa
Route::get('/penawaran/buat-kombinasi', [ProductController::class, 'createCombined'])->name('penawaran.create_combined');
// Simpan data form penawaran produk + jasa
Route::post('/penawaran/simpan-kombinasi', [ProductController::class, 'storeCombined'])->name('penawaran.store_combined');

// Histori Penawaran
Route::get('/histori-penawaran', [OfferController::class, 'index'])->name('histori.index');
Route::get('/penawaran/{offer}', [OfferController::class, 'show'])->name('histori.show');
Route::delete('/penawaran/{offer}', [OfferController::class, 'destroy'])->name('histori.destroy');
Route::get('/penawaran/{offer}/edit', [OfferController::class, 'edit'])->name('histori.edit');
Route::put('/penawaran/{offer}', [OfferController::class, 'update'])->name('histori.update');

// === RUTE INVOICE ===
Route::get('/invoice/create', [InvoiceController::class, 'create'])->name('invoice.create');
Route::get('/invoice/create-from-offer/{offer}', [InvoiceController::class, 'createFromOffer'])->name('invoice.create_from_offer');
Route::get('/invoice/histori', [InvoiceController::class, 'index'])->name('invoice.histori');
Route::post('/invoice/store-from-offer', [InvoiceController::class, 'storeFromOffer'])->name('invoice.store_from_offer');
Route::delete('/invoice/{invoice}', [InvoiceController::class, 'destroy'])->name('invoice.destroy');
Route::get('invoice/show/{invoice}', [InvoiceController::class, 'show'])->name('invoice.show');
Route::get('invoice/edit/{invoice}', [InvoiceController::class, 'edit'])->name('invoice.edit');
Route::put('invoice/update/{invoice}', [InvoiceController::class, 'update'])->name('invoice.update');

// === RUTE BAST (BERITA ACARA SERAH TERIMA) ===
// Histori BAST
Route::get('/histori-bast', [BastController::class, 'index'])->name('bast.index');
// Detail & Cetak BAST
Route::get('/bast/{bast}', [BastController::class, 'show'])->name('bast.show');
// Hapus BAST
Route::delete('/bast/{bast}', [BastController::class, 'destroy'])->name('bast.destroy');

// Buat BAST Baru (Dari ID Penawaran)
Route::get('/penawaran/{offer}/bast/create', [BastController::class, 'create'])->name('bast.create');
// Simpan BAST Baru
Route::post('/penawaran/{offer}/bast', [BastController::class, 'store'])->name('bast.store');


// === RUTE SKP (SURAT PERINTAH KERJA) ===
// Histori SKP
Route::get('/histori-skp', [SkpController::class, 'index'])->name('skp.index');

// Detail & Cetak SKP
Route::get('/skp/{skp}', [SkpController::class, 'show'])->name('skp.show');

// Hapus SKP
Route::delete('/skp/{skp}', [SkpController::class, 'destroy'])->name('skp.destroy');

// Buat SKP Baru (Dari ID Penawaran)
Route::get('/penawaran/{offer}/skp/create', [SkpController::class, 'create'])->name('skp.create');
Route::post('/penawaran/{offer}/skp', [SkpController::class, 'store'])->name('skp.store');