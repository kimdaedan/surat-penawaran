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
  public function index(Request $request)
    {
        // 1. Ambil kata kunci pencarian dari URL (jika ada)
        $search = $request->input('search');

        // 2. Mulai query ke database
        $query = Offer::query();

        // 3. Jika ada kata kunci pencarian, filter datanya
        if ($search) {
            $query->where('nama_klien', 'like', '%' . $search . '%');
        }

        // 4. Ambil data dengan pagination (15 data per halaman), urutkan dari yang terbaru
        $offers = $query->latest()->paginate(15);

        // 5. Kirim data ke view, beserta kata kunci pencariannya
        return view('histori.index', [
            'offers' => $offers,
            'search' => $search // Ini agar kata kunci tetap ada di kotak search
        ]);
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
