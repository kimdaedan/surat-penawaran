<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak SPK - {{ $skp->offer->nama_klien }}</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* Styling Khusus Print */
        @media print {
            @page {
                size: A4;
                margin: 2.5cm;
                /* Margin standar surat resmi */
            }

            body {
                background-color: white;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .no-print {
                display: none !important;
            }
        }

        /* Font Resmi */
        body {
            font-family: 'Times New Roman', Times, serif;
            color: black;
            line-height: 1.5;
        }

        .sans {
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
</head>

<body class="bg-white text-black text-sm" onload="window.print()">

    <div class="max-w-[21cm] mx-auto">

        {{-- 1. KOP SURAT --}}
        <header class="w-full mb-6">
    <div class="flex justify-between items-center w-full px-0">

        <div class="w-[22%] flex justify-start">
            <img src="{{ asset('images/logo-tasniem.png') }}" alt="Logo Tasniem" class="h-20 w-auto object-contain">
        </div>

        <div class="w-[61%] text-center">

            <h1 class="text-2xl font-extrabold text-[#1a237e] uppercase tracking-wide whitespace-nowrap leading-none mb-1"
                style="font-family: 'Times New Roman', Times, serif; transform: scaleY(1.1);">
                PT. TASNIEM GERAI INSPIRASI
            </h1>

            <p class="text-xs font-bold text-[#d32f2f]  mb-1"
               style="font-family: 'Times New Roman', Times, serif;">
                ( The First Inspiration Center of Jotun Indonesia )
            </p>

            <div class="text-[9px] font-bold text-[#1a237e] leading-tight font-sans">
                <p>Komp. Ruko KDA Junction Blok C 8 - 9 Batam Centre, Batam, Kepri - Indonesia</p>
                <p class="mt-0.5">Telp : +62 778-7485 999, Fax : +62 778-7485 789</p>
                <p class="mt-0.5">E-mail : tgi_team040210@yahoo.com &nbsp;&nbsp; Website : www.jotun.com/ap</p>
            </div>
        </div>

        <div class="w-[22%] flex justify-end">
            <img src="{{ asset('images/logo-jotun.png') }}" alt="Logo Jotun" class="h-30 w-auto object-contain">
        </div>
    </div>

    <div class="w-full border-b-[4px] border-[#d32f2f] mt-1"></div>
</header>

        {{-- 2. JUDUL SURAT --}}
        <div class="text-center mb-6">
            <h2 class="text-lg font-bold underline uppercase tracking-wide">SURAT PERINTAH KERJA</h2>
        </div>

        {{-- LOGIKA TANGGAL & FORMAT NOMOR --}}
        @php
        use Carbon\Carbon;

        $namaHari = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
        $namaBulan = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];

        $formatIndo = function($date, $pakaiHari = false) use ($namaHari, $namaBulan) {
        if (!$date) return '-';
        $d = Carbon::parse($date);
        $hari = $namaHari[$d->format('l')];
        $tgl = $d->format('d');
        $bln = $namaBulan[(int)$d->format('m')];
        $thn = $d->format('Y');
        return $pakaiHari ? "$hari, $tgl $bln $thn" : "$tgl $bln $thn";
        };

        // Helper Bulan Romawi
        $bulanRomawi = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'];

        $bulanOffer = (int) $skp->offer->created_at->format('n');
        $tahunOffer = $skp->offer->created_at->format('Y');

        $nomorSuratLengkap = sprintf('SP-%04d/SP/TGI-1/%s/%s', $skp->offer_id, $bulanRomawi[$bulanOffer], $tahunOffer);
        @endphp

        {{-- 3. ISI SURAT --}}
        <div class="text-justify leading-relaxed text-gray-900 space-y-4">

            <p>
                Berdasarkan Surat Penawaran Kerja Nomor: <strong>{{ $nomorSuratLengkap }}</strong>
                tertanggal <strong>{{ $formatIndo($skp->offer->created_at) }}</strong>,
                tentang <strong>{{ $skp->judul_pekerjaan }}</strong>,
                maka pada hari ini, <strong>{{ $formatIndo($skp->tanggal_surat, true) }}</strong>,
                kami yang bertanda tangan di bawah ini :
            </p>

            <div class="pl-4">
                <table class="w-full">
                    <tr>
                        <td class="w-32 align-top">Nama</td>
                        <td class="w-4 align-top">:</td>
                        <td class="align-top font-bold">{{ $skp->pihak_satu_nama }}</td>
                    </tr>
                    @if($skp->pihak_satu_perusahaan)
                    <tr>
                        <td class="align-top">Perusahaan</td>
                        <td class="align-top">:</td>
                        <td class="align-top">{{ $skp->pihak_satu_perusahaan }}</td>
                    </tr>
                    @endif
                    @if($skp->pihak_satu_jabatan)
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
                <p class="mt-1 pl-36 text-sm">yang selanjutnya disebut <strong>PIHAK I (pemberi pekerjaan).</strong></p>
            </div>

            <div class="pl-4 mt-4">
                <table class="w-full">
                    <tr>
                        <td class="w-32 align-top">Nama</td>
                        <td class="w-4 align-top">:</td>
                        <td class="align-top font-bold">{{ $skp->pihak_dua_nama }}</td>
                    </tr>
                    @if($skp->pihak_dua_perusahaan)
                    <tr>
                        <td class="align-top">Perusahaan</td>
                        <td class="align-top">:</td>
                        <td class="align-top">{{ $skp->pihak_dua_perusahaan }}</td>
                    </tr>
                    @endif
                    @if($skp->pihak_dua_jabatan)
                    <tr>
                        <td class="align-top">Jabatan</td>
                        <td class="align-top">:</td>
                        <td class="align-top">{{ $skp->pihak_dua_jabatan }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="align-top">Alamat</td>
                        <td class="align-top">:</td>
                        <td class="align-top">{{ $skp->pihak_dua_alamat }}</td>
                    </tr>
                </table>
                <p class="mt-1 pl-36 text-sm">yang selanjutnya disebut <strong>PIHAK II (penerima pekerjaan).</strong></p>
            </div>

            <p class="mt-6">bahwa Pihak I dengan ini memberikan perintah kepada Pihak II untuk melaksanakan ;</p>

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
                    <td class="align-top font-bold">Masa Pekerjaan</td>
                    <td class="align-top">:</td>
                    <td class="align-top">{{ $skp->durasi_hari }}</td>
                </tr>
                <tr>
                    <td class="font-bold">Terhitung</td>
                    <td class="align-top">:</td>
                    <td class="align-top">dari tanggal {{ $formatIndo($skp->tanggal_mulai) }} sampai dengan tanggal {{ $formatIndo($skp->tanggal_selesai) }}</td>
                </tr>
                <tr>
                    <td class="align-top font-bold">Total Nilai Pekerjaan</td>
                    <td class="align-top">:</td>
                    <td class="align-top font-bold">Rp {{ number_format($skp->nilai_pekerjaan, 0, ',', '.') }},-</td>
                </tr>

                @if($skp->termin_pembayaran && count($skp->termin_pembayaran) > 0)
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
                @endif
            </table>

            <div class="mt-6">
                <p class="font-bold underline mb-2">Syarat, ketentuan dan keterangan pelaksanaan pekerjaan :</p>
                <ol class="list-decimal list-outside ml-5 space-y-1 text-justify">
                    <li>Item pekerjaan, bobot prosentase pekerjaan, volume pekerjaan, gambar kerja dan Rencana Kerja dan Syarat – Syarat (RKS) merupakan bagian yang tidak terpisahkan dari Surat Perintah Kerja (SPK) ini.</li>
                    <li>Apabila dalam masa pelaksanaan pekerjaan ada perubahan – perubahan secara teknis, maka akan diatur dan dituangkan dalam bentuk SPK Addendum, yang akan diberitahukan oleh Pihak I.</li>
                </ol>
            </div>

            <p class="mt-6">Demikian Surat Perintah Kerja ini disusun untuk dipergunakan sebagaimana mestinya.</p>
        </div>

        {{-- 4. TANDA TANGAN --}}
        <div class="mt-16 flex justify-between text-center px-8">
            <div class="w-5/12">
                <p class="mb-24 font-bold">PIHAK I<br><span class="font-normal">Pemberi Pekerjaan</span></p>
                <p class="font-bold underline uppercase">{{ $skp->pihak_satu_nama }}</p>
            </div>
            <div class="w-5/12">
                <p class="mb-24 font-bold">PIHAK II<br><span class="font-normal">Penerima Pekerjaan</span></p>
                <p class="font-bold underline uppercase">{{ $skp->pihak_dua_nama }}</p>
            </div>
        </div>

    </div>

</body>

</html>