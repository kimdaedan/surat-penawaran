@extends('layouts.app')

@section('content')
<div class="container mx-auto my-12 px-4">

    <div class="max-w-4xl mx-auto mb-4 flex justify-end gap-2">
        <a href="{{ route('histori.index') }}" class="bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded hover:bg-gray-300">
            &larr; Kembali
        </a>
        <button onclick="window.print()" class="bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">
            🖨️ Print / Simpan PDF
        </button>
    </div>

    <div class="max-w-4xl mx-auto bg-white p-8 md:p-12 shadow-lg rounded-lg" id="surat-penawaran">

        <header class="flex justify-between items-start border-b-4 border-gray-800 pb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">PENAWARAN.APP</h1>
                <p class="text-gray-500">Jl. Pembangunan No. 123, Batam</p>
                <p class="text-gray-500">admin@penawaran.app | 0812-3456-7890</p>
            </div>
            <div class="text-right">
                <h2 class="text-2xl font-semibold text-gray-700">Surat Penawaran</h2>
                <p class="text-gray-500 mt-1">Tanggal: {{ $offer->created_at->format('d F Y') }}</p>
                <p class="text-gray-500">Nomor: 00{{ $offer->id }}/PNW/IX/2025</p>
            </div>
        </header>

        <section class="mt-8">
            <p class="text-gray-600">Kepada Yth.</p>
            <h3 class="text-xl font-bold text-gray-800">{{ $offer->nama_klien }}</h3>
        </section>

        <section class="mt-8">
            <p class="text-gray-700 leading-relaxed">Dengan hormat,<br>
            Bersama surat ini, kami mengajukan penawaran harga untuk pekerjaan sesuai dengan rincian sebagai berikut:</p>
        </section>

        <section class="mt-8">
            <div class="w-full overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="p-3 font-semibold uppercase">Deskripsi Pekerjaan</th>
                            <th class="p-3 font-semibold uppercase text-center">Volume</th>
                            <th class="p-3 font-semibold uppercase text-right">Harga Satuan</th>
                            <th class="p-3 font-semibold uppercase text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($offer->items as $item)
                        <tr class="border-b border-gray-200">
                            <td class="p-3">{{ $item->nama_produk_area }}</td>
                            <td class="p-3 text-center">{{ $item->volume }} M²</td>
                            <td class="p-3 text-right">Rp {{ number_format($item->harga_per_m2, 0, ',', '.') }}</td>
                            <td class="p-3 text-right">Rp {{ number_format($item->volume * $item->harga_per_m2, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach

                        @if($offer->jasa_harga > 0)
                        <tr class="border-b border-gray-200">
                            <td class="p-3">{{ $offer->jasa_nama }}</td>
                            <td class="p-3 text-center">1 Lot</td>
                            <td class="p-3 text-right">Rp {{ number_format($offer->jasa_harga, 0, ',', '.') }}</td>
                            <td class="p-3 text-right">Rp {{ number_format($offer->jasa_harga, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        <tr class="font-bold text-gray-800">
                            <td colspan="3" class="p-3 text-right text-xl uppercase">Grand Total</td>
                            <td class="p-3 text-right text-xl bg-gray-100">Rp {{ number_format($offer->total_keseluruhan, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </section>

        <section class="mt-12">
            <p class="text-gray-700 leading-relaxed">Demikian surat penawaran ini kami sampaikan. Atas perhatian dan kerjasamanya kami ucapkan terima kasih.</p>
            <div class="mt-16 text-right">
                <p>Hormat kami,</p>
                <div class="h-20"></div> <p class="font-bold">(Nama Anda)</p>
            </div>
        </section>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #surat-penawaran, #surat-penawaran * {
            visibility: visible;
        }
        #surat-penawaran {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            box-shadow: none;
            border: none;
        }
    }
</style>
@endsection