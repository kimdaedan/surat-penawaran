<?php

namespace App\Http\Controllers;

use App\Models\Product; // <-- 1. Import Model Product
use Illuminate\Http\Request;
use App\Models\Offer; // <-- INI YANG BENAR

class ProductController extends Controller
{
    // 2. Buat method index
    public function index()
    {
        // Ambil semua data dari tabel products
        $products = Product::latest()->get();

        // Kirim data products ke view harga.index
        return view('harga.index', ['products' => $products]);
    }

    public function create()
    {
        return view('harga.create');
    }

    // Method untuk menyimpan data baru ke database
    public function store(Request $request)
    {
        // 1. Validasi data (opsional tapi sangat direkomendasikan)
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'hasil_akhir' => 'required|string|max:255',
            'performa' => 'required|string|max:255',
            'harga' => 'required|integer',
        ]);

        // 2. Simpan data ke database menggunakan Model Product
        Product::create([
            'nama_produk' => $request->nama_produk,
            'hasil_akhir' => $request->hasil_akhir,
            'performa' => $request->performa,
            'harga' => $request->harga,
        ]);

        // 3. Alihkan kembali ke halaman daftar harga dengan pesan sukses
        return redirect()->route('harga.index')->with('success', 'Data berhasil ditambahkan!');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('harga.index')->with('success', 'Data berhasil dihapus!');
    }

    public function edit(Product $product)
    {
        return view('harga.edit', ['product' => $product]);
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'hasil_akhir' => 'required|string|max:255',
            'performa' => 'required|string|max:255',
            'harga' => 'required|integer',
        ]);

        $product->update([
            'nama_produk' => $request->nama_produk,
            'hasil_akhir' => $request->hasil_akhir,
            'performa' => $request->performa,
            'harga' => $request->harga,
        ]);

        return redirect()->route('harga.index')->with('success', 'Data berhasil diperbarui!');
    }

    // Jangan lupa tambahkan 'use App\Models\Product;' di atas jika belum ada
    public function createCombined()
    {
        // 1. Ambil semua data produk dari database
        $products = Product::all();

        // 2. Kirim data tersebut ke view
        return view('penawaran.create_combined', ['products' => $products]);
    }

    public function storeCombined(Request $request)
    {
        $request->validate(['nama_klien' => 'required|string|max:255']);

        // Hitung total di backend untuk keamanan
        $totalProduk = 0;
        if ($request->has('produk')) {
            foreach ($request->produk as $item) {
                $totalProduk += ($item['volume'] ?? 0) * ($item['harga'] ?? 0);
            }
        }
        $totalJasa = 0;
        if ($request->has('jasa')) {
            foreach ($request->jasa as $item) {
                $totalJasa += $item['harga'] ?? 0;
            }
        }

        // Simpan data utama ke tabel 'offers'
        $offer = Offer::create([
            'nama_klien' => $request->nama_klien,
            'total_keseluruhan' => $totalProduk + $totalJasa,
        ]);

        // Simpan setiap baris produk ke tabel 'offer_items'
        if ($request->has('produk')) {
            foreach ($request->produk as $itemData) {
                // Cek jika nama produk diisi, baru simpan
                if (!empty($itemData['nama'])) {
                    $offer->items()->create([
                        'nama_produk' => $itemData['nama'],
                        'area_dinding' => $itemData['area'],
                        'volume' => $itemData['volume'] ?? 0,
                        'harga_per_m2' => $itemData['harga'] ?? 0,
                    ]);
                }
            }
        }

        // Simpan setiap baris jasa ke tabel 'offer_jasa'
        if ($request->has('jasa')) {
            foreach ($request->jasa as $jasaData) {
                // Cek jika nama jasa diisi, baru simpan
                if (!empty($jasaData['nama'])) {
                    $offer->jasaItems()->create([
                        'nama_jasa' => $jasaData['nama'],
                        'harga_jasa' => $jasaData['harga'] ?? 0,
                    ]);
                }
            }
        }

        return redirect('/')->with('success', 'Surat Penawaran berhasil dibuat!');
    }

    public function show(Offer $offer)
    {
        // Memuat relasi 'items' (produk) DAN 'jasaItems' (pengerjaan tambahan)
        $offer->load(['items', 'jasaItems']);

        return view('histori.show', ['offer' => $offer]);
    }
}
