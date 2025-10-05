<?php

namespace App\Http\Controllers;

use App\Models\Product;

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

    /**
     * Menampilkan halaman detail untuk satu penawaran.
     */
    public function show(Offer $offer)
    {
        // Baris ini sangat penting.
        // Ia memberitahu Laravel untuk memuat semua 'items' yang terhubung dengan '$offer' ini.
        $offer->load('items');

        return view('histori.show', ['offer' => $offer]);
    }

    /**
     * Menghapus data penawaran dari database.
     */
    public function destroy(Offer $offer)
    {
        $offer->delete();

        return redirect()->route('histori.index')->with('success', 'Data penawaran berhasil dihapus!');
    }

    public function edit(Offer $offer)
    {
        $offer->load(['items', 'jasaItems']); // Muat data relasi
        $all_products = Product::all(); // Ambil semua produk untuk dropdown

        return view('histori.edit', [
            'offer' => $offer,
            'all_products' => $all_products
        ]);
    }

    /**
     * Memperbarui data penawaran di database.
     */
    public function update(Request $request, Offer $offer)
    {
        $request->validate(['nama_klien' => 'required|string|max:255']);

        // --- Hitung ulang total ---
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

        // --- Update data utama ---
        $offer->update([
            'nama_klien' => $request->nama_klien,
            'total_keseluruhan' => $totalProduk + $totalJasa,
        ]);

        // --- Hapus item lama dan buat ulang (cara termudah) ---
        $offer->items()->delete();
        $offer->jasaItems()->delete();

        // --- Simpan ulang item produk ---
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

        // --- Simpan ulang item jasa ---
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

        return redirect()->route('histori.index')->with('success', 'Data penawaran berhasil diperbarui!');
    }
}
