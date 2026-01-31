<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Invoice - {{ $invoice->no_invoice }}</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
    /* 1. RESET STANDAR UNTUK PRINT */
    @media print {
        @page {
            size: A4;
            margin: 0; /* Margin nol agar kita kontrol penuh lewat CSS */
        }

        body {
            margin: 0;
            padding: 0;
            background-color: white !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .no-print { display: none !important; }

        /* KONTROL PRESISI A4 */
        #main-container {
            width: 210mm;
            /* Jangan gunakan min-height: 297mm agar jika konten sedikit tidak memaksa 2 halaman */
            margin: 0 auto !important;
            padding: 15mm 20mm !important; /* Margin konten standar surat */
            box-shadow: none !important;
            border: none !important;
            float: none !important;
        }

        /* LOGIKA PRINT TANPA KOP */
        .hide-header-on-print .invoice-header {
            display: none !important;
        }

        .hide-header-on-print #main-container {
            padding-top: 60mm !important; /* Sesuaikan dengan tinggi Kop fisik Anda */
        }

        /* ANTI TERPOTONG */
        table { page-break-inside: auto; width: 100%; }
        tr { page-break-inside: avoid; page-break-after: auto; }
        thead { display: table-header-group; }

        /* Mencegah bagian tanda tangan terpisah sendirian di halaman baru */
        .signature-section {
            page-break-inside: avoid;
        }
    }

    /* 2. TAMPILAN LAYAR (PREVIEW) */
    body {
        font-family: 'Times New Roman', Times, serif;
        background-color: #f3f4f6; /* Abu-abu netral */
    }

    #main-container {
        background-color: white;
        width: 210mm;
        min-height: 297mm; /* Simulasi kertas A4 di layar */
        margin: 20px auto;
        padding: 20mm;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .sans { font-family: Arial, Helvetica, sans-serif; }
    .nav-floating {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 100;
        display: flex;
        gap: 10px;
    }
</style>
</head>
<body class="bg-gray-100 text-black">

    {{-- Tombol Navigasi Terapung (Hanya Muncul di Layar) --}}
    <div class="nav-floating no-print">
        <button onclick="printWithHeader()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-lg flex items-center gap-2 transition">
            <span>🖨️</span> Cetak Normal
        </button>
        <button onclick="printWithoutHeader()" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded shadow-lg flex items-center gap-2 transition">
            <span>📄</span> Tanpa Kop Surat
        </button>
        <button onclick="window.close()" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded shadow-lg transition">
            Tutup
        </button>
    </div>

    <div id="main-container" class="max-w-[21cm] mx-auto bg-white shadow-xl my-10 p-10 print:shadow-none print:my-0">

        {{-- 1. HEADER KOP SURAT --}}
        <header class="w-full mb-6 invoice-header">
            <div class="flex justify-between items-center w-full px-0">
                <div class="w-[22%] flex justify-start">
                    <img src="{{ asset('images/logo-tasniem.png') }}" alt="Logo Tasniem" class="h-20 w-auto object-contain">
                </div>

                <div class="w-[61%] text-center">
                    <h1 class="text-2xl font-extrabold text-[#1a237e] uppercase tracking-wide whitespace-nowrap leading-none mb-1"
                        style="font-family: 'Times New Roman', Times, serif; transform: scaleY(1.1);">
                        PT. TASNIEM GERAI INSPIRASI
                    </h1>
                    <p class="text-xs font-bold text-[#d32f2f] mb-1">
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

        {{-- 2. INFO KLIEN & INVOICE --}}
        <section class="mt-8 flex justify-between text-sm sans">
            <div class="w-1/2 text-black">
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
            <p class="mb-2 italic">Bersama ini kami sampaikan tagihan untuk:</p>
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

                    <tr class="font-bold bg-gray-50">
                        <td colspan="2" class="border border-black border-print p-2 text-right uppercase">Total Pekerjaan</td>
                        <td class="border border-black border-print p-2 text-right">
                            Rp {{ number_format($invoice->total_penawaran + ($invoice->total_tambahan ?? 0), 0, ',', '.') }}
                        </td>
                    </tr>

                    @if($invoice->diskon > 0)
                    <tr class="font-medium text-red-600">
                        <td colspan="2" class="border border-black border-print p-2 text-right italic">Potongan Harga (Diskon)</td>
                        <td class="border border-black border-print p-2 text-right">
                            - Rp {{ number_format($invoice->diskon, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endif

                    <tr class="font-bold bg-gray-100">
                        <td colspan="2" class="border border-black border-print p-2 text-right uppercase">Grand Total Tagihan</td>
                        <td class="border border-black border-print p-2 text-right text-lg">Rp {{ number_format($invoice->grand_total, 0, ',', '.') }}</td>
                    </tr>

                    @if(isset($invoice->payments) && count($invoice->payments) > 0)
                        @foreach($invoice->payments as $payment)
                        <tr class="font-medium text-gray-700 italic">
                            <td colspan="2" class="border border-black border-print p-2 text-right">
                                Pembayaran: {{ $payment->keterangan }} ({{ \Carbon\Carbon::parse($payment->created_at)->format('d/m/Y') }})
                            </td>
                            <td class="border border-black border-print p-2 text-right text-green-700">
                                - Rp {{ number_format($payment->jumlah, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    @endif

                    <tr class="font-bold text-lg bg-gray-200">
                        <td colspan="2" class="border border-black border-print p-2 text-right uppercase tracking-wider">Sisa Pembayaran</td>
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
                    <img src="{{ asset('images/ttd.png') }}" alt="Tanda Tangan" class="h-24 object-contain">
                </div>
                <p class="font-bold border-b border-black inline-block pb-1">SAMSU RIZAL</p>
                <p class="text-xs mt-1">General Manager</p>
            </div>

            <div class="text-left border border-gray-400 p-4 rounded-lg bg-gray-50 h-fit">
                <p class="font-bold border-b border-gray-300 pb-2 mb-2">Informasi Pembayaran:</p>
                <div class="space-y-1">
                    <p>Nama Akun : <strong>PT. Tasniem Gerai Inspirasi</strong></p>
                    <p>Bank : <strong>Bank BRI</strong> Cab. Nagoya</p>
                    <p>No. Rekening : <strong>0331 - 0100 1817 306</strong></p>
                </div>
            </div>
        </section>
    </div>

    <script>
        // Fungsi Cetak Normal
        function printWithHeader() {
            document.body.classList.remove('hide-header-on-print');
            window.print();
        }

        // Fungsi Cetak Tanpa Kop
        function printWithoutHeader() {
            document.body.classList.add('hide-header-on-print');
            window.print();
        }
    </script>
</body>
</html>