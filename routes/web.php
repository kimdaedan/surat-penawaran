<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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