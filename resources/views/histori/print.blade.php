<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Penawaran - {{ $offer->nama_klien }}</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* CSS KHUSUS PRINT A4 */
        @media print {
            @page {
                size: A4;
                margin: 0; /* Margin dikontrol via padding di container agar presisi */
            }

            body {
                background-color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                font-family: 'Times New Roman', Times, serif !important;
                font-size: 11pt;
            }

            .no-print { display: none !important; }

            /* Paksa Kontainer mengikuti ukuran A4 Murni */
            #print-paper {
                width: 210mm;
                min-height: 297mm;
                padding: 15mm 20mm !important; /* Standar margin surat resmi */
                margin: 0 auto !important;
                box-shadow: none !important;
                border: none !important;
            }

            /* LOGIKA PRINT TANPA KOP */
            .hide-header-on-print .invoice-header {
                display: none !important;
            }
            .hide-header-on-print #print-paper {
                padding-top: 65mm !important; /* Jarak pas agar teks mulai di bawah Kop Fisik */
            }

            /* Mencegah Tanda Tangan terpisah dari kalimat penutup di halaman berbeda */
            .signature-wrapper {
                page-break-inside: avoid;
            }

            /* Optimasi Tabel agar tidak berantakan saat pindah halaman */
            table { page-break-inside: auto; width: 100%; border-collapse: collapse; }
            tr { page-break-inside: avoid; page-break-after: auto; }
            thead { display: table-header-group; }

            .border-print { border: 1px solid #000 !important; }
        }

        /* Gaya Tampilan di Browser (Preview Mode) */
        body {
            background-color: #f4f4f4;
            font-family: 'Times New Roman', Times, serif;
        }

        #print-paper {
            background-color: white;
            width: 210mm;
            min-height: 297mm;
            margin: 30px auto;
            padding: 20mm;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            position: relative;
        }

        .nav-floating {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 100;
            display: flex;
            gap: 10px;
        }

        .sans { font-family: Arial, Helvetica, sans-serif; }
    </style>
</head>

<body class="text-black text-sm">

    {{-- Navigasi Terapung (Hanya muncul di layar) --}}
    <div class="nav-floating no-print">
        <button onclick="printWithHeader()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-full shadow-xl flex items-center gap-2 transition transform hover:scale-105">
            <span>🖨️</span> Cetak Normal
        </button>
        <button onclick="printWithoutHeader()" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-6 rounded-full shadow-xl flex items-center gap-2 transition transform hover:scale-105">
            <span>📄</span> Tanpa Kop Surat
        </button>
        <button onclick="window.close()" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-full shadow-lg transition">
            Tutup
        </button>
    </div>

    <div id="print-content" class="max-w-[21cm] mx-auto p-12 print:m-0 print:shadow-none">

        {{-- HEADER KOP SURAT --}}
        <header class="w-full mb-6 invoice-header">
            <div class="flex justify-between items-center w-full px-0">
                <div class="w-[22%] flex justify-start">
                    <img src="{{ asset('images/logo-tasniem.png') }}" alt="Logo Tasniem" class="h-20 w-auto object-contain">
                </div>

                <div class="w-[61%] text-center">
                    <h1 class="text-2xl font-extrabold text-[#1a237e] uppercase tracking-wide whitespace-nowrap leading-none mb-1"
                        style="transform: scaleY(1.1);">
                        PT. TASNIEM GERAI INSPIRASI
                    </h1>
                    <p class="text-xs font-bold text-[#d32f2f] mb-1">
                        ( The First Inspiration Center of Jotun Indonesia )
                    </p>
                    <div class="text-[9px] font-bold text-[#1a237e] leading-tight sans">
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

        {{-- KONTEN PENAWARAN --}}
        <section class="mb-6 text-sm sans">
            @php
            $bulanRomawi = [1=>'I', 2=>'II', 3=>'III', 4=>'IV', 5=>'V', 6=>'VI', 7=>'VII', 8=>'VIII', 9=>'IX', 10=>'X', 11=>'XI', 12=>'XII'];
            $romawi = $bulanRomawi[$offer->created_at->format('n')];
            $tahun = $offer->created_at->format('Y');
            @endphp
            <p>Nomor : 00{{ $offer->id }}/SP/TGI-1/{{ $romawi }}/{{ $tahun }}</p>
            <p>Batam, {{ $offer->created_at->format('d F Y') }}</p>
        </section>

        <section class="mt-8 text-sm sans">
            <p class="text-gray-600">Kepada Yth,</p>
            <h3 class="text-md font-bold text-gray-800 uppercase">{{ $offer->nama_klien }}</h3>
            @if($offer->client_details)
            <p class="text-sm text-gray-700">{{ $offer->client_details }}</p>
            @endif
            <p class="text-gray-700 mt-4">Dengan Hormat,</p>
        </section>

        <section class="mt-4 space-y-4 text-sm leading-relaxed text-justify sans">
            <p>Kami <strong>PT. TASNIEM GERAI INSPIRASI</strong> adalah dealer resmi PT. JOTUN INDONESIA. Dengan ini kami sampaikan penawaran Upah Jasa pengecatan sebagai berikut:</p>
            <div>
                <p>Kami PT Tasniem Gerai Inspirasi begerak di bidang Painting Dan Pekerjaan Sipil lainnya :</p>
                <ol class="list-decimal list-inside ml-4">
                    <li>Pekerjaan pengecatan dan perawatan gedung</li>
                    <li>Pemasangan partisi dan plafon Finising gypsum dan plafon sunda Plafon</li>
                    <li>Pekerjaan Pengecatan Lantai epoxy</li>
                </ol>
            </div>
            <p>Dengan ini kami sampaikan penawaran Upah Jasa pengecatan :</p>
        </section>

        {{-- PHP LOGIC --}}
        @php
        $isSplit = $offer->pisah_kriteria_total;
        $showTotal = !$offer->hilangkan_grand_total;
        $totalJasa = $offer->jasaItems->sum('harga_jasa');

        $exteriorItems = collect();
        $interiorItems = collect();
        $totalExterior = 0;
        $totalInterior = 0;

        if ($isSplit) {
        $exteriorItems = $offer->items->filter(function($item) {
        $prod = \App\Models\Product::where('nama_produk', $item->nama_produk)->first();
        return $prod && $prod->kriteria == 'Exterior';
        });
        $totalExterior = $exteriorItems->sum(function($item) { return $item->volume * $item->harga_per_m2; });

        $interiorItems = $offer->items->filter(function($item) {
        $prod = \App\Models\Product::where('nama_produk', $item->nama_produk)->first();
        return !$prod || $prod->kriteria != 'Exterior';
        });
        $totalInterior = $interiorItems->sum(function($item) { return $item->volume * $item->harga_per_m2; });
        }
        @endphp

        <section class="mt-8 sans text-sm">
            <div class="w-full">

                {{-- KASUS 1: SPLIT --}}
                @if($isSplit)

                {{-- EXTERIOR --}}
                @if($exteriorItems->isNotEmpty())
                <div class="mb-8 page-break-inside-avoid">
                    <h4 class="font-bold text-gray-800 mb-2 uppercase border-b-2 border-gray-800 inline-block text-sm">Pekerjaan Exterior</h4>
                    <table class="w-full text-left border-collapse mb-4">
                        <thead class="bg-gray-200 text-black">
                            <tr>
                                <th class="py-2 px-1 font-semibold uppercase text-xs w-[25%] align-middle">Area Pekerjaan</th>
                                <th class="py-2 px-1 font-semibold uppercase text-xs w-[10%] align-middle">Nama Brand</th>
                                <th class="py-2 px-1 font-semibold uppercase text-xs w-[15%] align-middle">Produk</th>
                                <th class="py-2 px-1 font-semibold uppercase text-xs text-right w-[10%] align-middle">Volume/M²</th>
                                <th class="py-2 px-1 font-semibold uppercase text-xs text-right w-[20%] align-middle">Harga Satuan</th>
                                <th class="py-2 px-1 font-semibold uppercase text-xs text-right w-[20%] align-middle">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($exteriorItems as $item)
                            <tr class="border-b border-gray-500">
                                <td class="py-0.5 px-1 text-xs text-gray-700 leading-none align-middle">{{ $item->area_dinding }}</td>
                                <td class="py-0.5 px-1 text-xs text-gray-700 leading-none align-middle">
                                    @php $p = \App\Models\Product::where('nama_produk', $item->nama_produk)->first(); @endphp
                                    {{ $p->performa ?? '-' }}
                                </td>
                                <td class="py-0.5 px-1 text-xs text-gray-700 leading-none align-middle">{{ $item->nama_produk }}</td>
                                <td class="py-0.5 px-1 text-xs text-gray-700 leading-none text-right whitespace-nowrap align-middle">{{ $item->volume }}</td>
                                <td class="py-0.5 px-1 text-xs text-gray-700 leading-none whitespace-nowrap align-middle">
                                    <div class="flex justify-end gap-1 w-full"><span>Rp</span><span>{{ number_format($item->harga_per_m2, 0, ',', '.') }}</span></div>
                                </td>
                                <td class="py-0.5 px-1 text-xs text-gray-700 leading-none whitespace-nowrap font-medium align-middle">
                                    <div class="flex justify-end gap-1 w-full"><span>Rp</span><span>{{ number_format($item->volume * $item->harga_per_m2, 0, ',', '.') }}</span></div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        @if($showTotal)
                        <tfoot>
                            <tr class="bg-gray-100 font-bold text-gray-800">
                                <td colspan="5" class="py-1 px-1 text-xs text-right uppercase align-middle">Total Exterior</td>
                                <td class="py-1 px-1 text-xs text-right whitespace-nowrap align-middle">
                                    <div class="flex justify-end gap-1 w-full"><span>Rp</span><span>{{ number_format($totalExterior, 0, ',', '.') }}</span></div>
                                </td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
                @endif

                {{-- INTERIOR --}}
                @if($interiorItems->isNotEmpty())
                <div class="mb-8 page-break-inside-avoid">
                    <h4 class="font-bold text-gray-800 mb-2 uppercase border-b-2 border-gray-800 inline-block text-sm">Pekerjaan Interior</h4>
                    <table class="w-full text-left border-collapse mb-4">
                        <thead class="bg-gray-200 text-black">
                            <tr>
                                <th class="py-2 px-1 font-semibold uppercase text-xs w-[25%] align-middle">Area Pekerjaan</th>
                                <th class="py-2 px-1 font-semibold uppercase text-xs w-[10%] align-middle">Nama Brand</th>
                                <th class="py-2 px-1 font-semibold uppercase text-xs w-[15%] align-middle">Produk</th>
                                <th class="py-2 px-1 font-semibold uppercase text-xs text-right w-[10%] align-middle">Volume/M²</th>
                                <th class="py-2 px-1 font-semibold uppercase text-xs text-right w-[20%] align-middle">Harga Satuan</th>
                                <th class="py-2 px-1 font-semibold uppercase text-xs text-right w-[20%] align-middle">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($interiorItems as $item)
                            <tr class="border-b border-gray-500">
                                <td class="py-0.5 px-1 text-xs text-gray-700 leading-none align-middle">{{ $item->area_dinding }}</td>
                                <td class="py-0.5 px-1 text-xs text-gray-700 leading-none align-middle">
                                    @php $p = \App\Models\Product::where('nama_produk', $item->nama_produk)->first(); @endphp
                                    {{ $p->performa ?? '-' }}
                                </td>
                                <td class="py-0.5 px-1 text-xs text-gray-700 leading-none align-middle">{{ $item->nama_produk }}</td>
                                <td class="py-0.5 px-1 text-xs text-gray-700 leading-none text-right whitespace-nowrap align-middle">{{ $item->volume }}</td>
                                <td class="py-0.5 px-1 text-xs text-gray-700 leading-none whitespace-nowrap align-middle">
                                    <div class="flex justify-end gap-1 w-full"><span>Rp</span><span>{{ number_format($item->harga_per_m2, 0, ',', '.') }}</span></div>
                                </td>
                                <td class="py-0.5 px-1 text-xs text-gray-700 leading-none whitespace-nowrap font-medium align-middle">
                                    <div class="flex justify-end gap-1 w-full"><span>Rp</span><span>{{ number_format($item->volume * $item->harga_per_m2, 0, ',', '.') }}</span></div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        @if($showTotal)
                        <tfoot>
                            <tr class="bg-gray-100 font-bold text-gray-800">
                                <td colspan="5" class="py-1 px-1 text-xs text-right uppercase align-middle">Total Interior</td>
                                <td class="py-1 px-1 text-xs text-right whitespace-nowrap align-middle">
                                    <div class="flex justify-end gap-1 w-full"><span>Rp</span><span>{{ number_format($totalInterior, 0, ',', '.') }}</span></div>
                                </td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
                @endif

                {{-- KASUS 2: DEFAULT --}}
                @else
                <table class="w-full text-left border-collapse page-break-inside-avoid mb-6">
                    <thead class="bg-gray-200 text-black">
                        <tr>
                            <th class="py-2 px-1 font-semibold uppercase text-xs w-[25%] align-middle">Area Pekerjaan</th>
                            <th class="py-2 px-1 font-semibold uppercase text-xs w-[10%] align-middle">Nama Brand</th>
                            <th class="py-2 px-1 font-semibold uppercase text-xs w-[15%] align-middle">Produk</th>
                            <th class="py-2 px-1 font-semibold uppercase text-xs text-right w-[10%] align-middle">Volume/M²</th>
                            <th class="py-2 px-1 font-semibold uppercase text-xs text-right w-[20%] align-middle">Harga Satuan</th>
                            <th class="py-2 px-1 font-semibold uppercase text-xs text-right w-[20%] align-middle">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($offer->items as $item)
                        <tr class="border-b border-gray-500">
                            <td class="py-0.5 px-1 text-xs text-gray-700 leading-none align-middle">{{ $item->area_dinding }}</td>
                            <td class="py-0.5 px-1 text-xs text-gray-700 leading-none align-middle">
                                @php $p = \App\Models\Product::where('nama_produk', $item->nama_produk)->first(); @endphp
                                {{ $p->performa ?? '-' }}
                            </td>
                            <td class="py-0.5 px-1 text-xs text-gray-700 leading-none align-middle">{{ $item->nama_produk }}</td>
                            <td class="py-0.5 px-1 text-xs text-gray-700 leading-none text-right whitespace-nowrap align-middle">{{ $item->volume }}</td>
                            <td class="py-0.5 px-1 text-xs text-gray-700 leading-none whitespace-nowrap align-middle">
                                <div class="flex justify-end gap-1 w-full"><span>Rp</span><span>{{ number_format($item->harga_per_m2, 0, ',', '.') }}</span></div>
                            </td>
                            <td class="py-0.5 px-1 text-xs text-gray-700 leading-none whitespace-nowrap font-medium align-middle">
                                <div class="flex justify-end gap-1 w-full"><span>Rp</span><span>{{ number_format($item->volume * $item->harga_per_m2, 0, ',', '.') }}</span></div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif

                {{-- TABEL JASA (DENGAN TOTAL) --}}
                @if($offer->jasaItems->isNotEmpty())
                <div class="mt-4 page-break-inside-avoid">
                    @if($isSplit)
                    <h4 class="font-bold text-gray-800 mb-2 uppercase border-b-2 border-gray-800 inline-block text-sm">Pengerjaan Tambahan</h4>
                    @endif
                    <table class="w-full text-left border-collapse">
                        @if($isSplit)
                        <thead class="bg-gray-200 text-black">
                            <tr>
                                <th class="py-2 px-1 font-semibold uppercase text-xs w-[50%] align-middle" colspan="3">Deskripsi Pengerjaan</th>
                                <th class="py-2 px-1 font-semibold uppercase text-xs text-right w-[10%] align-middle">Vol/Sat</th>
                                <th class="py-2 px-1 font-semibold uppercase text-xs text-right w-[20%] align-middle">Harga Satuan</th>
                                <th class="py-2 px-1 font-semibold uppercase text-xs text-right w-[20%] align-middle">Total</th>
                            </tr>
                        </thead>
                        @endif
                        <tbody>
                            @foreach($offer->jasaItems as $jasa)
                            <tr class="border-b border-gray-200">
                                <td class="py-1 px-1 font-medium text-gray-800 text-xs leading-none align-middle" colspan="3">{{ $jasa->nama_jasa }}</td>
                                <td class="py-1 px-1 text-right text-xs leading-none align-middle whitespace-nowrap">{{ $jasa->volume + 0 }} {{ $jasa->satuan }}</td>
                                <td class="py-1 px-1 text-xs leading-none whitespace-nowrap align-middle">
                                    <div class="flex justify-end gap-1 w-full"><span>Rp</span><span>{{ number_format($jasa->harga_satuan ?? ($jasa->harga_jasa / ($jasa->volume ?: 1)), 0, ',', '.') }}</span></div>
                                </td>
                                <td class="py-1 px-1 text-xs leading-none whitespace-nowrap font-bold text-gray-900 align-middle">
                                    <div class="flex justify-end gap-1 w-full"><span>Rp</span><span>{{ number_format($jasa->harga_jasa, 0, ',', '.') }}</span></div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        @if($showTotal)
                        <tfoot>
                            <tr class="bg-gray-100 font-bold text-gray-800">
                                <td colspan="5" class="py-1 px-1 text-xs text-right uppercase align-middle">Total Pengerjaan Tambahan</td>
                                <td class="py-1 px-1 text-xs text-right whitespace-nowrap align-middle">
                                    <div class="flex justify-end gap-1 w-full"><span>Rp</span><span>{{ number_format($totalJasa, 0, ',', '.') }}</span></div>
                                </td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
                @endif

            </div>
        </section>

        @if($showTotal)
        <section class="mt-4 flex justify-end sans" id="grand-total-block">
            <div class="w-full md:w-6/12">
                <div class="flex justify-between items-center bg-gray-800 text-white p-3 rounded-lg print:bg-white print:text-black print:p-0">
                    <span class="text-lg font-bold uppercase">Grand Total</span>
                    <span class="text-xl font-bold whitespace-nowrap flex gap-2">
                        <span>Rp</span>
                        <span>{{ number_format($offer->total_keseluruhan, 0, ',', '.') }}</span>
                    </span>
                </div>
            </div>
        </section>
        @endif

        <section class="mt-8 text-sm text-gray-700 leading-relaxed sans page-break-inside-avoid">
            <h4 class="font-semibold text-gray-800">Teknis pengerjaan:</h4>
            <ul class="list-disc list-inside ml-4 mt-2">
                <li>Semua peralatan pekerjaan akan disiapkan oleh pihak PT. Tasniem Gerai Inspirasi</li>
                <li>Meliputi : Cat, rol, kuas, dempul, plamir, scaffolding dll.</li>
                <li>Air dan Listrik serta gudang penyimpanan disediakan oleh pemberi kerja yaitu pihak {{ $offer->nama_klien }}</li>
                <li>Pengukuran final luas area akan dihitung bersama dan dijadikan patokan untuk nilai pekerjaan yang disepakati nantinya.</li>
                <li>Aplikasi Sealer ( cat dasar ) dilakukan pada area dinding yang akan di Cat.</li>
                <li>Pengecatan warna dua lapis.</li>
                <li>Finish.</li>
            </ul>
        </section>

        <section class="mt-6 text-sm text-gray-700 sans page-break-inside-avoid">
            <p>Demikianlah surat penawaran ini kami sampaikan, semoga dapat disetujui.</p>
        </section>

        {{-- TANDA TANGAN --}}
        <section class="mt-12 flex justify-end sans">
            <div class="text-center w-56">
                <p>Hormat kami,</p>
                <div class="h-28 flex items-center justify-center">
                    <img src="{{ asset('images/ttd.png') }}" alt="Tanda Tangan" class="h-24 object-contain">
                </div>
                <p class="font-bold text-gray-800 border-b border-black inline-block pb-0.5 uppercase">SAMSU RIZAL</p>
                <p class="text-xs text-gray-600 mt-1">General Manager</p>
            </div>
        </section>
    </div>

    <script>
        function printWithHeader() {
            document.body.classList.remove('hide-header-on-print');
            window.print();
        }

        function printWithoutHeader() {
            document.body.classList.add('hide-header-on-print');
            window.print();
        }
    </script>
</body>

</html>