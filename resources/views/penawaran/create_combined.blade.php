@extends('layouts.app')

@section('content')
<div class="container mx-auto my-12 px-4">
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-2">Surat Penawaran: Produk + Jasa</h1>
        <p class="text-gray-600 mb-6">Isi semua detail yang diperlukan di bawah ini.</p>

        <form action="{{ route('penawaran.store_combined') }}" method="POST">
            @csrf

            <fieldset class="border-t pt-6">
                <legend class="text-lg font-semibold text-gray-700 px-2">1. Informasi Klien</legend>
                <div class="grid grid-cols-1 gap-6 mt-4">
                    <div>
                        <label for="nama_klien" class="block text-sm font-medium text-gray-600">Nama Klien/Perusahaan</label>
                        <input type="text" name="nama_klien" id="nama_klien" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-800 focus:ring-gray-800" required>
                    </div>
                </div>
            </fieldset>

            <fieldset class="border-t pt-6 mt-8">
                <legend class="text-lg font-semibold text-gray-700 px-2">2. Detail Produk</legend>
                <div id="product-rows-container" class="space-y-4 mt-4">
                    <div class="product-row grid grid-cols-1 md:grid-cols-12 gap-4 items-end p-3 border rounded-md">
                        <div class="md:col-span-3"><label class="block text-sm font-medium text-gray-600">Nama Produk</label><select name="produk[0][nama]" class="product-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-800 focus:ring-gray-800">
                                <option value="">-- Pilih Produk --</option>@foreach ($products as $product)<option value="{{ $product->nama_produk }}" data-harga="{{ $product->harga }}">{{ $product->nama_produk }}</option>@endforeach
                            </select></div>
                        <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-600">Area Dinding</label><input type="text" name="produk[0][area]" placeholder="Dinding Luar" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-800 focus:ring-gray-800"></div>
                        <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-600">Volume M²</label><input type="number" name="produk[0][volume]" value="1" class="volume-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-800 focus:ring-gray-800"></div>
                        <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-600">Harga/M²</label><input type="number" name="produk[0][harga]" class="harga-input mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm" readonly></div>
                        <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-600">Total</label><input type="text" class="total-output mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm" readonly></div>
                        <div class="md:col-span-1"><button type="button" class="remove-row-btn bg-red-500 text-white p-2 rounded hover:bg-red-600 w-full">-</button></div>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="button" id="add-product-row-btn" class="bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-600"> + Tambah Produk </button>
                </div>
            </fieldset>

            <fieldset class="border-t pt-6 mt-8">
                <legend class="text-lg font-semibold text-gray-700 px-2">3. Tambahan Pengerjaan (optional)</legend>
                <div id="jasa-rows-container" class="space-y-4 mt-4">
                    <div class="jasa-row grid grid-cols-1 md:grid-cols-12 gap-4 items-end p-3 border rounded-md">
                        <div class="md:col-span-8">
                            <label class="block text-sm font-medium text-gray-600">Nama Pengerjaan</label>
                            <input type="text" name="jasa[0][nama]" placeholder="Contoh: Bongkar Pasang AC" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-800 focus:ring-gray-800">
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-sm font-medium text-gray-600">Harga (Rp)</label>
                            <input type="number" name="jasa[0][harga]" placeholder="500000" class="jasa-harga-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-800 focus:ring-gray-800">
                        </div>
                        <div class="md:col-span-1">
                            <button type="button" class="remove-jasa-row-btn bg-red-500 text-white p-2 rounded hover:bg-red-600 w-full">-</button>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="button" id="add-jasa-row-btn" class="bg-green-500 text-white font-bold py-2 px-4 rounded hover:bg-green-600"> + Tambah Pengerjaan </button>
                </div>
            </fieldset>

            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="flex justify-end items-center">
                    <span class="text-lg font-medium text-gray-600 mr-4">Total Estimasi Harga:</span>
                    <span id="total_keseluruhan" class="text-2xl font-bold text-gray-800">Rp 0</span>
                </div>
            </div>

            <div class="mt-8">
                <button type="submit" class="w-full bg-gray-800 text-white font-bold py-3 px-6 rounded hover:bg-gray-700 transition"> Buat Surat Penawaran </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Helper & Global Variables ---
        const totalKeseluruhanDisplay = document.getElementById('total_keseluruhan');
        let productRowIndex = 1;
        let jasaRowIndex = 1;

        function formatRupiah(angka) {
            return 'Rp ' + (angka || 0).toLocaleString('id-ID');
        }

        // --- Tom Select Initialization ---
        const tomSelectSettings = {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            }
        };

        // --- Product Logic ---
        const productContainer = document.getElementById('product-rows-container');
        const addProductRowBtn = document.getElementById('add-product-row-btn');

        function addProductEventListeners(row) {
            const productSelect = row.querySelector('.product-select');
            const hargaInput = row.querySelector('.harga-input');
            const volumeInput = row.querySelector('.volume-input');
            const removeBtn = row.querySelector('.remove-row-btn');

            // Inisialisasi Tom Select pada dropdown
            new TomSelect(productSelect, tomSelectSettings);

            productSelect.addEventListener('change', function() {
                const selectedValue = this.value;
                const selectedOption = Array.from(this.options).find(opt => opt.value === selectedValue);
                const harga = selectedOption ? selectedOption.getAttribute('data-harga') : 0;
                hargaInput.value = harga || '';
                calculateAllTotals();
            });

            volumeInput.addEventListener('input', calculateAllTotals);

            removeBtn.addEventListener('click', () => {
                if (productSelect.tomselect) {
                    productSelect.tomselect.destroy();
                }
                row.remove();
                calculateAllTotals();
            });
        }

        addProductRowBtn.addEventListener('click', () => {
            const newRow = productContainer.querySelector('.product-row').cloneNode(true);
            const newSelect = newRow.querySelector('.product-select');

            // Hancurkan instance TomSelect lama dari hasil kloning
            if (newSelect.tomselect) {
                newSelect.tomselect.destroy();
            }

            newSelect.selectedIndex = 0;
            newRow.querySelectorAll('input').forEach(input => {
                input.value = input.classList.contains('volume-input') ? '1' : '';
            });

            // Update name attributes
            newSelect.name = `produk[${productRowIndex}][nama]`;
            newRow.querySelector('[name$="[area]"]').name = `produk[${productRowIndex}][area]`;
            newRow.querySelector('.volume-input').name = `produk[${productRowIndex}][volume]`;
            newRow.querySelector('.harga-input').name = `produk[${productRowIndex}][harga]`;

            productContainer.appendChild(newRow);
            addProductEventListeners(newRow); // Terapkan event listener (termasuk TomSelect baru)
            productRowIndex++;
        });

        // --- Jasa (Additional Work) Logic ---
        const jasaContainer = document.getElementById('jasa-rows-container');
        const addJasaRowBtn = document.getElementById('add-jasa-row-btn');

        function addJasaEventListeners(row) {
            row.querySelector('.jasa-harga-input').addEventListener('input', calculateAllTotals);
            row.querySelector('.remove-jasa-row-btn').addEventListener('click', () => {
                row.remove();
                calculateAllTotals();
            });
        }

        addJasaRowBtn.addEventListener('click', () => {
            /* ... kode tambah jasa tetap sama ... */ });

        // --- Calculation Logic ---
        function calculateAllTotals() {
            let totalProduk = 0;
            document.querySelectorAll('.product-row').forEach(row => {
                const volume = parseFloat(row.querySelector('.volume-input').value) || 0;
                const harga = parseFloat(row.querySelector('.harga-input').value) || 0;
                const total = volume * harga;
                row.querySelector('.total-output').value = formatRupiah(total);
                totalProduk += total;
            });

            let totalJasa = 0;
            document.querySelectorAll('.jasa-row').forEach(row => {
                totalJasa += parseFloat(row.querySelector('.jasa-harga-input').value) || 0;
            });

            totalKeseluruhanDisplay.textContent = formatRupiah(totalProduk + totalJasa);
        }

        // --- Initial Setup ---
        addProductEventListeners(productContainer.querySelector('.product-row'));
        addJasaEventListeners(jasaContainer.querySelector('.jasa-row'));
        calculateAllTotals();
    });
</script>
@endsection