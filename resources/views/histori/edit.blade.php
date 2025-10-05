@extends('layouts.app')

@section('content')
<div class="container mx-auto my-12 px-4">
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6">Edit Surat Penawaran</h1>

        <form action="{{ route('histori.update', $offer->id) }}" method="POST">
            @csrf
            @method('PUT')

            <fieldset class="border-t pt-6">
                <legend class="text-lg font-semibold text-gray-700 px-2">1. Informasi Klien</legend>
                <div class="mt-4">
                    <label for="nama_klien" class="block text-sm font-medium text-gray-600">Nama Klien/Perusahaan</label>
                    <input type="text" name="nama_klien" id="nama_klien" value="{{ $offer->nama_klien }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                </div>
            </fieldset>

            <fieldset class="border-t pt-6 mt-8">
                <legend class="text-lg font-semibold text-gray-700 px-2">2. Detail Produk</legend>
                <div id="product-rows-container" class="space-y-4 mt-4">
                    @forelse($offer->items as $index => $item)
                    <div class="product-row grid grid-cols-1 md:grid-cols-12 gap-4 items-end p-3 border rounded-md">
                        <div class="md:col-span-3"><label class="block text-sm font-medium text-gray-600">Nama Produk</label><select name="produk[{{$index}}][nama]" class="product-select mt-1 block w-full rounded-md"><option value="">-- Pilih --</option>@foreach ($all_products as $product)<option value="{{ $product->nama_produk }}" data-harga="{{ $product->harga }}" @if($product->nama_produk == $item->nama_produk) selected @endif>{{ $product->nama_produk }}</option>@endforeach</select></div>
                        <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-600">Area Dinding</label><input type="text" name="produk[{{$index}}][area]" value="{{ $item->area_dinding }}" class="mt-1 block w-full rounded-md"></div>
                        <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-600">Volume M²</label><input type="number" step="0.01" name="produk[{{$index}}][volume]" value="{{ $item->volume }}" class="volume-input mt-1 block w-full rounded-md"></div>
                        <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-600">Harga/M²</label><input type="number" name="produk[{{$index}}][harga]" value="{{ $item->harga_per_m2 }}" class="harga-input mt-1 block w-full bg-gray-100" readonly></div>
                        <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-600">Total</label><input type="text" class="total-output mt-1 block w-full bg-gray-100" readonly></div>
                        <div class="md:col-span-1"><label class="block text-sm font-medium text-transparent">Hapus</label><button type="button" class="remove-row-btn bg-red-500 text-white p-2 rounded w-full">-</button></div>
                    </div>
                    @empty
                    {{-- Sediakan satu baris kosong jika tidak ada item --}}
                    @endforelse
                </div>
                <div class="mt-4"><button type="button" id="add-product-row-btn" class="bg-blue-500 text-white font-bold py-2 px-4 rounded"> + Tambah Produk </button></div>
            </fieldset>

            <fieldset class="border-t pt-6 mt-8">
                <legend class="text-lg font-semibold text-gray-700 px-2">3. Tambahan Pengerjaan (optional)</legend>
                <div id="jasa-rows-container" class="space-y-4 mt-4">
                     @forelse($offer->jasaItems as $index => $jasa)
                    <div class="jasa-row grid grid-cols-1 md:grid-cols-12 gap-4 items-end p-3 border rounded-md">
                        <div class="md:col-span-8"><label class="block text-sm font-medium text-gray-600">Nama Pengerjaan</label><input type="text" name="jasa[{{$index}}][nama]" value="{{ $jasa->nama_jasa }}" class="mt-1 block w-full rounded-md"></div>
                        <div class="md:col-span-3"><label class="block text-sm font-medium text-gray-600">Harga (Rp)</label><input type="number" name="jasa[{{$index}}][harga]" value="{{ $jasa->harga_jasa }}" class="jasa-harga-input mt-1 block w-full rounded-md"></div>
                        <div class="md:col-span-1"><label class="block text-sm font-medium text-transparent">Hapus</label><button type="button" class="remove-jasa-row-btn bg-red-500 text-white p-2 rounded w-full">-</button></div>
                    </div>
                    @empty
                    {{-- Sediakan satu baris kosong jika tidak ada item --}}
                    @endforelse
                </div>
                 <div class="mt-4"><button type="button" id="add-jasa-row-btn" class="bg-green-500 text-white font-bold py-2 px-4 rounded"> + Tambah Pengerjaan </button></div>
            </fieldset>

            <div class="mt-8 pt-6 border-t"><div class="flex justify-end items-center"><span class="text-lg mr-4">Total Estimasi Harga:</span><span id="total_keseluruhan" class="text-2xl font-bold"></span></div></div>
            <div class="mt-8"><button type="submit" class="w-full bg-gray-800 text-white font-bold py-3 px-6 rounded"> Update Surat Penawaran </button></div>
        </form>

        <template id="product-row-template">
            <div class="product-row grid grid-cols-1 md:grid-cols-12 gap-4 items-end p-3 border rounded-md">
                <div class="md:col-span-3"><label class="block text-sm font-medium text-gray-600">Nama Produk</label><select class="product-select mt-1 block w-full rounded-md"><option value="">-- Pilih --</option>@foreach ($all_products as $product)<option value="{{ $product->nama_produk }}" data-harga="{{ $product->harga }}">{{ $product->nama_produk }}</option>@endforeach</select></div>
                <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-600">Area Dinding</label><input type="text" placeholder="Dinding Luar" class="mt-1 block w-full rounded-md"></div>
                <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-600">Volume M²</label><input type="number" step="0.01" value="1" class="volume-input mt-1 block w-full rounded-md"></div>
                <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-600">Harga/M²</label><input type="number" class="harga-input mt-1 block w-full bg-gray-100" readonly></div>
                <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-600">Total</label><input type="text" class="total-output mt-1 block w-full bg-gray-100" readonly></div>
                <div class="md:col-span-1"><label class="block text-sm font-medium text-transparent">Hapus</label><button type="button" class="remove-row-btn bg-red-500 text-white p-2 rounded w-full">-</button></div>
            </div>
        </template>
        <template id="jasa-row-template">
             <div class="jasa-row grid grid-cols-1 md:grid-cols-12 gap-4 items-end p-3 border rounded-md">
                <div class="md:col-span-8"><label class="block text-sm font-medium text-gray-600">Nama Pengerjaan</label><input type="text" class="mt-1 block w-full rounded-md"></div>
                <div class="md:col-span-3"><label class="block text-sm font-medium text-gray-600">Harga (Rp)</label><input type="number" class="jasa-harga-input mt-1 block w-full rounded-md"></div>
                <div class="md:col-span-1"><label class="block text-sm font-medium text-transparent">Hapus</label><button type="button" class="remove-jasa-row-btn bg-red-500 text-white p-2 rounded w-full">-</button></div>
            </div>
        </template>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const totalKeseluruhanDisplay = document.getElementById('total_keseluruhan');
    let productRowIndex = {{ $offer->items->count() }};
    let jasaRowIndex = {{ $offer->jasaItems->count() }};

    function formatRupiah(angka) { return 'Rp ' + (angka || 0).toLocaleString('id-ID'); }

    // --- Tom Select Logic ---
    const tomSelectSettings = { create: false, sortField: { field: "text", direction: "asc" } };

    // --- Product Logic ---
    const productContainer = document.getElementById('product-rows-container');
    const addProductRowBtn = document.getElementById('add-product-row-btn');
    const productTemplate = document.getElementById('product-row-template').content.firstElementChild;

    function addProductEventListeners(row) { /* ... isi fungsi sama seperti sebelumnya ... */ }

    addProductRowBtn.addEventListener('click', () => {
        const newRow = productTemplate.cloneNode(true);
        // Set name attributes
        newRow.querySelector('.product-select').name = `produk[${productRowIndex}][nama]`;
        newRow.querySelector('input[placeholder="Dinding Luar"]').name = `produk[${productRowIndex}][area]`;
        newRow.querySelector('.volume-input').name = `produk[${productRowIndex}][volume]`;
        newRow.querySelector('.harga-input').name = `produk[${productRowIndex}][harga]`;
        productContainer.appendChild(newRow);
        addProductEventListeners(newRow);
        productRowIndex++;
    });

    // --- Jasa Logic ---
    const jasaContainer = document.getElementById('jasa-rows-container');
    const addJasaRowBtn = document.getElementById('add-jasa-row-btn');
    const jasaTemplate = document.getElementById('jasa-row-template').content.firstElementChild;

    function addJasaEventListeners(row) { /* ... isi fungsi sama seperti sebelumnya ... */ }

    addJasaRowBtn.addEventListener('click', () => {
        const newRow = jasaTemplate.cloneNode(true);
        // Set name attributes
        newRow.querySelector('input[type="text"]').name = `jasa[${jasaRowIndex}][nama]`;
        newRow.querySelector('.jasa-harga-input').name = `jasa[${jasaRowIndex}][harga]`;
        jasaContainer.appendChild(newRow);
        addJasaEventListeners(newRow);
        jasaRowIndex++;
    });

    // --- Calculation Logic ---
    function calculateAllTotals() { /* ... isi fungsi sama seperti sebelumnya ... */ }

    // --- Initial Setup ---
    document.querySelectorAll('.product-row').forEach(row => addProductEventListeners(row));
    document.querySelectorAll('.jasa-row').forEach(row => addJasaEventListeners(row));
    calculateAllTotals();
});
</script>
@endsection