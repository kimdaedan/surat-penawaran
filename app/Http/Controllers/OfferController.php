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
    public function show($id)
    {
        // Ambil data penawaran beserta item-itemnya
        $offer = Offer::with(['items', 'jasaItems'])->findOrFail($id);

        // LOGIKA PEMILIHAN VIEW
        if ($offer->jenis_penawaran == 'produk') {
            // Arahkan ke file VIEW BARU yang baru kita buat
            return view('histori.show_product', compact('offer'));
        } else {
            // Arahkan ke file VIEW LAMA (Proyek)
            return view('histori.show', compact('offer'));
        }
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
        // 1. Validasi Input
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

        // 2. Hitung Ulang Total (Berdasarkan input form)
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

        // 3. Siapkan Array Data Utama
        $dataUtama = [
            'nama_klien' => $request->nama_klien,
            'client_details' => $request->client_details,
            'total_keseluruhan' => $totalProduk + $totalJasa,
            'pisah_kriteria_total' => $request->has('pisah_kriteria_total'),
            'hilangkan_grand_total' => $request->has('hilangkan_grand_total'),
        ];

        // 4. LOGIKA CABANG (UPDATE vs SAVE AS NEW)
        $targetOffer = null;
        $message = '';

        if ($request->input('action') == 'save_and_copy') {
            // === KASUS A: UPDATE & COPY (SAVE AS NEW) ===
            // 1. Jangan sentuh $offer lama.
            // 2. Buat object Offer BARU dengan data dari form.

            // Opsional: Tambahkan penanda "(Copy)" jika nama klien tidak diubah user
            // Jika user sudah merubah nama klien di form, pakai nama baru tersebut.
            if ($dataUtama['nama_klien'] === $offer->nama_klien) {
                $dataUtama['nama_klien'] .= ' (Copy)';
            }

            $targetOffer = Offer::create($dataUtama); // Insert data baru
            $message = 'Data berhasil disimpan sebagai Penawaran Baru (Data lama tidak berubah)!';

            // Tidak perlu delete items, karena ini offer baru yang masih kosong item-nya.

        } else {
            // === KASUS B: UPDATE BIASA ===
            // 1. Update $offer lama.
            $offer->update($dataUtama);
            $targetOffer = $offer; // Target kita adalah offer yang sedang diedit

            // 2. Hapus item lama (karena mau ditimpa dengan inputan form baru)
            $targetOffer->items()->delete();
            $targetOffer->jasaItems()->delete();

            $message = 'Surat penawaran berhasil diperbarui.';
        }

        // 5. SIMPAN ITEM PRODUK (Ke $targetOffer)
        // Logika ini dipakai bersama baik untuk Update maupun Copy
        if ($request->has('produk')) {
            foreach ($request->produk as $itemData) {
                if (!empty($itemData['nama'])) {
                    $targetOffer->items()->create([
                        'nama_produk' => $itemData['nama'],
                        'area_dinding' => $itemData['area'],
                        'volume' => $itemData['volume'] ?? 0,
                        'harga_per_m2' => $itemData['harga'] ?? 0,
                    ]);
                }
            }
        }

        // 6. SIMPAN ITEM JASA (Ke $targetOffer)
        if ($request->has('jasa')) {
            foreach ($request->jasa as $jasaData) {
                if (!empty($jasaData['nama'])) {
                    $targetOffer->jasaItems()->create([
                        'nama_jasa' => $jasaData['nama'],
                        'harga_jasa' => $jasaData['harga'] ?? 0,
                    ]);
                }
            }
        }

        // 7. Redirect
        // Jika Copy: Redirect ke halaman Edit milik Offer BARU
        // Jika Update: Redirect ke halaman Index (atau tetap di edit, sesuai selera)
        if ($request->input('action') == 'save_and_copy') {
            return redirect()->route('histori.edit', $targetOffer->id)->with('success', $message);
        }

        return redirect()->route('histori.index')->with('success', $message);
    }
}
