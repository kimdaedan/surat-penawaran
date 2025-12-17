<?php

namespace App\Http\Controllers;

use App\Models\Skp;
use App\Models\Offer;
use Illuminate\Http\Request;

class SkpController extends Controller
{
    // === 1. FORMULIR PEMBUATAN SKP ===
    public function create(Offer $offer)
    {
        // Cek jika SKP sudah pernah dibuat untuk penawaran ini
        if (Skp::where('offer_id', $offer->id)->exists()) {
             return redirect()->back()->with('error', 'SKP untuk penawaran ini sudah dibuat.');
        }

        // Generate Nomor Surat (Format Contoh: 00XX/SPK/TGI-1/ROMAWI/TAHUN)
        $bulanRomawi = array("", "I","II","III","IV","V","VI","VII","VIII","IX","X","XI","XII");
        $nextId = Skp::max('id') + 1;
        $noSurat = sprintf('00%d/SPK/TGI-1/%s/%s', $nextId, $bulanRomawi[date('n')], date('Y'));

        // DATA DEFAULT PIHAK KEDUA (PIHAK KAMI - KONTRAKTOR)
        $pihakDua = [
            'nama' => 'Samsu Rizal',
            'jabatan' => 'General Manager',
            'perusahaan' => 'PT. Tasniem Gerai Inspirasi',
            'alamat' => 'Ruko KDA Junction Blok C No 8-9 Batam Center - Batam'
        ];

        return view('skp.create', [
            'offer' => $offer,
            'noSurat' => $noSurat,
            'pihakDua' => $pihakDua
        ]);
    }

    // === 2. SIMPAN DATA SKP ===
    public function store(Request $request, Offer $offer)
    {
        // Validasi Input
        $request->validate([
            'no_surat' => 'required',
            // 'tanggal_surat' diambil dari input date di view (default hari ini)
            'tanggal_surat' => 'required|date',

            // Pihak I (Klien)
            'pihak_satu_nama' => 'required',
            'pihak_satu_perusahaan' => 'nullable', // <-- OPSIONAL
            'pihak_satu_alamat' => 'required',
            // Jabatan Pihak I juga opsional di view, jadi di sini tidak wajib required
            'pihak_satu_jabatan' => 'nullable',

            // Pihak II (Kita)
            'pihak_dua_nama' => 'required',
            'pihak_dua_jabatan' => 'required',
            // Perusahaan & Alamat Pihak II biasanya readonly/wajib ada

            // Detail Pekerjaan
            'judul_pekerjaan' => 'required',
            'lokasi_pekerjaan' => 'required',
            'durasi_hari' => 'required', // String teks lengkap ("60 hari kalender")
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',

            // Termin Pembayaran (Array dari input dinamis)
            'termin_keterangan' => 'required|array',
            'termin_tanggal' => 'required|array', // Validasi array tanggal
            'termin_jumlah' => 'required|array',
        ]);

        // Proses Data Termin Pembayaran
        // Gabungkan array keterangan, tanggal, dan jumlah menjadi satu struktur data
        $terminPembayaran = [];

        // Loop berdasarkan array keterangan (asumsi panjang array sama karena required)
        if ($request->has('termin_keterangan')) {
            foreach ($request->termin_keterangan as $index => $ket) {
                if (!empty($ket)) {
                    // Ambil tanggal dari input (format YYYY-MM-DD dari date picker)
                    // Kita bisa memformatnya ke format Indonesia jika mau (d F Y),
                    // tapi simpan format asli date (Y-m-d) lebih fleksibel, nanti di-format di view.
                    $tglRaw = $request->termin_tanggal[$index] ?? null;

                    // Format jumlah dengan % otomatis jika user lupa mengetiknya
                    $jumlahInput = $request->termin_jumlah[$index] ?? '';
                    $jumlahFormatted = str_contains($jumlahInput, '%') ? $jumlahInput : $jumlahInput . '%';

                    $terminPembayaran[] = [
                        'keterangan' => $ket,
                        'tanggal' => $tglRaw, // Simpan tanggal (bisa null/dash/date)
                        'jumlah' => $jumlahFormatted
                    ];
                }
            }
        }

        // Simpan ke Database
        $skp = Skp::create([
            'offer_id' => $offer->id,
            'no_surat' => $request->no_surat,
            'tanggal_surat' => $request->tanggal_surat,

            'pihak_satu_nama' => $request->pihak_satu_nama,
            'pihak_satu_jabatan' => $request->pihak_satu_jabatan ?? '-', // Default strip jika kosong
            'pihak_satu_perusahaan' => $request->pihak_satu_perusahaan ?? '-', // Default strip jika kosong
            'pihak_satu_alamat' => $request->pihak_satu_alamat,

            'pihak_dua_nama' => $request->pihak_dua_nama,
            'pihak_dua_jabatan' => $request->pihak_dua_jabatan,
            'pihak_dua_perusahaan' => $request->pihak_dua_perusahaan,
            'pihak_dua_alamat' => $request->pihak_dua_alamat,

            'judul_pekerjaan' => $request->judul_pekerjaan,
            'lokasi_pekerjaan' => $request->lokasi_pekerjaan,
            'durasi_hari' => $request->durasi_hari,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'nilai_pekerjaan' => $offer->total_keseluruhan, // Otomatis ambil dari total penawaran

            'termin_pembayaran' => $terminPembayaran, // Disimpan sebagai JSON
        ]);

        // Redirect ke halaman detail/cetak dengan pesan sukses
        return redirect()->route('skp.show', $skp->id)->with('success', 'SKP berhasil dibuat!');
    }

    // === 3. HISTORI DAFTAR SKP ===
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Skp::query()->with('offer'); // Eager loading relasi offer

        if ($search) {
            $query->where('no_surat', 'like', '%' . $search . '%')
                  ->orWhere('pihak_satu_nama', 'like', '%' . $search . '%') // Cari nama klien
                  ->orWhere('judul_pekerjaan', 'like', '%' . $search . '%');
        }

        $skps = $query->latest()->paginate(15);

        return view('skp.index', [
            'skps' => $skps,
            'search' => $search
        ]);
    }

    // === 4. DETAIL / CETAK SKP ===
    public function show(Skp $skp)
    {
        $skp->load('offer'); // Pastikan relasi offer termuat

        return view('skp.show', [
            'skp' => $skp
        ]);
    }

    // === 5. HAPUS SKP ===
    public function destroy(Skp $skp)
    {
        $skp->delete();
        return redirect()->route('skp.index')->with('success', 'Surat Perintah Kerja berhasil dihapus!');
    }
}