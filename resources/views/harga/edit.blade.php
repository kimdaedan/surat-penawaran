@extends('layouts.app')

@section('content')
<div class="container mx-auto my-12 px-4">
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6">Edit Data Harga</h1>

        <form action="{{ route('harga.update', $product->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="nama_produk" class="block text-sm font-medium text-gray-700">Nama Produk / Jasa</label>
                    <input type="text" name="nama_produk" id="nama_produk" value="{{ $product->nama_produk }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-800 focus:ring-gray-800" required>
                </div>
                <div>
                    <label for="hasil_akhir" class="block text-sm font-medium text-gray-700">Hasil Akhir</label>
                    <input type="text" name="hasil_akhir" id="hasil_akhir" value="{{ $product->hasil_akhir }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-800 focus:ring-gray-800" required>
                </div>
                <div>
                    <label for="performa" class="block text-sm font-medium text-gray-700">Performa / Fitur</label>
                    <input type="text" name="performa" id="performa" value="{{ $product->performa }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-800 focus:ring-gray-800" required>
                </div>
                <div>
                    <label for="harga" class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
                    <input type="number" name="harga" id="harga" value="{{ $product->harga }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-800 focus:ring-gray-800" required>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200">
                <button type="submit" class="w-full bg-gray-800 text-white font-bold py-3 px-6 rounded hover:bg-gray-700 transition ease-in-out duration-150">
                    Update Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection