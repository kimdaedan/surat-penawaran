<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

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