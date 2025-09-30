<?php

namespace App\Http\Controllers;

use App\Models\Offer; // <-- Import model Offer
use Illuminate\Http\Request;

class OfferController extends Controller
{
    /**
     * Menampilkan halaman histori dari semua penawaran yang telah dibuat.
     */
    public function index()
    {
        // 1. Ambil semua data dari tabel 'offers', urutkan dari yang terbaru
        $offers = Offer::latest()->get();

        // 2. Kirim data tersebut ke view 'histori.index'
        return view('histori.index', ['offers' => $offers]);
    }

    public function show(Offer $offer)
    {
        // Laravel akan otomatis mencari data 'Offer' berdasarkan ID di URL.
        // Kita juga memuat data 'items' yang berelasi dengannya.
        $offer->load('items');

        return view('histori.show', ['offer' => $offer]);
    }
}
