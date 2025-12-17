@extends('layouts.app')

@section('content')
{{-- LOGIKA TANGGAL INDONESIA --}}
@php
    use Carbon\Carbon;

    // Array Nama Hari & Bulan Indonesia
    $namaHari = [
        'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
    ];
    $namaBulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
        7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];

    // Fungsi Helper Lokal untuk Format Tanggal
    $formatIndo = function($date, $pakaiHari = false) use ($namaHari, $namaBulan) {
        if (!$date) return '-';
        $d = Carbon::parse($date);
        $hari = $namaHari[$d->format('l')];
        $tgl = $d->format('d');
        $bln = $namaBulan[(int)$d->format('m')];
        $thn = $d->format('Y');

        if ($pakaiHari) {
            return "$hari, $tgl $bln $thn";
        }
        return "$tgl $bln $thn";
    };
@endphp

<div class="container mx-auto my-12 px-4">

    <!-- Tombol Aksi (Hanya tampil di layar) -->
    <div class="max-w-5xl mx-auto mb-4 flex justify-between gap-2 print:hidden">
        <a href="{{ route('skp.index') }}" class="bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded hover:bg-gray-300 transition-colors">
            &larr; Kembali ke Histori
        </a>
        <button onclick="window.print()" class="bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 transition-colors">
            🖨️ Print Dokumen
        </button>
    </div>

    <!-- Area Dokumen Cetak -->
    <!-- ID 'dokumen-skp' ini yang akan kita isolasi saat print -->
    <div class="max-w-5xl mx-auto bg-white p-8 md:p-16 shadow-lg rounded-lg font-serif" id="dokumen-skp">

        <!-- KOP SURAT -->
        <header class="border-b-2 border-gray-800 pb-4 mb-8 flex justify-between items-center">
            <div class="w-1/4">
                {{-- Logo Kiri --}}
                <img src="{{ asset('images/logo-tasniem.png') }}" alt="Logo Tasniem" class="w-32">
            </div>
            <div class="w-1/2 text-center font-serif">
                <h1 class="text-xl font-bold text-gray-900 uppercase">PT. TASNIEM GERAI INSPIRASI</h1>
                <p class="text-sm font-semibold text-gray-700 italic">(The First Inspiration Center of Jotun Indonesia)</p>
                <p class="text-xs text-gray-600 mt-2">Komp. Ruko KDA Junction Blok C 8 - 9 Batam Centre, Batam</p>
                <p class="text-xs text-gray-600">Telp: +62 778-7485 999 | Email: tgi_team040210@yahoo.com</p>
            </div>
            <div class="w-1/4 flex justify-end">
                {{-- Logo Kanan --}}
                <img src="{{ asset('images/logo-jotun.png') }}" alt="Logo Jotun" class="w-40">
            </div>
        </header>

        <!-- JUDUL SURAT -->
        <div class="text-center mb-6">
            <h2 class="text-xl font-bold underline uppercase tracking-wide">SURAT PERINTAH KERJA</h2>
        </div>

        <!-- ISI SURAT -->
        <div class="text-justify leading-relaxed text-gray-900 text-base font-serif space-y-4">

            <p>
                Berdasarkan Surat Penawaran Kerja Nomor: <strong>SP-{{ $skp->offer_id }}</strong>
                tertanggal <strong>{{ $formatIndo($skp->offer->created_at) }}</strong>,
                tentang <strong>{{ $skp->judul_pekerjaan }}</strong>,
                maka pada hari ini, <strong>{{ $formatIndo($skp->tanggal_surat, true) }}</strong>,
                kami yang bertanda tangan di bawah ini :
            </p>

            <!-- PIHAK I -->
            <div class="pl-4">
                <table class="w-full">
                    <tr>
                        <td class="w-32 align-top">Nama</td>
                        <td class="w-4 align-top">:</td>
                        <td class="align-top font-bold">{{ $skp->pihak_satu_nama }}</td>
                    </tr>
                    <tr>
                        <td class="align-top">Perusahaan</td>
                        <td class="align-top">:</td>
                        <td class="align-top">{{ $skp->pihak_satu_perusahaan }}</td>
                    </tr>
                    @if($skp->pihak_satu_jabatan && $skp->pihak_satu_jabatan != '-')
                    <tr>
                        <td class="align-top">Jabatan</td>
                        <td class="align-top">:</td>
                        <td class="align-top">{{ $skp->pihak_satu_jabatan }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="align-top">Alamat</td>
                        <td class="align-top">:</td>
                        <td class="align-top">{{ $skp->pihak_satu_alamat }}</td>
                    </tr>
                </table>
                <p class="mt-1 pl-36">yang selanjutnya disebut <strong>PIHAK I (pemberi pekerjaan).</strong></p>
            </div>

            <!-- PIHAK II -->
            <div class="pl-4 mt-4">
                <table class="w-full">
                    <tr>
                        <td class="w-32 align-top">Nama</td>
                        <td class="w-4 align-top">:</td>
                        <td class="align-top font-bold">{{ $skp->pihak_dua_nama }}</td>
                    </tr>
                    <tr>
                        <td class="align-top">Perusahaan</td>
                        <td class="align-top">:</td>
                        <td class="align-top">{{ $skp->pihak_dua_perusahaan }}</td>
                    </tr>
                    <tr>
                        <td class="align-top">Jabatan</td>
                        <td class="align-top">:</td>
                        <td class="align-top">{{ $skp->pihak_dua_jabatan }}</td>
                    </tr>
                    <tr>
                        <td class="align-top">Alamat</td>
                        <td class="align-top">:</td>
                        <td class="align-top">{{ $skp->pihak_dua_alamat }}</td>
                    </tr>
                </table>
                <p class="mt-1 pl-36">yang selanjutnya disebut <strong>PIHAK II (penerima pekerjaan).</strong></p>
            </div>

            <p class="mt-6">bahwa Pihak I dengan ini memberikan perintah kepada Pihak II untuk melaksanakan ;</p>

            <!-- RINCIAN PEKERJAAN -->
            <table class="w-full mt-2 ml-4">
                <tr>
                    <td class="w-40 align-top font-bold">Pekerjaan</td>
                    <td class="w-4 align-top">:</td>
                    <td class="align-top">{{ $skp->judul_pekerjaan }}</td>
                </tr>
                <tr>
                    <td class="align-top font-bold">Lokasi</td>
                    <td class="align-top">:</td>
                    <td class="align-top">{{ $skp->lokasi_pekerjaan }}</td>
                </tr>
                <tr>
                    <td class="align-top font-bold">Masa Pelaksanaan Pek.</td>
                    <td class="align-top">:</td>
                    <td class="align-top">
                        {{ $skp->durasi_hari }} <br>
                        dari tanggal {{ $formatIndo($skp->tanggal_mulai) }} sampai dengan tanggal {{ $formatIndo($skp->tanggal_selesai) }}
                    </td>
                </tr>
                <tr>
                    <td class="align-top font-bold">Total Nilai Pekerjaan</td>
                    <td class="align-top">:</td>
                    <td class="align-top font-bold">Rp {{ number_format($skp->nilai_pekerjaan, 0, ',', '.') }},-</td>
                </tr>
                <tr>
                    <td class="align-top font-bold">Sistem Pembayaran</td>
                    <td class="align-top">:</td>
                    <td class="align-top">
                        <ul class="list-none pl-0 m-0">
                            @foreach($skp->termin_pembayaran as $termin)
                                <li class="mb-1">
                                    {{ $termin['keterangan'] }}
                                    @if(!empty($termin['tanggal']) && $termin['tanggal'] != '-')
                                        ({{ $formatIndo($termin['tanggal']) }})
                                    @endif
                                    , dibayarkan {{ $termin['jumlah'] }}
                                </li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
            </table>

            <!-- SYARAT & KETENTUAN -->
            <div class="mt-6">
                <p class="font-bold underline mb-2">Syarat, ketentuan dan keterangan pelaksanaan pekerjaan :</p>
                <ol class="list-decimal list-outside ml-5 space-y-1 text-justify">
                    <li>Item pekerjaan, bobot prosentase pekerjaan, volume pekerjaan, gambar kerja dan Rencana Kerja dan Syarat – Syarat (RKS) merupakan bagian yang tidak terpisahkan dari Surat Perintah Kerja (SPK) ini.</li>
                    <li>Apabila dalam masa pelaksanaan pekerjaan ada perubahan – perubahan secara teknis, maka akan diatur dan dituangkan dalam bentuk SPK Addendum, yang akan diberitahukan oleh Pihak I.</li>
                </ol>
            </div>

            <p class="mt-6">Demikian Surat Perintah Kerja ini disusun untuk dipergunakan sebagaimana mestinya.</p>
        </div>

        <!-- TANDA TANGAN -->
        <div class="mt-16 flex justify-between text-center px-8 font-serif">
            <div class="w-1/2">
                <p class="mb-24 font-bold">PIHAK I<br><span class="font-normal">Pemberi Pekerjaan</span></p>
                <p class="font-bold underline uppercase">{{ $skp->pihak_satu_nama }}</p>
            </div>
            <div class="w-1/2">
                <p class="mb-24 font-bold">PIHAK II<br><span class="font-normal">Penerima Pekerjaan</span></p>
                <p class="font-bold underline uppercase">{{ $skp->pihak_dua_nama }}</p>
            </div>
        </div>

    </div>
</div>

<!-- CSS KHUSUS PRINT -->
<style>
    /* Mengatur Font Times New Roman untuk seluruh area dokumen */
    .font-serif, #dokumen-skp, #dokumen-skp * {
        font-family: 'Times New Roman', Times, serif !important;
    }

    @media print {
        /* Sembunyikan elemen body utama */
        body * {
            visibility: hidden;
        }

        /* Tampilkan HANYA dokumen SKP dan isinya */
        #dokumen-skp, #dokumen-skp * {
            visibility: visible;
        }

        /* Atur posisi dokumen agar di pojok kiri atas kertas */
        #dokumen-skp {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 0;
            box-shadow: none !important; /* Hilangkan bayangan */
            border: none !important; /* Hilangkan border container */
        }

        /* Hilangkan elemen navigasi/tombol yang mungkin terlewat */
        .print\:hidden {
            display: none !important;
        }

        /* Reset background */
        body {
            background-color: white !important;
        }

        /* Setup Halaman A4 */
        @page {
            size: A4 portrait;
            margin: 2cm; /* Margin standar surat resmi */
        }
    }
</style>
@endsection