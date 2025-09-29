@extends('layouts.app')

@section('content')
<div class="container mx-auto my-12 px-4">
    <div class="max-w-4xl mx-auto">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                Daftar Harga Produk & Jasa
            </h1>
            <a href="{{ url('/daftar-harga/tambah') }}" class="bg-gray-800 text-white font-bold py-2 px-4 rounded hover:bg-gray-700 transition ease-in-out duration-150">
                + Tambah Baru
            </a>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="text-xs text-white uppercase bg-gray-800">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Nama Produk / Jasa
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Hasil Akhir
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Deskripsi / Performa
                        </th>
                        <th scope="col" class="px-6 py-3 text-right">
                            Harga
                        </th>
                        <th scope="col" class="px-6 py-3 text-center">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            {{ $product->nama_produk }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $product->hasil_akhir }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $product->performa }}
                        </td>
                        <td class="px-6 py-4 font-semibold text-right">
                            Rp {{ number_format($product->harga, 0, ',', '.') }} /m²
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center items-center gap-2">
                               <a href="{{ route('harga.edit', $product->id) }}" class="font-medium text-blue-600 hover:underline">Edit</a>
                                <form action="{{ route('harga.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-medium text-red-600 hover:underline">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Belum ada data.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection