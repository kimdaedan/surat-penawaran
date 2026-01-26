@extends('layouts.app')

@section('content')
<div class="container mx-auto my-12 px-4">
    <div class="max-w-5xl mx-auto bg-white p-8 md:p-12 shadow-lg rounded-lg">

        <div class="flex justify-between items-center mb-8 print:hidden">
            <h1 class="text-2xl font-bold text-gray-800">Edit Surat Perintah Kerja (SKP)</h1>
            <a href="{{ route('skp.index') }}" class="text-gray-600 hover:text-gray-900 font-medium">
                &larr; Batal
            </a>
        </div>

        <form action="{{ route('skp.update', $skp->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- JUDUL -->
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold underline uppercase tracking-wide">SURAT PERINTAH KERJA</h2>
            </div>

            <!-- PREAMBULE -->
            <div class="bg-gray-50 p-4 rounded border mb-6 text-sm text-gray-700 leading-relaxed">
                <p>
                    {{--
                        Logic untuk membuat Ref ID Penawaran yang Lengkap
                        (Karena di edit kita mungkin tidak pass variable refNoPenawaran, kita buat manual di sini)
                    --}}
                    @php
                    $bulanRomawi = array("", "I","II","III","IV","V","VI","VII","VIII","IX","X","XI","XII");
                    $bulanOffer = $bulanRomawi[$offer->created_at->format('n')];
                    $tahunOffer = $offer->created_at->format('Y');
                    $refNoPenawaranLengkap = sprintf('00%d/SP/TGI-1/%s/%s', $offer->id, $bulanOffer, $tahunOffer);
                    @endphp

                    Berdasarkan Surat Penawaran Kerja (Ref ID): <strong>{{ $refNoPenawaranLengkap }}</strong>
                    tertanggal <strong>{{ $offer->created_at->format('d F Y') }}</strong>,
                    tentang Pekerjaan:
                </p>

                {{-- DROPDOWN EDIT --}}
                <select name="judul_pekerjaan" class="w-full mt-2 border-b border-gray-400 bg-transparent focus:outline-none font-semibold py-1 cursor-pointer hover:bg-gray-100 transition" required>
                    <option value="" disabled>-- Pilih Jenis Pekerjaan --</option>
                    <option value="Renovasi dan Pengecatan Interior" {{ $skp->judul_pekerjaan == 'Renovasi dan Pengecatan Interior' ? 'selected' : '' }}>Renovasi dan Pengecatan Interior</option>
                    <option value="Renovasi dan Pengecatan Exterior" {{ $skp->judul_pekerjaan == 'Renovasi dan Pengecatan Exterior' ? 'selected' : '' }}>Renovasi dan Pengecatan Exterior</option>
                    <option value="Renovasi dan Pengecatan Interior & Exterior" {{ $skp->judul_pekerjaan == 'Renovasi dan Pengecatan Interior & Exterior' ? 'selected' : '' }}>Renovasi dan Pengecatan Interior & Exterior</option>
                </select>

                <p class="mt-4">
                    Maka pada hari ini, tanggal
                    <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat', $skp->tanggal_surat->format('Y-m-d')) }}" class="border rounded px-2 py-1 bg-white">,
                    kami yang bertanda tangan di bawah ini :
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- PIHAK I -->
                <div class="border p-4 rounded bg-blue-50 border-blue-200">
                    <h3 class="font-bold text-blue-800 mb-4 border-b border-blue-300 pb-2">PIHAK I (Pemberi Pekerjaan)</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase">Nama</label>
                            <input type="text" name="pihak_satu_nama" value="{{ old('pihak_satu_nama', $skp->pihak_satu_nama) }}" class="w-full border rounded px-2 py-1 focus:border-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase">Perusahaan (Opsional)</label>
                            <input type="text" name="pihak_satu_perusahaan" value="{{ old('pihak_satu_perusahaan', $skp->pihak_satu_perusahaan) }}" placeholder="Tidak wajib diisi" class="w-full border rounded px-2 py-1 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase">Jabatan (Opsional)</label>
                            <input type="text" name="pihak_satu_jabatan" value="{{ old('pihak_satu_jabatan', $skp->pihak_satu_jabatan) }}" placeholder="Tidak wajib diisi" class="w-full border rounded px-2 py-1 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase">Alamat</label>
                            <textarea name="pihak_satu_alamat" id="alamat_pihak_satu" rows="2" class="w-full border rounded px-2 py-1 focus:border-blue-500" required>{{ old('pihak_satu_alamat', $skp->pihak_satu_alamat) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- PIHAK II -->
                <div class="border p-4 rounded bg-green-50 border-green-200">
                    <h3 class="font-bold text-green-800 mb-4 border-b border-green-300 pb-2">PIHAK II (Penerima Pekerjaan)</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase">Nama</label>
                            <input type="text" name="pihak_dua_nama" value="{{ old('pihak_dua_nama', $skp->pihak_dua_nama) }}" class="w-full border rounded px-2 py-1 focus:border-green-500" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase">Perusahaan (Opsional)</label>
                            <input type="text" name="pihak_dua_perusahaan" value="{{ old('pihak_dua_perusahaan', $skp->pihak_dua_perusahaan) }}" class="w-full border rounded px-2 py-1 focus:border-green-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase">Jabatan (Opsional)</label>
                            <input type="text" name="pihak_dua_jabatan" value="{{ old('pihak_dua_jabatan', $skp->pihak_dua_jabatan) }}" class="w-full border rounded px-2 py-1 focus:border-green-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase">Alamat</label>
                            <textarea name="pihak_dua_alamat" rows="2" class="w-full border rounded px-2 py-1 bg-gray-100" readonly>{{ $skp->pihak_dua_alamat }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <p class="mt-6 text-sm text-gray-600 text-center">bahwa Pihak I dengan ini memberikan perintah kepada Pihak II untuk melaksanakan ;</p>

            <!-- DETAIL PELAKSANAAN -->
            <fieldset class="border-t pt-6 mt-4">
                <legend class="text-lg font-semibold text-gray-700 px-2">Detail Pelaksanaan</legend>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-2">
                    <div>
                        <label class="block text-sm font-bold text-gray-700">Nomor Surat SPK</label>
                        <input type="text" name="no_surat" value="{{ old('no_surat', $skp->no_surat) }}" class="w-full border rounded px-3 py-2 mt-1 bg-gray-100" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700">Lokasi Pekerjaan</label>
                        <input type="text" name="lokasi_pekerjaan" id="lokasi_pekerjaan" value="{{ old('lokasi_pekerjaan', $skp->lokasi_pekerjaan) }}" class="w-full border rounded px-3 py-2 mt-1 bg-gray-5 focus:bg-white transition" required>
                        <p class="text-xs text-gray-500 mt-1">Otomatis diisi dari Alamat Pihak I (bisa diedit manual)</p>
                    </div>

                    <div class="md:col-span-2 bg-gray-50 p-4 rounded border grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700">Durasi (Hari Kalender)</label>
                            {{-- Kita coba ambil angka dari teks durasi lama (misal "60 hari" -> 60) --}}
                            @php
                            preg_match('/\d+/', $skp->durasi_hari, $matches);
                            $durasiAngka = $matches[0] ?? '';
                            @endphp
                            <input type="number" id="durasi_angka" class="w-full border rounded px-3 py-2 mt-1" value="{{ $durasiAngka }}" placeholder="Contoh: 60" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700">Dari Tanggal</label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai', $skp->tanggal_mulai->format('Y-m-d')) }}" class="w-full border rounded px-3 py-2 mt-1" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700">Sampai Tanggal</label>
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai', $skp->tanggal_selesai->format('Y-m-d')) }}" class="w-full border rounded px-3 py-2 mt-1 bg-gray-200 cursor-not-allowed" readonly>
                            <p class="text-xs text-blue-600 mt-1 font-semibold" id="info_selesai">Otomatis dihitung...</p>
                        </div>
                        <input type="hidden" name="durasi_hari" id="durasi_hari_text" value="{{ old('durasi_hari', $skp->durasi_hari) }}">
                    </div>
                </div>

                <div class="mt-4 bg-yellow-50 p-4 rounded border border-yellow-200">
                    <p class="font-bold text-gray-800">Total Nilai Pekerjaan (Otomatis dari Penawaran):</p>
                    <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($offer->total_keseluruhan, 0, ',', '.') }}</p>
                </div>
            </fieldset>

            <fieldset class="border-t pt-6 mt-8">
                <legend class="text-lg font-semibold text-gray-700 px-2">Sistem Pembayaran (Opsional)</legend>
                <p class="text-sm text-gray-500 mb-4">Tambahkan baris jika diperlukan. Jika tidak, biarkan kosong.</p>

                <div class="bg-gray-50 p-4 rounded border">
                    <table class="w-full text-left" id="payment-table">
                        <thead>
                            <tr>
                                <th class="pb-2 text-sm text-gray-600 w-5/12">Keterangan Progres</th>
                                <th class="pb-2 text-sm text-gray-600 w-3/12">Tanggal</th>
                                <th class="pb-2 text-sm text-gray-600 w-3/12">Amount (Jumlah)</th>
                                <th class="pb-2 w-1/12"></th>
                            </tr>
                        </thead>
                        <tbody id="payment-rows">
                            <tr>
                                <td class="pr-2 pb-2">
                                    <input type="text" name="termin_keterangan[]" placeholder="Contoh: DP / Progres I" class="w-full border rounded px-3 py-2 focus:border-blue-500">
                                </td>
                                <td class="pr-2 pb-2">
                                    <input type="date" name="termin_tanggal[]" class="w-full border rounded px-3 py-2 focus:border-blue-500">
                                </td>
                                <td class="pr-2 pb-2 relative">
                                    <span class="absolute left-3 top-2.5 text-gray-500 font-semibold">Rp</span>
                                    <input type="number" name="termin_jumlah[]" placeholder="0" class="w-full border rounded px-3 py-2 pl-10 focus:border-blue-500 text-right">
                                </td>
                                <td class="pb-2 text-center">
                                    <button type="button" class="text-red-500 hover:text-red-700 remove-row" disabled>x</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <button type="button" id="add-payment-btn" class="mt-2 text-sm bg-blue-100 text-blue-700 px-3 py-1 rounded hover:bg-blue-200 font-bold flex items-center gap-1">
                        <span>+</span> Tambah Tahap Pembayaran
                    </button>
                </div>
            </fieldset>

            <div class="mt-12 text-center">
                <button type="submit" class="bg-indigo-600 text-white font-bold py-3 px-10 rounded-full shadow-lg hover:bg-indigo-700 transition transform hover:-translate-y-1">
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // --- 1. OTOMATISASI LOKASI PEKERJAAN ---
        const alamatSumber = document.getElementById('alamat_pihak_satu');
        const lokasiTujuan = document.getElementById('lokasi_pekerjaan');

        alamatSumber.addEventListener('input', function() {
            lokasiTujuan.value = this.value;
        });

        // --- 2. OTOMATISASI TANGGAL & DURASI ---
        const durasiInput = document.getElementById('durasi_angka');
        const mulaiInput = document.getElementById('tanggal_mulai');
        const selesaiInput = document.getElementById('tanggal_selesai');
        const durasiTextHidden = document.getElementById('durasi_hari_text');
        const infoSelesai = document.getElementById('info_selesai');

        function hitungSelesai() {
            const hari = parseInt(durasiInput.value);
            const mulaiStr = mulaiInput.value;

            if (!isNaN(hari) && mulaiStr) {
                const mulai = new Date(mulaiStr);
                const selesai = new Date(mulai);
                selesai.setDate(mulai.getDate() + hari);

                const yyyy = selesai.getFullYear();
                const mm = String(selesai.getMonth() + 1).padStart(2, '0');
                const dd = String(selesai.getDate()).padStart(2, '0');

                selesaiInput.value = `${yyyy}-${mm}-${dd}`;
                durasiTextHidden.value = `${hari} hari kalender`;

                const options = {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                };
                infoSelesai.innerText = `Selesai pada: ${selesai.toLocaleDateString('id-ID', options)}`;
            }
        }

        durasiInput.addEventListener('input', hitungSelesai);
        mulaiInput.addEventListener('change', hitungSelesai);

        // --- 3. TABEL PEMBAYARAN DINAMIS ---
        // --- 3. TABEL PEMBAYARAN DINAMIS ---
        const tableBody = document.getElementById('payment-rows');
        const addBtn = document.getElementById('add-payment-btn');

        addBtn.addEventListener('click', function() {
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
        <td class="pr-2 pb-2">
            <input type="text" name="termin_keterangan[]" placeholder="Keterangan progres..." class="w-full border rounded px-3 py-2 focus:border-blue-500">
        </td>
        <td class="pr-2 pb-2">
            <input type="date" name="termin_tanggal[]" class="w-full border rounded px-3 py-2 focus:border-blue-500">
        </td>
        <td class="pr-2 pb-2 relative">
            <span class="absolute left-3 top-2.5 text-gray-500 font-semibold">Rp</span>
            <input type="number" name="termin_jumlah[]" placeholder="0" class="w-full border rounded px-3 py-2 pl-10 focus:border-blue-500 text-right">
        </td>
        <td class="pb-2 text-center">
            <button type="button" class="text-red-500 hover:text-red-700 remove-row text-xl font-bold">&times;</button>
        </td>
    `;
            tableBody.appendChild(newRow);
        });

        tableBody.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-row')) {
                if (tableBody.rows.length > 1) {
                    e.target.closest('tr').remove();
                } else {
                    alert('Minimal satu tahap pembayaran harus ada.');
                }
            }
        });
    });
</script>
@endsection