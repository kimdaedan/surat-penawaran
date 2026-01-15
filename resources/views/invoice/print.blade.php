<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Invoice - {{ $invoice->no_invoice }}</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* CSS Khusus Print */
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
                font-size: 10pt; /* Ukuran font standar surat */
            }

            /* Hapus elemen yang tidak perlu */
            .no-print { display: none !important; }

            /* Pastikan elemen hitam pekat untuk teks */
            .text-black-print { color: #000 !important; }

            /* Page Break Management */
            table { page-break-inside: auto; }
            tr    { page-break-inside: avoid; page-break-after: auto; }
            thead { display: table-header-group; }
            tfoot { display: table-row-group; }

            /* Border Tabel Tegas */
            .border-print { border: 1px solid #000 !important; }
        }

        /* Font styling */
        body { font-family: 'Times New Roman', Times, serif; }
        .sans { font-family: Arial, Helvetica, sans-serif; }
    </style>
</head>
<body class="bg-white text-black" onload="window.print()">

    <div class="max-w-[21cm] mx-auto bg-white p-4">

        {{-- 1. HEADER KOP SURAT --}}
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

        {{-- 2. INFO KLIEN & INVOICE --}}
        <section class="mt-8 flex justify-between text-sm sans">
            <div class="w-1/2">
                <p class="font-bold mb-1">TO:</p>
                <p class="font-bold text-lg uppercase">{{ $invoice->nama_klien }}</p>

                @if($invoice->offer && $invoice->offer->client_details)
                <p class="text-gray-700">{{ $invoice->offer->client_details }}</p>
                @endif

                <p class="mt-4 font-bold">Attn:</p>
                <p>{{ $invoice->nama_klien }}</p>
            </div>
            <div class="w-1/2 text-right">
                <div class="flex justify-end mb-1">
                    <span class="w-24 text-left font-bold">Tanggal</span>
                    <span class="text-left">: {{ \Carbon\Carbon::parse($invoice->created_at)->format('d F Y') }}</span>
                </div>
                <div class="flex justify-end">
                    <span class="w-24 text-left font-bold">Invoice No.</span>
                    <span class="text-left">: {{ $invoice->no_invoice }}</span>
                </div>
            </div>
        </section>

        {{-- 3. TABEL RINCIAN --}}
        <section class="mt-8 text-sm sans">
            <p class="mb-2">Bersama ini kami sampaikan tagihan untuk:</p>
            <div class="mb-4">
                <p><span class="font-bold w-20 inline-block">Project</span>: Pengecatan dan Supply Cat Jotun Paints</p>
                @if($invoice->offer && $invoice->offer->client_details)
                <p><span class="font-bold w-20 inline-block">Alamat</span>: {{ $invoice->offer->client_details }}</p>
                @endif
            </div>

            <table class="w-full mt-4 border-collapse border border-black border-print">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border border-black border-print p-2 text-center w-12 font-bold">No.</th>
                        <th class="border border-black border-print p-2 text-left font-bold">Keterangan</th>
                        <th class="border border-black border-print p-2 text-right font-bold w-48">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-black border-print p-2 text-center align-top">1</td>
                        <td class="border border-black border-print p-2 align-top">Total Pengecatan (sesuai Penawaran)</td>
                        <td class="border border-black border-print p-2 text-right align-top">Rp {{ number_format($invoice->total_penawaran, 0, ',', '.') }}</td>
                    </tr>

                    @if(isset($invoice->additions) && count($invoice->additions) > 0)
                        @foreach($invoice->additions as $index => $addition)
                        <tr>
                            <td class="border border-black border-print p-2 text-center align-top">{{ $index + 2 }}</td>
                            <td class="border border-black border-print p-2 align-top">{{ $addition->nama_pekerjaan }}</td>
                            <td class="border border-black border-print p-2 text-right align-top">Rp {{ number_format($addition->harga, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    @endif

                    <tr>
                        <td class="border border-black border-print p-2 h-8"></td>
                        <td class="border border-black border-print p-2"></td>
                        <td class="border border-black border-print p-2"></td>
                    </tr>

                    <tr class="font-medium">
                        <td colspan="2" class="border border-black border-print p-2 text-right font-bold">TOTAL</td>
                        <td class="border border-black border-print p-2 text-right font-bold">
                            Rp {{ number_format($invoice->total_penawaran + ($invoice->total_tambahan ?? 0), 0, ',', '.') }}
                        </td>
                    </tr>

                    @if($invoice->diskon > 0)
                    <tr class="font-medium">
                        <td colspan="2" class="border border-black border-print p-2 text-right">Diskon</td>
                        <td class="border border-black border-print p-2 text-right text-red-600">
                            - Rp {{ number_format($invoice->diskon, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endif

                    <tr class="font-bold bg-gray-100">
                        <td colspan="2" class="border border-black border-print p-2 text-right">TOTAL TAGIHAN</td>
                        <td class="border border-black border-print p-2 text-right">Rp {{ number_format($invoice->grand_total, 0, ',', '.') }}</td>
                    </tr>

                    @if(isset($invoice->payments) && count($invoice->payments) > 0)
                        @foreach($invoice->payments as $payment)
                        <tr class="font-medium text-gray-700">
                            <td colspan="2" class="border border-black border-print p-2 text-right italic">
                                {{ $payment->keterangan }} ({{ \Carbon\Carbon::parse($payment->created_at)->format('d/m/Y') }})
                            </td>
                            <td class="border border-black border-print p-2 text-right text-green-700">
                                - Rp {{ number_format($payment->jumlah, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    @endif

                    <tr class="font-bold text-lg bg-gray-200">
                        <td colspan="2" class="border border-black border-print p-2 text-right uppercase">SISA PEMBAYARAN</td>
                        <td class="border border-black border-print p-2 text-right">Rp {{ number_format($invoice->sisa_pembayaran, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </section>

        {{-- 4. INFO TTD & BANK --}}
        <section class="mt-12 flex justify-between text-sm sans page-break-inside-avoid">

            <div class="text-center w-64">
                <p class="mb-4">Hormat kami,</p>
                <p class="font-bold">PT. Tasniem Gerai Inspirasi</p>
                <div class="h-24 w-full flex justify-center items-center my-2">
                    <img src="{{ asset('images/ttd.png') }}" alt="Logo & Tanda Tangan" class="h-24 opacity-100 object-contain">
                </div>
                <p class="font-bold border-b border-black inline-block pb-1">SAMSU RIZAL</p>
                <p class="text-xs mt-1">General Manager</p>
            </div>

            <div class="text-left border border-gray-400 p-4 rounded-lg bg-gray-50 h-fit">
                <p class="font-bold border-b border-gray-300 pb-2 mb-2">Pembayaran melalui Bank:</p>
                <p>Nama Akun : <strong>PT. Tasniem Gerai Inspirasi</strong></p>
                <p>Bank : <strong>Bank BRI</strong> Cab. Nagoya</p>
                <p>No. Rekening : <strong>0331 - 0100 1817 306</strong></p>
            </div>
        </section>

    </div>

</body>
</html>