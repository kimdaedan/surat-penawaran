@extends('layouts.app')

@section('content')
<div class="container mx-auto my-12 px-4">
    <div class="max-w-7xl mx-auto">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                Histori Penawaran
            </h1>
            <a href="{{ route('penawaran.create_combined') }}" class="bg-gray-800 text-white font-bold py-2 px-4 rounded hover:bg-gray-700 transition ease-in-out duration-150">
                + Buat Penawaran Baru
            </a>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="text-xs text-white uppercase bg-gray-800">
                    <tr>
                        <th scope="col" class="px-6 py-3">Tanggal</th>
                        <th scope="col" class="px-6 py-3">Nama Klien</th>
                        <th scope="col" class="px-6 py-3">Detail Penawaran</th>
                        <th scope="col" class="px-6 py-3 text-right">Total Harga</th>
                        <th scope="col" class="px-6 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($offers as $offer)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4">
                            {{ $offer->created_at->format('d M Y') }}
                        </td>
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            {{ $offer->nama_klien }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $offer->produk_nama }} {{ $offer->jasa_nama ? '+ '.$offer->jasa_nama : '' }}
                        </td>
                        <td class="px-6 py-4 font-semibold text-right">
                            Rp {{ number_format($offer->total_keseluruhan, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center items-center gap-4">
                                <a href="{{ route('histori.show', $offer->id) }}" class="font-medium text-gray-600 hover:underline">Lihat</a>
                                <a href="#" class="font-medium text-blue-600 hover:underline">Edit</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Belum ada histori penawaran.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection