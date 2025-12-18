<?php

namespace App\Http\Controllers;

use App\Models\Skp;
use App\Models\Offer;
use Illuminate\Http\Request;

class SkpController extends Controller
{
    // === FORMULIR PEMBUATAN SKP ===
    public function create(Offer $offer)
    {
        if (Skp::where('offer_id', $offer->id)->exists()) {
             return redirect()->back()->with('error', 'SKP untuk penawaran ini sudah dibuat.');
        }

        $bulanRomawi = array("", "I","II","III","IV","V","VI","VII","VIII","IX","X","XI","XII");
        $nextId = Skp::max('id') + 1;
        $noSurat = sprintf('00%d/SPK/TGI-1/%s/%s', $nextId, $bulanRomawi[date('n')], date('Y'));

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

    // === SIMPAN DATA SKP ===
    public function store(Request $request, Offer $offer)
    {
        // 1. Validasi Input (Dilonggarkan)
        $request->validate([
            'no_surat' => 'required',
            'tanggal_surat' => 'required|date',

            // Pihak I (Klien)
            'pihak_satu_nama' => 'required',
            'pihak_satu_perusahaan' => 'nullable', // <-- OPSIONAL
            'pihak_satu_alamat' => 'required',
            'pihak_satu_jabatan' => 'nullable', // <-- OPSIONAL

            // Pihak II (Kita)
            'pihak_dua_nama' => 'required',
            'pihak_dua_jabatan' => 'required',

            // Detail Pekerjaan
            'judul_pekerjaan' => 'required',
            'lokasi_pekerjaan' => 'required',
            'durasi_hari' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',

            // Termin Pembayaran (OPSIONAL)
            'termin_keterangan' => 'nullable|array',
            'termin_tanggal' => 'nullable|array',
            'termin_jumlah' => 'nullable|array',
        ]);

        // 2. Proses Data Termin Pembayaran
        $terminPembayaran = [];

        if ($request->has('termin_keterangan')) {
            foreach ($request->termin_keterangan as $index => $ket) {
                // Hanya simpan jika keterangan TIDAK KOSONG
                if (!empty($ket)) {
                    $tglRaw = $request->termin_tanggal[$index] ?? null;
                    $jumlahInput = $request->termin_jumlah[$index] ?? '';
                    $jumlahFormatted = str_contains($jumlahInput, '%') ? $jumlahInput : $jumlahInput . '%';

                    $terminPembayaran[] = [
                        'keterangan' => $ket,
                        'tanggal' => $tglRaw,
                        'jumlah' => $jumlahFormatted
                    ];
                }
            }
        }

        // 3. Simpan ke Database
        $skp = Skp::create([
            'offer_id' => $offer->id,
            'no_surat' => $request->no_surat,
            'tanggal_surat' => $request->tanggal_surat,

            'pihak_satu_nama' => $request->pihak_satu_nama,
            'pihak_satu_jabatan' => $request->pihak_satu_jabatan ?? '-',
            'pihak_satu_perusahaan' => $request->pihak_satu_perusahaan ?? '-',
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
            'nilai_pekerjaan' => $offer->total_keseluruhan,

            'termin_pembayaran' => $terminPembayaran,
        ]);

        return redirect()->route('skp.index')->with('success', 'SKP berhasil dibuat!');
    }

    // ... Method index, show, destroy, edit, update lainnya ...

    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Skp::query()->with('offer');

        if ($search) {
            $query->where('no_surat', 'like', '%' . $search . '%')
                  ->orWhere('pihak_satu_nama', 'like', '%' . $search . '%')
                  ->orWhere('judul_pekerjaan', 'like', '%' . $search . '%');
        }

        $skps = $query->latest()->paginate(15);
        return view('skp.index', ['skps' => $skps, 'search' => $search]);
    }

    public function show(Skp $skp)
    {
        $skp->load('offer');
        return view('skp.show', ['skp' => $skp]);
    }

    public function destroy(Skp $skp)
    {
        $skp->delete();
        return redirect()->route('skp.index')->with('success', 'Surat Perintah Kerja berhasil dihapus!');
    }

    public function edit(Skp $skp)
    {
        $skp->load('offer');
        return view('skp.edit', ['skp' => $skp, 'offer' => $skp->offer]);
    }

    public function update(Request $request, Skp $skp)
    {
         $request->validate([
            'no_surat' => 'required',
            'tanggal_surat' => 'required|date',
            'pihak_satu_nama' => 'required',
            'pihak_satu_perusahaan' => 'nullable', // Opsional
            'pihak_satu_alamat' => 'required',
            'pihak_satu_jabatan' => 'nullable', // Opsional
            'pihak_dua_nama' => 'required',
            'pihak_dua_jabatan' => 'required',
            'judul_pekerjaan' => 'required',
            'lokasi_pekerjaan' => 'required',
            'durasi_hari' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'termin_keterangan' => 'nullable|array', // Opsional
            'termin_tanggal' => 'nullable|array',
            'termin_jumlah' => 'nullable|array',
        ]);

        $terminPembayaran = [];
        if ($request->has('termin_keterangan')) {
            foreach ($request->termin_keterangan as $index => $ket) {
                if (!empty($ket)) {
                    $tglRaw = $request->termin_tanggal[$index] ?? null;
                    $jumlahInput = $request->termin_jumlah[$index] ?? '';
                    $jumlahFormatted = str_contains($jumlahInput, '%') ? $jumlahInput : $jumlahInput . '%';

                    $terminPembayaran[] = [
                        'keterangan' => $ket,
                        'tanggal' => $tglRaw,
                        'jumlah' => $jumlahFormatted
                    ];
                }
            }
        }

        $skp->update([
            'no_surat' => $request->no_surat,
            'tanggal_surat' => $request->tanggal_surat,
            'pihak_satu_nama' => $request->pihak_satu_nama,
            'pihak_satu_jabatan' => $request->pihak_satu_jabatan ?? '-',
            'pihak_satu_perusahaan' => $request->pihak_satu_perusahaan ?? '-',
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
            'termin_pembayaran' => $terminPembayaran,
        ]);

        return redirect()->route('skp.index')->with('success', 'SKP berhasil diperbarui!');
    }
}