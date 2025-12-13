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
            // (Mencari berdasarkan nama Klien ATAU ID)
            $query->where(function ($q) use ($search) {
                $q->where('nama_klien', 'like', '%' . $search . '%')
                  ->orWhere('id', $search); // <-- Mencari berdasarkan ID untuk No. Surat
            });
        }

        // 4. Ambil data dengan pagination (15 data per halaman), urutkan dari yang terbaru
        $offers = $query->latest()->paginate(15);

        // 5. Kirim data ke view
        return view('histori.index', [
            'offers' => $offers,
            'search' => $search
        ]);
    }

    /**
     * Menampilkan halaman detail untuk satu penawaran.
     */
    public function show(Offer $offer)
    {
        // Memuat semua relasi
        $offer->load(['items', 'jasaItems']);

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

    /**
     * Menampilkan form edit.
     */
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
        // Validasi
        $request->validate([
            'nama_klien' => 'required|string|max:255',
            'client_details' => 'nullable|string',
            'produk.*.nama' => 'nullable|string',
            'produk.*.area' => 'nullable|string',
            'produk.*.volume' => 'nullable|numeric',
            'produk.*.harga' => 'nullable|numeric',
            'jasa.*.nama' => 'nullable|string',
            'jasa.*.harga' => 'nullable|numeric',
        ]);

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
            'client_details' => $request->client_details,
            'total_keseluruhan' => $totalProduk + $totalJasa,
            // === UPDATE OPSI TAMBAHAN ===
            // Menggunakan $request->has(...) untuk mengecek apakah checkbox dicentang
            'pisah_kriteria_total' => $request->has('pisah_kriteria_total'),
            'hilangkan_grand_total' => $request->has('hilangkan_grand_total'),
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