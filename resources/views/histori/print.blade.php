<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Penawaran - {{ $offer->nama_klien }}</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* CSS KHUSUS UNTUK HALAMAN PRINT INI */
        @media print {
            @page {
                size: A4;
                margin: 1.5cm; /* Margin yang cukup */
            }

            body {
                background-color: white;
                margin: 0;
                padding: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .container {
                width: 100% !important;
                max-width: none !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            /* Hapus elemen yang tidak perlu saat diprint */
            .print\:hidden {
                display: none !important;
            }

            /* Pastikan elemen hitam pekat */
            #surat-penawaran, #surat-penawaran * {
                color: #000 !important;
            }

            #grand-total-block div,
            #grand-total-block span {
                color: #000 !important;
                background-color: transparent !important;
            }

            /* Page Break Management */
            .page-break-inside-avoid {
                page-break-inside: avoid;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            thead {
                display: table-header-group;
            }

            tfoot {
                display: table-row-group;
            }

            /* Paksa background header tabel */
            .bg-gray-800 {
                background-color: #1f2937 !important;
                color: white !important;
            }
            .bg-gray-100 {
                background-color: #f3f4f6 !important;
            }
        }

        /* Font styling agar mirip surat resmi */
        body { font-family: 'Times New Roman', Times, serif; }
        .sans { font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; }
    </style>
</head>
<body class="bg-white" onload="window.print()">

    <div class="container mx-auto my-8 px-4">

        <div class="max-w-[21cm] mx-auto bg-white" id="surat-penawaran">

            {{-- HEADER KOP SURAT --}}
            <header class="border-b-2 border-gray-300 pb-4 mb-6 flex justify-between items-center">
                <div class="w-1/4">
                    <img src="{{ asset('images/logo-tasniem.png') }}" alt="Logo Tasniem" class="w-32">
                </div>
                <div class="w-1/2 text-center sans">
                    <h1 class="text-xl font-bold text-gray-800">PT. TASNIEM GERAI INSPIRASI</h1>
                    <p class="text-sm font-semibold text-gray-600">(The First Inspiration Center of Jotun Indonesia)</p>
                    <p class="text-xs text-gray-500 mt-2">Komp. Ruko KDA Junction Blok C 8 - 9 Batam Centre, Batam, Kepri - Indonesia</p>
                    <p class="text-xs text-gray-500">Telp : +62 778-7485 999, 7080 549 Fax : +62 778-7485 789</p>
                    <p class="text-xs text-gray-500">E-mail : tgi_team040210@yahoo.com Website : www.jotun.com/ap</p>
                </div>
                <div class="w-1/4 flex justify-end">
                    <img src="{{ asset('images/logo-jotun.png') }}" alt="Logo Jotun" class="w-40">
                </div>
            </header>

           <section class="mb-6 text-sm sans">
            @php
                $bulanRomawi = [1=>'I', 2=>'II', 3=>'III', 4=>'IV', 5=>'V', 6=>'VI', 7=>'VII', 8=>'VIII', 9=>'IX', 10=>'X', 11=>'XI', 12=>'XII'];
                $romawi = $bulanRomawi[$offer->created_at->format('n')];
                $tahun = $offer->created_at->format('Y');
            @endphp

            <p>Nomor : 00{{ $offer->id }}/SP/TGI-1/{{ $romawi }}/{{ $tahun }}</p>
            <p>Batam, {{ $offer->created_at->format('d F Y') }}</p>
        </section>

            <section class="mt-8 text-sm text-gray-700 sans">
                <p class="text-gray-600">Kepada Yth,</p>
                <h3 class="text-md font-bold text-gray-800">{{ $offer->nama_klien }}</h3>
                @if($offer->client_details)
                <p class="text-sm text-gray-700">{{ $offer->client_details }}</p>
                @endif
                <p class="text-gray-700 mt-2">Dengan Hormat,</p>
            </section>

            <section class="mt-4 space-y-4 text-sm text-gray-700 leading-relaxed text-justify sans">
                <p>Kami PT. TASNIEM GERAI INSPIRASI adalah dealer resmi PT. JOTUN INDONESIA, didirikan pada tanggal 4 Februari 2010, Konsep Inspirasi Centre pertama di kota Batam dan pertama di Indonesia, website <a href="https://tasniemgroup.com" class="text-blue-600 underline">https://tasniemgroup.com</a>.</p>
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

            <section class="mt-10 flex justify-end sans page-break-inside-avoid">
                <div class="text-center w-48">
                    <p>Hormat kami,</p>
                    <div class="h-28 w-48 relative">
                        <img src="{{ asset('images/ttd.png') }}" alt="Logo & Tanda Tangan" class="h-28 opacity-100 mx-auto">
                    </div>
                    <p class="font-bold text-gray-800">SAMSU RIZAL</p>
                    <p class="text-gray-600">General Manager</p>
                </div>
            </section>

        </div>
    </div>

</body>
</html>