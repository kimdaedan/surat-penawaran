@extends('layouts.app')

@section('content')
<div class="container mx-auto my-12 px-4">
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-2">Surat Penawaran: Produk + Jasa</h1>
        <p class="text-gray-600 mb-6">Isi semua detail yang diperlukan di bawah ini.</p>

        <form id="offer-form" action="{{ route('penawaran.store_combined') }}" method="POST">
            @csrf
            <input type="hidden" name="form_token" value="{{ session('form_token') }}">

            <fieldset class="border-t pt-6">
                <legend class="text-lg font-semibold text-gray-700 px-2">1. Informasi Klien</legend>
                <div class="grid grid-cols-1 gap-6 mt-4">
                    <div>
                        <label for="nama_klien" class="block text-sm font-medium text-gray-600">Nama Klien/Perusahaan</label>
                        <input type="text" name="nama_klien" id="nama_klien" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    </div>
                    <div>
                        <label for="client_details" class="block text-sm font-medium text-gray-600">Detail Pengerjaan (optional)</label>
                        <input type="text" name="client_details" id="client_details" placeholder="Contoh: Restoran The Recipes" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                </div>
            </fieldset>

            <fieldset class="border-t pt-6 mt-8">
                <legend class="text-lg font-semibold text-gray-700 px-2">2. Detail Produk</legend>

                <div class="mb-6 p-4 bg-gray-100 rounded-lg border">
                    <label for="produk-all-select" class="block text-sm font-medium text-gray-700 font-semibold">
                        Pilih Produk untuk Semua Baris (Produk All)
                    </label>
                    <select id="produk-all-select" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">-- Pilih Produk --</option>
                        @foreach ($products as $product)
                        <option value="{{ $product->nama_produk }}" data-harga="{{ $product->harga }}">{{ $product->nama_produk }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">
                        Memilih produk di sini akan mengubah semua "Nama Produk" dan "Harga/M²" di baris bawah.
                    </p>
                </div>
                <div id="product-rows-container" class="space-y-4 mt-4">
                </div>
                <div class="mt-4">
                    <button type="button" id="add-product-row-btn" class="bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-600"> + Tambah Produk </button>
                </div>
            </fieldset>

            <fieldset class="border-t pt-6 mt-8">
                <legend class="text-lg font-semibold text-gray-700 px-2">3. Tambahan Pengerjaan (optional)</legend>
                <div id="jasa-rows-container" class="space-y-4 mt-4">
                </div>
                <div class="mt-4">
                    <button type="button" id="add-jasa-row-btn" class="bg-green-500 text-white font-bold py-2 px-4 rounded hover:bg-green-600"> + Tambah Pengerjaan </button>
                </div>
            </fieldset>

            <!-- ====================================================== -->
            <!-- 4. OPSI TAMBAHAN (MENU BARU) -->
            <!-- ====================================================== -->
            <fieldset class="border-t pt-6 mt-8">
                <legend class="text-lg font-semibold text-gray-700 px-2">4. Opsi Tambahan</legend>
                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">

                    <!-- Opsi Pisahkan Kriteria Total -->
                    <div class="flex items-start p-3 bg-gray-50 rounded-md border border-gray-200">
                        <div class="flex items-center h-5">
                            <input id="pisah_kriteria_total" name="pisah_kriteria_total" type="checkbox" value="1" class="focus:ring-gray-800 h-4 w-4 text-gray-800 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="pisah_kriteria_total" class="font-medium text-gray-700 cursor-pointer">Pisahkan Kriteria Total</label>
                            <p class="text-gray-500 text-xs mt-1">Total harga akan dikelompokkan berdasarkan kriteria (Interior/Exterior) di hasil surat.</p>
                        </div>
                    </div>

                    <!-- Opsi Hilangkan Grand Total -->
                    <div class="flex items-start p-3 bg-gray-50 rounded-md border border-gray-200">
                        <div class="flex items-center h-5">
                            <input id="hilangkan_grand_total" name="hilangkan_grand_total" type="checkbox" value="1" class="focus:ring-gray-800 h-4 w-4 text-gray-800 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="hilangkan_grand_total" class="font-medium text-gray-700 cursor-pointer">Hilangkan Grand Total</label>
                            <p class="text-gray-500 text-xs mt-1">Menyembunyikan baris Grand Total di bagian bawah surat penawaran.</p>
                        </div>
                    </div>

                </div>
            </fieldset>
            <!-- ====================================================== -->

            <div class="mt-8 pt-6 border-t">
                <div class="flex justify-end items-center"><span class="text-lg mr-4">Total Estimasi Harga:</span><span id="total_keseluruhan" class="text-2xl font-bold">Rp 0</span></div>
            </div>
            <div class="mt-8"><button type="submit" class="w-full bg-gray-800 text-white font-bold py-3 px-6 rounded"> Buat Surat Penawaran </button></div>
        </form>

        <template id="product-row-template">
            <div class="product-row grid grid-cols-1 md:grid-cols-12 gap-4 items-end p-3 border rounded-md">
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-600">Nama Produk</label>
                    <select class="product-select mt-1 block w-full rounded-md">
                        <option value="">-- Pilih --</option>
                        @foreach ($products as $product)
                        <option value="{{ $product->nama_produk }}" data-harga="{{ $product->harga }}">{{ $product->nama_produk }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-600">Area</label>
                    <input type="text" placeholder="Dinding Luar" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-600">Volume M²</label>
                    <input type="number" step="0.01" value="1" class="volume-input mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-600">Harga/M²</label>
                    <input type="number" class="harga-input mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-600">Total</label>
                    <input type="text" class="total-output mt-1 block w-full bg-gray-100 border-gray-300 rounded-md shadow-sm cursor-not-allowed" readonly>
                </div>
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-transparent">Hapus</label>
                    <button type="button" class="remove-row-btn bg-red-500 text-white p-2 rounded w-full hover:bg-red-600">-</button>
                </div>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const totalKeseluruhanDisplay = document.getElementById('total_keseluruhan');
        let productRowIndex = 0;
        let jasaRowIndex = 0;

        function formatRupiah(angka) {
            return 'Rp ' + (angka || 0).toLocaleString('id-ID');
        }

        // --- Tom Select Settings ---
        const tomSelectSettings = {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            }
        };

        // ======================================================
        //     KODE BARU DIMULAI (LOGIKA "PRODUK ALL")
        // ======================================================
        const produkAllSelect = document.getElementById('produk-all-select');
        // Inisialisasi Tom Select pada dropdown master
        const produkAllTomSelect = new TomSelect(produkAllSelect, tomSelectSettings);

        produkAllSelect.addEventListener('change', function() {
            const selectedValue = this.value;
            if (!selectedValue) return; // Jangan lakukan apa-apa jika pilihannya kosong

            const selectedOption = Array.from(this.options).find(opt => opt.value === selectedValue);
            const masterHarga = selectedOption ? selectedOption.getAttribute('data-harga') : 0;

            // Loop ke semua baris produk yang ada
            document.querySelectorAll('.product-row').forEach(row => {
                const rowSelect = row.querySelector('.product-select');
                const rowHargaInput = row.querySelector('.harga-input');

                // Set harga di input
                rowHargaInput.value = masterHarga;

                // Set nilai di dropdown Tom Select baris tersebut
                if (rowSelect.tomselect) {
                    // Set nilai secara "silent" agar tidak memicu event 'change'
                    rowSelect.tomselect.setValue(selectedValue, 'silent');
                } else {
                    rowSelect.value = selectedValue; // Fallback
                }
            });

            // Hitung ulang total di akhir
            calculateAllTotals();
        });
        // ======================================================
        //               KODE BARU SELESAI
        // ======================================================

        // --- Product Logic ---
        const productContainer = document.getElementById('product-rows-container');
        const addProductRowBtn = document.getElementById('add-product-row-btn');
        const productTemplate = document.getElementById('product-row-template').content.firstElementChild;

        function addProductEventListeners(row) {
            const productSelect = row.querySelector('.product-select');
            const hargaInput = row.querySelector('.harga-input');
            const volumeInput = row.querySelector('.volume-input');
            const removeBtn = row.querySelector('.remove-row-btn');

            new TomSelect(productSelect, tomSelectSettings);

            productSelect.addEventListener('change', function() {
                const selectedValue = this.value;
                const selectedOption = Array.from(this.options).find(opt => opt.value === selectedValue);
                const harga = selectedOption ? selectedOption.getAttribute('data-harga') : 0;

                // Mengisi harga otomatis saat produk dipilih
                hargaInput.value = harga || '';
                calculateAllTotals();
            });

            // --- TAMBAHAN BARU DISINI ---
            // Agar saat harga diketik manual, total langsung berubah
            hargaInput.addEventListener('input', calculateAllTotals);
            // ---------------------------

            volumeInput.addEventListener('input', calculateAllTotals);

            removeBtn.addEventListener('click', () => {
                if (productSelect.tomselect) productSelect.tomselect.destroy();
                row.remove();
                calculateAllTotals();
            });
        }

        addProductRowBtn.addEventListener('click', () => {
            const newRow = productTemplate.cloneNode(true);
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

        function addJasaEventListeners(row) {
            row.querySelector('.jasa-harga-input').addEventListener('input', calculateAllTotals);
            row.querySelector('.remove-jasa-row-btn').addEventListener('click', () => {
                row.remove();
                calculateAllTotals();
            });
        }

        addJasaRowBtn.addEventListener('click', () => {
            const newRow = jasaTemplate.cloneNode(true);
            newRow.querySelector('input[type="text"]').name = `jasa[${jasaRowIndex}][nama]`;
            newRow.querySelector('.jasa-harga-input').name = `jasa[${jasaRowIndex}][harga]`;
            jasaContainer.appendChild(newRow);
            addJasaEventListeners(newRow);
            jasaRowIndex++;
        });

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
        addProductRowBtn.click();
        addJasaRowBtn.click();

        const offerForm = document.getElementById('offer-form');
        if (offerForm) {
            offerForm.addEventListener('submit', function() {
                const submitButton = offerForm.querySelector('button[type="submit"]');
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.textContent = 'Menyimpan...';
                }
            });
        }
    });
</script>
@endsection