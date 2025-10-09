<?php

namespace App\Http\Controllers;

use App\Models\Product; // <-- 1. Import Model Product
use Illuminate\Http\Request;
use App\Models\Offer; // <-- INI YANG BENAR
use Illuminate\Support\Str;

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
    public function createCombined(Request $request) // Tambahkan Request $request
    {
        // 1. Buat token unik ("tiket sekali pakai")
        $token = Str::uuid()->toString();

        // 2. Simpan token ini di session
        $request->session()->put('form_token', $token);

        // 3. Ambil data produk seperti biasa
        $products = Product::all();

        // 4. Kirim data ke view (jangan lupa kirim token juga, meskipun kita akan ambil dari session)
        return view('penawaran.create_combined', ['products' => $products]);
    }

    public function storeCombined(Request $request)
    {
        $request->validate([
            'nama_klien' => 'required|string|max:255',
            // Tambahkan validasi lain jika perlu
        ]);

        // Hitung total di backend (logika ini sudah benar)
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

        // =================================================================
        // BAGIAN PENTING 1: Simpan DATA UTAMA ke tabel 'offers'
        // =================================================================
        $offer = Offer::create([
            'nama_klien' => $request->nama_klien,
            'client_details' => $request->client_details, // <-- client_details HANYA DISIMPAN DI SINI
            'total_keseluruhan' => $totalProduk + $totalJasa,
        ]);

        // =================================================================
        // BAGIAN PENTING 2: Simpan DATA RINCIAN PRODUK ke 'offer_items'
        // =================================================================
        if ($request->has('produk')) {
            foreach ($request->produk as $itemData) {
                if (!empty($itemData['nama'])) {
                    $offer->items()->create([
                        // PASTIKAN TIDAK ADA 'client_details' DI DALAM ARRAY INI
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
                if (!empty($jasaData['nama'])) {
                    $offer->jasaItems()->create([
                        'nama_jasa' => $jasaData['nama'],
                        'harga_jasa' => $jasaData['harga'] ?? 0,
                    ]);
                }
            }
        }

        // (Logika simpan data jasa Anda)
        // ...

        return redirect('/')->with('success', 'Surat Penawaran berhasil dibuat!');
    }

    public function show(Offer $offer)
    {
        // Memuat relasi 'items' (produk) DAN 'jasaItems' (pengerjaan tambahan)
        $offer->load(['items', 'jasaItems']);

        return view('histori.show', ['offer' => $offer]);
    }
}
