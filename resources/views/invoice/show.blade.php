@extends('layouts.app')

@section('content')
<div class="container mx-auto my-12 px-4">

    <!-- Tombol Aksi (Hilang saat print) -->
    <div class="max-w-4xl mx-auto mb-4 flex justify-end gap-2 print:hidden">
        <a href="{{ route('invoice.histori') }}" class="bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded hover:bg-gray-300">
            &larr; Kembali ke Histori
        </a>
        <button onclick="window.print()" class="bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">
            🖨️ Print / Simpan PDF
        </button>
    </div>

    <!-- Konten Invoice (Area yang akan di-print) -->
    <div class="max-w-4xl mx-auto bg-white p-8 md:p-12 shadow-lg rounded-lg border" id="invoice-print-area">

        <!-- 1. Kop Surat (Sama seperti Surat Penawaran) -->
        <header class="border-b-2 border-gray-300 pb-4">
            <div class="flex justify-between items-center">
                <div class="w-1/4">
                    <!-- Pastikan logo ada di public/images/logo-tasniem.png -->
                    <img src="{{ asset('images/logo-tasniem.png') }}" alt="Logo Tasniem" class="w-24">
                </div>
                <div class="w-1/2 text-center">
                    <h1 class="text-xl font-bold text-gray-800">PT. TASNIEM GERAI INSPIRASI</h1>
                    <p class="text-sm font-semibold text-gray-600">(The First Inspiration Center of Jotun Indonesia)</p>
                    <p class="text-xs text-gray-500 mt-2">Komp. Ruko KDA Junction Blok C 8 - 9 Batam Centre, Batam, Kepri - Indonesia</p>
                    <p class="text-xs text-gray-500">Telp : +62 778-7485 999, 7080 549 Fax : +62 778-7485 789</p>
                    <p class="text-xs text-gray-500">E-mail : tgi_team040210@yahoo.com Website : www.jotun.com/ap</p>
                </div>
                <div class="w-1/4 flex justify-end">
                    <!-- Pastikan logo ada di public/images/logo-jotun.png -->
                    <img src="{{ asset('images/logo-jotun.png') }}" alt="Logo Jotun" class="w-40">
                </div>
            </div>
        </header>

        <!-- 2. Info Klien & Invoice (Sesuai PDF) -->
        <section class="mt-8 flex justify-between text-sm">
            <div class="w-1/2">
                <p class="font-medium">TO:</p>
                <p class="font-bold text-lg">{{ $invoice->nama_klien }}</p>
                <!-- Menampilkan detail dari Penawaran aslinya -->
                @if($invoice->offer && $invoice->offer->client_details)
                <p>{{ $invoice->offer->client_details }}</p>
                @endif
                <p class="mt-4 font-medium">Attn:</p>
                <p>{{ $invoice->nama_klien }}</p>
            </div>
            <div class="w-1/2 text-right">
                <div class="flex justify-end">
                    <span class="w-28 text-left font-medium">Tanggal</span>
                    <span>: {{ $invoice->created_at->format('d F Y') }}</span>
                </div>
                <div class="flex justify-end mt-1">
                    <span class="w-28 text-left font-medium">Invoice No.</span>
                    <span>: {{ $invoice->no_invoice }}</span>
                </div>
            </div>
        </section>

        <!-- 3. Tabel Rincian (Sesuai PDF) -->
        <section class="mt-8 text-sm">
            <p class="mb-2">Bersama ini kami sampaikan tagihan untuk:</p>
            <p><span class="font-medium w-20 inline-block">Project</span>: Pengecatan dan Supply Cat Jotun Paints</p>
            @if($invoice->offer && $invoice->offer->client_details)
            <p><span class="font-medium w-20 inline-block">Alamat</span>: {{ $invoice->offer->client_details }}</p>
            @endif

            <table class="w-full mt-4 border-collapse border border-black">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border border-black p-2 text-left font-semibold">No.</th>
                        <th class="border border-black p-2 text-left font-semibold">Keterangan</th>
                        <th class="border border-black p-2 text-right font-semibold">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Total Penawaran Asli -->
                    <tr>
                        <td class="border border-black p-2 text-center">1</td>
                        <td class="border border-black p-2">Total Pengecatan (sesuai Penawaran)</td>
                        <td class="border border-black p-2 text-right">Rp {{ number_format($invoice->total_penawaran, 0, ',', '.') }}</td>
                    </tr>

                    <!-- Pekerjaan Tambahan -->
                    @foreach($invoice->additions as $index => $addition)
                    <tr>
                        <td class="border border-black p-2 text-center">{{ $index + 2 }}</td>
                        <td class="border border-black p-2">{{ $addition->nama_pekerjaan }}</td>
                        <td class="border border-black p-2 text-right">Rp {{ number_format($addition->harga, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach

                    <!-- Baris Kosong (jika perlu) -->
                    <tr>
                        <td class="border border-black p-2 h-8"></td>
                        <td class="border border-black p-2"></td>
                        <td class="border border-black p-2"></td>
                    </tr>

                    <!-- Kalkulasi Total -->
                    <tr class="font-medium">
                        <td colspan="2" class="border border-black p-2 text-right">TOTAL</td>
                        <td class="border border-black p-2 text-right">Rp {{ number_format($invoice->total_penawaran + $invoice->total_tambahan, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="font-medium">
                        <td colspan="2" class="border border-black p-2 text-right">Diskon</td>
                        <td class="border border-black p-2 text-right">Rp {{ number_format($invoice->diskon, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="font-bold bg-gray-100">
                        <td colspan="2" class="border border-black p-2 text-right">TOTAL TAGIHAN</td>
                        <td class="border border-black p-2 text-right">Rp {{ number_format($invoice->grand_total, 0, ',', '.') }}</td>
                    </tr>

                    <!-- Pembayaran DP -->
                    @foreach($invoice->payments as $payment)
                    <tr class="font-medium">
                        <td colspan="2" class="border border-black p-2 text-right">{{ $payment->keterangan }}</td>
                        <td class="border border-black p-2 text-right">Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach

                    <!-- Sisa Pembayaran -->
                    <tr class="font-bold text-xl bg-gray-200">
                        <td colspan="2" class="border border-black p-2 text-right">SISA PEMBAYARAN</td>
                        <td class="border border-black p-2 text-right">Rp {{ number_format($invoice->sisa_pembayaran, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <!-- 4. Info TTD & Bank (Sesuai PDF) -->
        <section class="mt-12 flex justify-between text-sm">
            <!-- TTD -->
            <div class="text-center">
                <p>Hormat kami,</p>
                <p>PT. Tasniem Gerai Inspirasi</p>
                <div class="h-28 w-48 relative">
                    <!-- Ganti dengan TTD asli jika ada -->
                   <img src="{{ asset('images/ttd.png') }}" alt="Logo & Tanda Tangan" class="h-28 opacity-100 mx-auto">
                </div>
                <p class="font-bold text-gray-800">SAMSU RIZAL</p>
                <p class="text-gray-600">General Manager</p>
            </div>
            <!-- Info Bank -->
            <div class="text-left">
                <p class="font-medium">Pembayaran melalui Bank:</p>
                <p>a/n PT. Tasniem Gerai Inspirasi</p>
                <p>Bank BRI Cab. Nagoya</p>
                <p>Rek. No. 0331 - 0100 1817 306</p>
            </div>
        </section>

    </div>
</div>

<!-- CSS khusus untuk Print -->
<style>
    @media print {
        .print\:hidden { display: none; }
        body * { visibility: hidden; }
        #invoice-print-area, #invoice-print-area * {
            visibility: visible;
        }
        #invoice-print-area {
            position: absolute; left: 0; top: 0; width: 100%;
            box-shadow: none; border: none; margin: 0; padding: 0.5in;
            font-size: 10pt; /* Ukuran font print */
        }
        /* Hindari page-break di dalam tabel */
        table { page-break-inside: auto; }
        tr    { page-break-inside: avoid; page-break-after: auto; }
        thead { display: table-header-group; }
        tfoot { display: table-footer-group; }
    }
</style>
@endsection