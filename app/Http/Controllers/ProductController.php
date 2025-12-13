<?php

namespace App\Http\Controllers;

use App\Models\Product; // <-- Import Model Product
use Illuminate\Http\Request;
use App\Models\Offer; // <-- Import Model Offer
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // 1. Menampilkan daftar harga
    public function index()
    {
        $products = Product::latest()->get();
        return view('harga.index', ['products' => $products]);
    }

    // 2. Form tambah harga baru
    public function create()
    {
        return view('harga.create');
    }

    // 3. Menyimpan data harga baru (TERMASUK KRITERIA)
    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'hasil_akhir' => 'required|string|max:255',
            'performa' => 'required|string|max:255',
            'kriteria' => 'required|string|in:Exterior,Interior', // <-- Validasi Kriteria
            'harga' => 'required|integer',
        ]);

        Product::create([
            'nama_produk' => $request->nama_produk,
            'hasil_akhir' => $request->hasil_akhir,
            'performa' => $request->performa,
            'kriteria' => $request->kriteria, // <-- Simpan Kriteria
            'harga' => $request->harga,
        ]);

        return redirect()->route('harga.index')->with('success', 'Data berhasil ditambahkan!');
    }

    // 4. Menghapus data harga
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('harga.index')->with('success', 'Data berhasil dihapus!');
    }

    // 5. Form edit harga
    public function edit(Product $product)
    {
        return view('harga.edit', ['product' => $product]);
    }

    // 6. Update data harga (TERMASUK KRITERIA)
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'hasil_akhir' => 'required|string|max:255',
            'performa' => 'required|string|max:255',
            'kriteria' => 'required|string|in:Exterior,Interior', // <-- Validasi Kriteria
            'harga' => 'required|integer',
        ]);

        $product->update([
            'nama_produk' => $request->nama_produk,
            'hasil_akhir' => $request->hasil_akhir,
            'performa' => $request->performa,
            'kriteria' => $request->kriteria, // <-- Update Kriteria
            'harga' => $request->harga,
        ]);

        return redirect()->route('harga.index')->with('success', 'Data berhasil diperbarui!');
    }

    // =========================================================================
    // BAGIAN LOGIKA PENAWARAN (OFFER)
    // =========================================================================

    public function createCombined(Request $request)
    {
        $token = Str::uuid()->toString();
        $request->session()->put('form_token', $token);
        $products = Product::all();
        return view('penawaran.create_combined', ['products' => $products]);
    }

    public function storeCombined(Request $request)
    {
        $request->validate([
            'nama_klien' => 'required|string|max:255',
        ]);

        // Hitung total di backend
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

        // Simpan Offer Utama
        $offer = Offer::create([
            'nama_klien' => $request->nama_klien,
            'client_details' => $request->client_details,
            'total_keseluruhan' => $totalProduk + $totalJasa,
        ]);

        // Simpan Item Produk
        if ($request->has('produk')) {
            foreach ($request->produk as $itemData) {
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

        // Simpan Item Jasa
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

        return redirect('/')->with('success', 'Surat Penawaran berhasil dibuat!');
    }

    public function show(Offer $offer)
    {
        $offer->load(['items', 'jasaItems']);
        return view('histori.show', ['offer' => $offer]);
    }
}