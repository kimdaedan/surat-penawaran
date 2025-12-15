<?php

namespace App\Http\Controllers;

use App\Models\Bast;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BastController extends Controller
{
    // === Menampilkan Form Pembuatan BAST ===
    public function create(Offer $offer)
    {
        // Cek apakah BAST sudah ada
        if ($offer->bast) {
             return redirect()->back()->with('error', 'BAST untuk penawaran ini sudah dibuat.');
        }

        // Generate Nomor Surat Otomatis
        $bulanRomawi = array("", "I","II","III","IV","V","VI","VII","VIII","IX","X","XI","XII");
        $nextId = Bast::max('id') + 1;
        $noSurat = sprintf('%04d/SK/TGI-1/%s/%s', $nextId, $bulanRomawi[date('n')], date('Y'));

        // Default Pihak Pertama
        $defaultPihakPertama = [
            'nama' => 'Samsu Rizal',
            'jabatan' => 'General Manager'
        ];

        return view('bast.create', [
            'offer' => $offer,
            'noSurat' => $noSurat,
            'pihakPertama' => $defaultPihakPertama
        ]);
    }

    // === Menyimpan Data BAST ===
    public function store(Request $request, Offer $offer)
    {
        // 1. Validasi Input
        $request->validate([
            'no_surat' => 'required|string',
            // Tanggal otomatis diambil di backend, jadi tidak perlu validasi input tanggal manual di sini

            'pihak_pertama_nama' => 'required|string',
            'pihak_pertama_jabatan' => 'required|string',
            'pihak_kedua_nama' => 'required|string',

            // PERUBAHAN: Jabatan & Perusahaan sekarang BOLEH KOSONG (nullable)
            'pihak_kedua_jabatan' => 'nullable|string',
            'pihak_kedua_perusahaan' => 'nullable|string',

            'pihak_kedua_alamat' => 'required|string', // Alamat tetap wajib (sudah terisi otomatis di view)
            'deskripsi_pekerjaan' => 'required|string',

            // Validasi Gambar (Multiple Upload)
            'before_images' => 'required|array|min:1',
            'before_images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'after_images' => 'required|array|min:1',
            'after_images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. Proses Upload Gambar Before
        $beforePaths = [];
        if($request->hasFile('before_images')) {
            foreach($request->file('before_images') as $file) {
                $beforePaths[] = $file->store('bast-images', 'public');
            }
        }

        // 3. Proses Upload Gambar After
        $afterPaths = [];
        if($request->hasFile('after_images')) {
            foreach($request->file('after_images') as $file) {
                $afterPaths[] = $file->store('bast-images', 'public');
            }
        }

        // 4. Set Tanggal Resmi (Otomatis hari ini)
        $tanggalResmi = date('Y-m-d');

        // 5. Simpan ke Database
        $bast = Bast::create([
            'offer_id' => $offer->id,
            'no_surat' => $request->no_surat,
            'tanggal_bast' => $tanggalResmi,

            'pihak_pertama_nama' => $request->pihak_pertama_nama,
            'pihak_pertama_jabatan' => $request->pihak_pertama_jabatan,

            'pihak_kedua_nama' => $request->pihak_kedua_nama,
            // Jika input kosong, isi dengan strip '-' agar tidak error di database
            'pihak_kedua_jabatan' => $request->pihak_kedua_jabatan ?? '-',
            'pihak_kedua_perusahaan' => $request->pihak_kedua_perusahaan ?? '-',

            'pihak_kedua_alamat' => $request->pihak_kedua_alamat,
            'deskripsi_pekerjaan' => $request->deskripsi_pekerjaan,

            // Simpan array path sebagai JSON String
            'before_image_path' => json_encode($beforePaths),
            'after_image_path' => json_encode($afterPaths),
        ]);

        // 6. Redirect ke halaman Histori BAST dengan pesan Sukses
        return redirect()->route('bast.index')->with('success', 'BAST berhasil disimpan!');
    }

    // === Menampilkan Daftar Histori BAST ===
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Bast::query()->with('offer');

        if ($search) {
            $query->where('no_surat', 'like', '%' . $search . '%')
                  ->orWhereHas('offer', function ($q) use ($search) {
                      $q->where('nama_klien', 'like', '%' . $search . '%');
                  });
        }

        $basts = $query->latest()->paginate(15);

        return view('bast.index', [
            'basts' => $basts,
            'search' => $search
        ]);
    }

    // === Menampilkan Detail/Cetak BAST ===
    public function show(Bast $bast)
    {
        $bast->load('offer');

        // Decode JSON gambar agar bisa di-loop di view
        $beforeImages = json_decode($bast->before_image_path);
        if (!is_array($beforeImages)) {
            $beforeImages = $bast->before_image_path ? [$bast->before_image_path] : [];
        }

        $afterImages = json_decode($bast->after_image_path);
        if (!is_array($afterImages)) {
            $afterImages = $bast->after_image_path ? [$bast->after_image_path] : [];
        }

        return view('bast.show', [
            'bast' => $bast,
            'beforeImages' => $beforeImages,
            'afterImages' => $afterImages
        ]);
    }

    // === Menghapus BAST ===
    public function destroy(Bast $bast)
    {
        // 1. Hapus File Before
        $beforeImages = json_decode($bast->before_image_path);
        if (is_array($beforeImages)) {
            foreach ($beforeImages as $path) Storage::disk('public')->delete($path);
        } elseif ($bast->before_image_path) {
            Storage::disk('public')->delete($bast->before_image_path);
        }

        // 2. Hapus File After
        $afterImages = json_decode($bast->after_image_path);
        if (is_array($afterImages)) {
            foreach ($afterImages as $path) Storage::disk('public')->delete($path);
        } elseif ($bast->after_image_path) {
            Storage::disk('public')->delete($bast->after_image_path);
        }

        // 3. Hapus Record DB
        $bast->delete();

        return redirect()->route('bast.index')->with('success', 'BAST berhasil dihapus!');
    }
}