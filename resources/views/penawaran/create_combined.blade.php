@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Buat Penawaran Baru</h1>
            <p class="text-sm text-gray-500">Isi formulir di bawah untuk membuat penawaran proyek atau produk.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-blue-600 transition">
            &larr; Kembali ke Dashboard
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">

        <form id="offer-form" action="{{ route('penawaran.store_combined') }}" method="POST" class="p-6 md:p-8">
            @csrf

            <div class="mb-8 border-b border-gray-100 pb-6">
                <h2 class="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">
                    <span class="bg-blue-100 text-blue-600 p-1.5 rounded-md text-xs">1</span> Informasi Klien
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Klien / Perusahaan <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_klien" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Contoh: PT. Maju Jaya / Bpk. Andi">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Detail Proyek (Opsional)</label>
                        <input type="text" name="client_details" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Contoh: Renovasi Ruko Lantai 2">
                    </div>
                </div>
            </div>

            <div class="mb-8 border-b border-gray-100 pb-6">
                <div class="flex justify-between items-end mb-4">
                    <h2 class="text-lg font-bold text-gray-700 flex items-center gap-2">
                        <span class="bg-blue-100 text-blue-600 p-1.5 rounded-md text-xs">2</span> Detail Produk
                    </h2>
                </div>

                <div class="bg-blue-50 rounded-lg p-4 mb-6 border border-blue-100">
                    <label class="block text-sm font-bold text-blue-800 mb-1">Set Produk Masal (Opsional)</label>
                    <p class="text-xs text-blue-600 mb-2">Pilih produk di sini untuk mengisi semua baris di bawah secara otomatis.</p>
                    <select id="produk-all-select" class="w-full rounded-lg border-blue-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">-- Pilih Produk untuk Semua --</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->nama_produk }}" data-harga="{{ $product->harga }}">{{ $product->nama_produk }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="product-rows-container" class="space-y-3">
                    </div>

                <div class="mt-4">
                    <button type="button" id="add-product-row-btn" class="flex items-center gap-2 text-sm font-bold text-blue-600 hover:text-blue-800 hover:bg-blue-50 px-4 py-2 rounded-lg transition-colors border border-dashed border-blue-300 w-full justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Tambah Baris Produk
                    </button>
                </div>
            </div>

            <div class="mb-8 border-b border-gray-100 pb-6">
                <h2 class="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">
                    <span class="bg-blue-100 text-blue-600 p-1.5 rounded-md text-xs">3</span> Jasa / Pengerjaan Tambahan
                </h2>

                <div id="jasa-rows-container" class="space-y-3">
                    </div>

                <div class="mt-4">
                    <button type="button" id="add-jasa-row-btn" class="flex items-center gap-2 text-sm font-bold text-green-600 hover:text-green-800 hover:bg-green-50 px-4 py-2 rounded-lg transition-colors border border-dashed border-green-300 w-full justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Tambah Jasa Tambahan
                    </button>
                </div>
            </div>

            <div class="flex flex-col md:flex-row justify-between items-start gap-8">

                <div class="w-full md:w-1/2 space-y-3 bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-bold text-gray-700 text-sm mb-2">Pengaturan Dokumen</h3>
                    <div class="flex items-center">
                        <input id="pisah_kriteria_total" name="pisah_kriteria_total" type="checkbox" value="1" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="pisah_kriteria_total" class="ml-2 block text-sm text-gray-700">
                            Pisahkan Total (Interior/Exterior)
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input id="hilangkan_grand_total" name="hilangkan_grand_total" type="checkbox" value="1" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="hilangkan_grand_total" class="ml-2 block text-sm text-gray-700">
                            Sembunyikan Grand Total
                        </label>
                    </div>
                </div>

                <div class="w-full md:w-1/2 text-right">
                    <p class="text-sm text-gray-500 uppercase font-bold tracking-wider">Total Estimasi Biaya</p>
                    <div id="total_keseluruhan" class="text-4xl font-extrabold text-gray-800 mt-1">Rp 0</div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-lg shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5 text-lg">
                    💾 Simpan & Buat Penawaran
                </button>
            </div>

        </form>
    </div>
</div>

<template id="product-row-template">
    <div class="product-row grid grid-cols-1 md:grid-cols-12 gap-3 items-end p-4 border border-gray-200 rounded-lg bg-gray-50 relative hover:shadow-sm transition-shadow">
        <div class="md:col-span-4">
            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Nama Produk</label>
            <select class="product-select w-full rounded-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">-- Pilih --</option>
                @foreach ($products as $product)
                    <option value="{{ $product->nama_produk }}" data-harga="{{ $product->harga }}">{{ $product->nama_produk }}</option>
                @endforeach
            </select>
        </div>
        <div class="md:col-span-2">
            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Area</label>
            <input type="text" placeholder="Dinding Luar" class="w-full rounded-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div class="md:col-span-1">
            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Vol</label>
            <input type="number" step="0.01" value="1" class="volume-input w-full rounded-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500 text-center">
        </div>
        <div class="md:col-span-2">
            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Harga/m²</label>
            <input type="number" class="harga-input w-full rounded-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div class="md:col-span-2">
            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Subtotal</label>
            <input type="text" class="total-output w-full bg-gray-200 border-gray-300 rounded-md text-sm font-bold text-gray-700 cursor-not-allowed" readonly>
        </div>
        <div class="absolute top-2 right-2 md:static md:col-span-1 md:flex md:justify-end">
             <button type="button" class="remove-row-btn text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded transition-colors" title="Hapus Baris">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>
</template>

<template id="jasa-row-template">
    <div class="jasa-row grid grid-cols-1 md:grid-cols-12 gap-3 items-end p-4 border border-gray-200 rounded-lg bg-gray-50 relative hover:shadow-sm transition-shadow">

        <div class="md:col-span-4">
            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Nama Pengerjaan</label>
            <input type="text" class="w-full rounded-md border-gray-300 text-sm focus:ring-green-500 focus:border-green-500" placeholder="Contoh: Biaya Scaffolding">
        </div>

        <div class="md:col-span-2">
            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Volume</label>
            <input type="number" step="0.01" value="1" class="jasa-volume w-full rounded-md border-gray-300 text-sm focus:ring-green-500 focus:border-green-500 text-center">
        </div>

        <div class="md:col-span-2">
            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Satuan</label>
            <select class="jasa-satuan w-full rounded-md border-gray-300 text-sm focus:ring-green-500 focus:border-green-500">
                <option value="Ls">Ls (Lumpsum)</option>
                <option value="Lot">Lot</option>
                <option value="M2">M²</option>
                <option value="M1">M¹</option>
                <option value="Unit">Unit</option>
                <option value="Pkt">Pkt (Paket)</option>
                <option value="Liter">Liter</option>
                <option value="Titik">Titik</option>
            </select>
        </div>

        <div class="md:col-span-2">
            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Harga Satuan</label>
            <input type="number" class="jasa-harga w-full rounded-md border-gray-300 text-sm focus:ring-green-500 focus:border-green-500">
        </div>

        <div class="md:col-span-1">
            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Total</label>
            <input type="text" class="jasa-total w-full bg-gray-200 border-gray-300 rounded-md text-sm font-bold text-gray-700 cursor-not-allowed" readonly>
        </div>

        <div class="absolute top-2 right-2 md:static md:col-span-1 md:flex md:justify-end">
            <button type="button" class="remove-jasa-row-btn text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>
</template>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof TomSelect === 'undefined') {
            console.error('TomSelect library not loaded.');
        }

        const totalKeseluruhanDisplay = document.getElementById('total_keseluruhan');
        let productRowIndex = 0;
        let jasaRowIndex = 0;

        function formatRupiah(angka) {
            return 'Rp ' + (angka || 0).toLocaleString('id-ID');
        }

        const tomSelectSettings = {
            create: false,
            sortField: { field: "text", direction: "asc" },
            placeholder: "Cari Produk...",
            plugins: ['dropdown_input'],
        };

        // --- 1. LOGIKA PRODUK ALL ---
        const produkAllSelect = document.getElementById('produk-all-select');
        if(produkAllSelect){
            new TomSelect(produkAllSelect, tomSelectSettings);

            produkAllSelect.addEventListener('change', function() {
                const selectedValue = this.value;
                if (!selectedValue) return;

                const selectedOption = Array.from(this.options).find(opt => opt.value === selectedValue);
                const masterHarga = selectedOption ? selectedOption.getAttribute('data-harga') : 0;

                document.querySelectorAll('.product-row').forEach(row => {
                    const rowSelect = row.querySelector('.product-select');
                    const rowHargaInput = row.querySelector('.harga-input');

                    rowHargaInput.value = masterHarga;
                    if (rowSelect.tomselect) {
                        rowSelect.tomselect.setValue(selectedValue, true);
                    } else {
                        rowSelect.value = selectedValue;
                    }
                });
                calculateAllTotals();
            });
        }

        // --- 2. LOGIKA PRODUK ROW ---
        const productContainer = document.getElementById('product-rows-container');
        const addProductRowBtn = document.getElementById('add-product-row-btn');
        const productTemplate = document.getElementById('product-row-template');

        function addProductRow() {
            const clone = productTemplate.content.firstElementChild.cloneNode(true);

            clone.querySelector('.product-select').name = `produk[${productRowIndex}][nama]`;
            const areaInput = clone.querySelector('input[placeholder="Dinding Luar"]');
            if(areaInput) areaInput.name = `produk[${productRowIndex}][area]`;
            clone.querySelector('.volume-input').name = `produk[${productRowIndex}][volume]`;
            clone.querySelector('.harga-input').name = `produk[${productRowIndex}][harga]`;

            productContainer.appendChild(clone);
            setupProductRowEvents(clone);
            productRowIndex++;
            calculateAllTotals();
        }

        function setupProductRowEvents(row) {
            const productSelect = row.querySelector('.product-select');
            const hargaInput = row.querySelector('.harga-input');
            const volumeInput = row.querySelector('.volume-input');
            const removeBtn = row.querySelector('.remove-row-btn');

            if(typeof TomSelect !== 'undefined') new TomSelect(productSelect, tomSelectSettings);

            productSelect.addEventListener('change', function() {
                const selectedValue = this.value;
                const originalOption = Array.from(this.options).find(opt => opt.value === selectedValue);
                const hargaDefault = originalOption ? originalOption.getAttribute('data-harga') : 0;
                hargaInput.value = hargaDefault;
                calculateAllTotals();
            });

            hargaInput.addEventListener('input', calculateAllTotals);
            volumeInput.addEventListener('input', calculateAllTotals);
            removeBtn.addEventListener('click', function() {
                if (productSelect.tomselect) productSelect.tomselect.destroy();
                row.remove();
                calculateAllTotals();
            });
        }

        if(addProductRowBtn) {
            addProductRowBtn.addEventListener('click', addProductRow);
            addProductRow();
        }

        // --- 3. LOGIKA JASA ROW (UPDATED) ---
        const jasaContainer = document.getElementById('jasa-rows-container');
        const addJasaRowBtn = document.getElementById('add-jasa-row-btn');
        const jasaTemplate = document.getElementById('jasa-row-template');

        function addJasaRow() {
            const clone = jasaTemplate.content.firstElementChild.cloneNode(true);

            // Set Name Attributes
            clone.querySelector('input[placeholder="Contoh: Biaya Scaffolding"]').name = `jasa[${jasaRowIndex}][nama]`;
            clone.querySelector('.jasa-volume').name = `jasa[${jasaRowIndex}][volume]`;
            clone.querySelector('.jasa-satuan').name = `jasa[${jasaRowIndex}][satuan]`;
            clone.querySelector('.jasa-harga').name = `jasa[${jasaRowIndex}][harga]`;

            jasaContainer.appendChild(clone);

            // Event Listeners Jasa
            const volInput = clone.querySelector('.jasa-volume');
            const hrgInput = clone.querySelector('.jasa-harga');
            const removeBtn = clone.querySelector('.remove-jasa-row-btn');

            volInput.addEventListener('input', calculateAllTotals);
            hrgInput.addEventListener('input', calculateAllTotals);

            removeBtn.addEventListener('click', function() {
                clone.remove();
                calculateAllTotals();
            });

            jasaRowIndex++;
        }

        if(addJasaRowBtn) {
            addJasaRowBtn.addEventListener('click', addJasaRow);
        }

        // --- 4. FUNGSI HITUNG TOTAL ---
        function calculateAllTotals() {
            let total = 0;

            // Hitung Produk
            document.querySelectorAll('.product-row').forEach(row => {
                const vol = parseFloat(row.querySelector('.volume-input').value) || 0;
                const hrg = parseFloat(row.querySelector('.harga-input').value) || 0;
                const subtotal = vol * hrg;
                row.querySelector('.total-output').value = formatRupiah(subtotal);
                total += subtotal;
            });

            // Hitung Jasa (Updated Formula: Volume * Harga)
            document.querySelectorAll('.jasa-row').forEach(row => {
                const vol = parseFloat(row.querySelector('.jasa-volume').value) || 0;
                const hrg = parseFloat(row.querySelector('.jasa-harga').value) || 0;
                const subtotal = vol * hrg;

                // Update input readonly total di row jasa
                row.querySelector('.jasa-total').value = formatRupiah(subtotal);

                total += subtotal;
            });

            // Update Grand Total
            totalKeseluruhanDisplay.textContent = formatRupiah(total);
        }

    });
</script>
@endsection