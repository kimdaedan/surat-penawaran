<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\InvoiceController;

// Rute untuk menampilkan Dashboard
Route::get('/', function () {
    // Di masa depan, Anda akan mengambil data penawaran dari database di sini
    // $penawaran = Penawaran::all();
    return view('dashboard'); // <-- Menampilkan view dashboard baru
});

// Rute untuk menampilkan form pembuatan surat
Route::get('/penawaran/buat', function() {
    return view('penawaran.create'); // <-- Menampilkan view form baru
})->name('penawaran.create'); // Beri nama rute agar mudah dipanggil

// Rute untuk memproses data dari formulir dan menampilkan hasilnya
Route::post('/buat-surat', function (Request $request) {
    $data = $request->all();

    // Tampilan hasil surat juga perlu di-styling, untuk sekarang biarkan dulu
    return view('hasil_surat', ['data' => $data]);
});

// Rute untuk harga
Route::get('/daftar-harga', [ProductController::class, 'index'])->name('harga.index');

// Rute untuk menampilkan form tambah harga/jasa
Route::get('/daftar-harga/tambah', [ProductController::class, 'create'])->name('harga.create');

// Tambahkan rute baru ini untuk proses simpan data
Route::post('/daftar-harga', [ProductController::class, 'store'])->name('harga.store');

Route::delete('/daftar-harga/{product}', [ProductController::class, 'destroy'])->name('harga.destroy');
Route::get('/daftar-harga/{product}/edit', [ProductController::class, 'edit'])->name('harga.edit');
Route::put('/daftar-harga/{product}', [ProductController::class, 'update'])->name('harga.update');

// Rute untuk menampilkan form penawaran produk + jasa
Route::get('/penawaran/buat-kombinasi', [ProductController::class, 'createCombined'])->name('penawaran.create_combined');

// Rute untuk menyimpan data form penawaran produk + jasa
Route::post('/penawaran/simpan-kombinasi', [ProductController::class, 'storeCombined'])->name('penawaran.store_combined');

// Rute untuk menampilkan halaman histori penawaran
Route::get('/histori-penawaran', [OfferController::class, 'index'])->name('histori.index');

// Rute untuk menampilkan detail satu penawaran
Route::get('/penawaran/{offer}', [OfferController::class, 'show'])->name('histori.show');

//untuk menghapus histori
Route::delete('/penawaran/{offer}', [OfferController::class, 'destroy'])->name('histori.destroy');

// Rute untuk menampilkan halaman form edit
Route::get('/penawaran/{offer}/edit', [OfferController::class, 'edit'])->name('histori.edit');

// Rute untuk memproses update data
Route::put('/penawaran/{offer}', [OfferController::class, 'update'])->name('histori.update');

// Rute untuk menampilkan form pembuatan invoice
Route::get('/invoice/create', [InvoiceController::class, 'create'])->name('invoice.create');

// Rute untuk menampilkan form invoice yang mengambil data dari Penawaran
Route::get('/invoice/create-from-offer/{offer}', [InvoiceController::class, 'createFromOffer'])->name('invoice.create_from_offer');

// Rute untuk menampilkan halaman histori invoice
Route::get('/invoice/histori', [InvoiceController::class, 'index'])->name('invoice.histori');

// Rute untuk menyimpan data invoice yang dibuat dari penawaran
Route::post('/invoice/store-from-offer', [InvoiceController::class, 'storeFromOffer'])->name('invoice.store_from_offer');

//     TAMBAHKAN RUTE BARU DI SINI
// ==================================
Route::delete('/invoice/{invoice}', [InvoiceController::class, 'destroy'])->name('invoice.destroy');

Route::get('invoice/show/{invoice}', [InvoiceController::class, 'show'])->name('invoice.show');
Route::get('invoice/edit/{invoice}', [InvoiceController::class, 'edit'])->name('invoice.edit');
Route::put('invoice/update/{invoice}', [InvoiceController::class, 'update'])->name('invoice.update');
