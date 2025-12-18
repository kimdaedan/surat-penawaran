@extends('layouts.app')

@section('content')
<div class="container mx-auto my-12 px-4">
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6">Edit Surat Penawaran</h1>

        <form id="offer-form" action="{{ route('histori.update', $offer->id) }}" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="action" id="action_input" value="save">

            {{-- 1. INFORMASI KLIEN --}}
            <fieldset class="border-t pt-6">
                <legend class="text-lg font-semibold text-gray-700 px-2">1. Informasi Klien</legend>
                <div class="grid grid-cols-1 gap-6 mt-4">
                    <div>
                        <label for="nama_klien" class="block text-sm font-medium text-gray-600">Nama Klien/Perusahaan</label>
                        <input type="text" name="nama_klien" id="nama_klien" value="{{ old('nama_klien', $offer->nama_klien) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-800 focus:ring-gray-800" required>
                    </div>
                    <div>
                        <label for="client_details" class="block text-sm font-medium text-gray-600">Detail Pengerjaan (optional)</label>
                        <input type="text" name="client_details" id="client_details" value="{{ old('client_details', $offer->client_details) }}" placeholder="Contoh: Restoran The Recipes" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-800 focus:ring-gray-800">
                    </div>
                </div>
            </fieldset>

            {{-- 2. DETAIL PRODUK --}}
            <fieldset class="border-t pt-6 mt-8">
                <legend class="text-lg font-semibold text-gray-700 px-2">2. Detail Produk</legend>

                <div class="mb-6 p-4 bg-gray-100 rounded-lg border mt-4">
                    <label for="produk-all-select" class="block text-sm font-medium text-gray-700 font-semibold">
                        Pilih Produk untuk Semua Baris (Produk All)
                    </label>
                    <select id="produk-all-select" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">-- Pilih Produk --</option>
                        @foreach ($all_products as $product)
                            <option value="{{ $product->nama_produk }}" data-harga="{{ $product->harga }}">{{ $product->nama_produk }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">
                        Memilih produk di sini akan mengubah semua "Nama Produk" dan "Harga/M²" di baris bawah.
                    </p>
                </div>
                <div id="product-rows-container" class="space-y-4 mt-4">
                    {{-- Loop item produk yang sudah ada --}}
                    @forelse($offer->items as $index => $item)
                    <div class="product-row grid grid-cols-1 md:grid-cols-12 gap-4 items-end p-3 border rounded-md">
                        <div class="md:col-span-3">
                            <label class="block text-sm font-medium text-gray-600">Nama Produk</label>
                            <select name="produk[{{$index}}][nama]" class="product-select mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">-- Pilih --</option>
                                @foreach ($all_products as $product)
                                <option value="{{ $product->nama_produk }}" data-harga="{{ $product->harga }}" @if($product->nama_produk == $item->nama_produk) selected @endif>{{ $product->nama_produk }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-600">Area</label>
                            <input type="text" name="produk[{{$index}}][area]" value="{{ $item->area_dinding }}" placeholder="Dinding Luar" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-600">Volume M²</label>
                            <input type="number" step="0.01" name="produk[{{$index}}][volume]" value="{{ $item->volume }}" class="volume-input mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-600">Harga/M²</label>
                            <input type="number" name="produk[{{$index}}][harga]" value="{{ $item->harga_per_m2 }}" class="harga-input mt-1 block w-full bg-gray-100 border-gray-300 shadow-sm" readonly>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-600">Total</label>
                            <input type="text" class="total-output mt-1 block w-full bg-gray-100 border-gray-300 shadow-sm" readonly>
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-sm font-medium text-transparent">Hapus</label>
                            <button type="button" class="remove-row-btn bg-red-500 hover:bg-red-600 text-white p-2 rounded w-full transition">-</button>
                        </div>
                    </div>
                    @empty
                    @endforelse
                </div>
                <div class="mt-4">
                    <button type="button" id="add-product-row-btn" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">
                        + Tambah Produk
                    </button>
                </div>
            </fieldset>

            {{-- 3. TAMBAHAN PENGERJAAN --}}
            <fieldset class="border-t pt-6 mt-8">
                <legend class="text-lg font-semibold text-gray-700 px-2">3. Tambahan Pengerjaan (optional)</legend>
                <div id="jasa-rows-container" class="space-y-4 mt-4">
                    @forelse($offer->jasaItems as $index => $jasa)
                    <div class="jasa-row grid grid-cols-1 md:grid-cols-12 gap-4 items-end p-3 border rounded-md">
                        <div class="md:col-span-8">
                            <label class="block text-sm font-medium text-gray-600">Nama Pengerjaan</label>
                            <input type="text" name="jasa[{{$index}}][nama]" value="{{ $jasa->nama_jasa }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-sm font-medium text-gray-600">Harga (Rp)</label>
                            <input type="number" name="jasa[{{$index}}][harga]" value="{{ $jasa->harga_jasa }}" class="jasa-harga-input mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-sm font-medium text-transparent">Hapus</label>
                            <button type="button" class="remove-jasa-row-btn bg-red-500 hover:bg-red-600 text-white p-2 rounded w-full transition">-</button>
                        </div>
                    </div>
                    @empty
                    @endforelse
                </div>
                 <div class="mt-4">
                    <button type="button" id="add-jasa-row-btn" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition">
                        + Tambah Pengerjaan
                    </button>
                </div>
            </fieldset>

            {{-- 4. OPSI TAMBAHAN --}}
            <fieldset class="border-t pt-6 mt-8">
                <legend class="text-lg font-semibold text-gray-700 px-2">4. Opsi Tambahan</legend>
                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div class="flex items-start p-3 bg-gray-50 rounded-md border border-gray-200">
                        <div class="flex items-center h-5">
                            <input id="pisah_kriteria_total" name="pisah_kriteria_total" type="checkbox" value="1" class="focus:ring-gray-800 h-4 w-4 text-gray-800 border-gray-300 rounded" {{ $offer->pisah_kriteria_total ? 'checked' : '' }}>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="pisah_kriteria_total" class="font-medium text-gray-700 cursor-pointer">Pisahkan Kriteria Total</label>
                            <p class="text-gray-500 text-xs mt-1">Total harga akan dikelompokkan berdasarkan kriteria (Interior/Exterior) di hasil surat.</p>
                        </div>
                    </div>

                    <div class="flex items-start p-3 bg-gray-50 rounded-md border border-gray-200">
                        <div class="flex items-center h-5">
                            <input id="hilangkan_grand_total" name="hilangkan_grand_total" type="checkbox" value="1" class="focus:ring-gray-800 h-4 w-4 text-gray-800 border-gray-300 rounded" {{ $offer->hilangkan_grand_total ? 'checked' : '' }}>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="hilangkan_grand_total" class="font-medium text-gray-700 cursor-pointer">Hilangkan Grand Total</label>
                            <p class="text-gray-500 text-xs mt-1">Menyembunyikan baris Grand Total di bagian bawah surat penawaran.</p>
                        </div>
                    </div>

                </div>
            </fieldset>

            {{-- TOTAL ESTIMASI --}}
            <div class="mt-8 pt-6 border-t">
                <div class="flex justify-end items-center">
                    <span class="text-lg mr-4 text-gray-700">Total Estimasi Harga:</span>
                    <span id="total_keseluruhan" class="text-2xl font-bold text-gray-900"></span>
                </div>
            </div>

            {{-- TOMBOL AKSI (PERBAIKAN UTAMA DI SINI) --}}
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Tombol 1: Simpan Biasa --}}
                <button type="submit"
                        value="save"
                        onclick="document.getElementById('action_input').value = 'save';"
                        class="w-full bg-gray-800 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded transition duration-200 shadow-md">
                    Update (Simpan Perubahan)
                </button>

                {{-- Tombol 2: Simpan & Duplikat --}}
                <button type="submit"
                        value="save_and_copy"
                        onclick="document.getElementById('action_input').value = 'save_and_copy';"
                        class="w-full bg-yellow-600 hover:bg-yellow-500 text-white font-bold py-3 px-6 rounded transition duration-200 flex justify-center items-center gap-2 shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M7 9a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2H9a2 2 0 01-2-2V9z" />
                        <path d="M5 3a2 2 0 00-2 2v6a2 2 0 002 2V5h8a2 2 0 00-2-2H5z" />
                    </svg>
                    Update & Copy (Buat Baru)
                </button>
            </div>

        </form>

        {{-- TEMPLATE ROW PRODUK --}}
        <template id="product-row-template">
            <div class="product-row grid grid-cols-1 md:grid-cols-12 gap-4 items-end p-3 border rounded-md">
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-600">Nama Produk</label>
                    <select class="product-select mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">-- Pilih --</option>
                        @foreach ($all_products as $product)
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
                    <input type="number" class="harga-input mt-1 block w-full bg-gray-100 border-gray-300 shadow-sm" readonly>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-600">Total</label>
                    <input type="text" class="total-output mt-1 block w-full bg-gray-100 border-gray-300 shadow-sm" readonly>
                </div>
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-transparent">Hapus</label>
                    <button type="button" class="remove-row-btn bg-red-500 hover:bg-red-600 text-white p-2 rounded w-full transition">-</button>
                </div>
            </div>
        </template>

        {{-- TEMPLATE ROW JASA --}}
        <template id="jasa-row-template">
             <div class="jasa-row grid grid-cols-1 md:grid-cols-12 gap-4 items-end p-3 border rounded-md">
                <div class="md:col-span-8">
                    <label class="block text-sm font-medium text-gray-600">Nama Pengerjaan</label>
                    <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-600">Harga (Rp)</label>
                    <input type="number" class="jasa-harga-input mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-transparent">Hapus</label>
                    <button type="button" class="remove-jasa-row-btn bg-red-500 hover:bg-red-600 text-white p-2 rounded w-full transition">-</button>
                </div>
            </div>
        </template>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // --- Variabel Global & Fungsi Bantuan ---
    const totalKeseluruhanDisplay = document.getElementById('total_keseluruhan');
    let productRowIndex = {{ $offer->items->count() }};
    let jasaRowIndex = {{ $offer->jasaItems->count() }};

    function formatRupiah(angka) {
        return 'Rp ' + (angka || 0).toLocaleString('id-ID');
    }

    // --- Logika Tom Select (Dropdown Searchable) ---
    const tomSelectSettings = { create: false, sortField: { field: "text", direction: "asc" } };

    // --- LOGIKA "PRODUK ALL" ---
    const produkAllSelect = document.getElementById('produk-all-select');
    // Inisialisasi TomSelect untuk produk All
    const produkAllTomSelect = new TomSelect(produkAllSelect, tomSelectSettings);

    produkAllSelect.addEventListener('change', function() {
        const selectedValue = this.value;
        if (!selectedValue) return;

        const selectedOption = Array.from(this.options).find(opt => opt.value === selectedValue);
        const masterHarga = selectedOption ? selectedOption.getAttribute('data-harga') : 0;

        document.querySelectorAll('.product-row').forEach(row => {
            const rowSelect = row.querySelector('.product-select');
            const rowHargaInput = row.querySelector('.harga-input');

            rowHargaInput.value = masterHarga;

            // Jika row menggunakan TomSelect, update via API TomSelect
            if (rowSelect.tomselect) {
                rowSelect.tomselect.setValue(selectedValue, 'silent');
            } else {
                rowSelect.value = selectedValue;
            }
        });
        calculateAllTotals();
    });

    // --- Logika untuk Baris Produk ---
    const productContainer = document.getElementById('product-rows-container');
    const addProductRowBtn = document.getElementById('add-product-row-btn');
    const productTemplate = document.getElementById('product-row-template').content.firstElementChild;

    function addProductEventListeners(row) {
        const productSelect = row.querySelector('.product-select');
        const hargaInput = row.querySelector('.harga-input');
        const volumeInput = row.querySelector('.volume-input');

        // Inisialisasi TomSelect di baris ini
        if (!productSelect.tomselect) {
            new TomSelect(productSelect, tomSelectSettings);
        }

        productSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            hargaInput.value = selectedOption ? selectedOption.getAttribute('data-harga') : '';
            calculateAllTotals();
        });

        volumeInput.addEventListener('input', calculateAllTotals);

        row.querySelector('.remove-row-btn').addEventListener('click', () => {
            if(productSelect.tomselect) productSelect.tomselect.destroy();
            row.remove();
            calculateAllTotals();
        });
    }

    addProductRowBtn.addEventListener('click', () => {
        const newRow = productTemplate.cloneNode(true);
        // Set name attributes
        newRow.querySelector('.product-select').name = `produk[${productRowIndex}][nama]`;
        newRow.querySelector('input[placeholder="Dinding Luar"]').name = `produk[${productRowIndex}][area]`;
        newRow.querySelector('input[placeholder="Dinding Luar"]').placeholder = `Area`;
        newRow.querySelector('.volume-input').name = `produk[${productRowIndex}][volume]`;
        newRow.querySelector('.harga-input').name = `produk[${productRowIndex}][harga]`;

        productContainer.appendChild(newRow);
        addProductEventListeners(newRow);
        productRowIndex++;
    });

    // --- Logika untuk Baris Jasa ---
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

    // --- Logika Kalkulasi Total ---
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

    // --- Inisialisasi Saat Halaman Dimuat ---
    document.querySelectorAll('.product-row').forEach(row => addProductEventListeners(row));
    document.querySelectorAll('.jasa-row').forEach(row => addJasaEventListeners(row));
    calculateAllTotals();

    // --- Anti Double-Submit Logic ---
    const offerForm = document.getElementById('offer-form');
    if (offerForm) {
        offerForm.addEventListener('submit', function() {
            // Ambil semua tombol submit
            const submitButtons = offerForm.querySelectorAll('button[type="submit"]');

            submitButtons.forEach(btn => {
                // Disable tombol secara visual & fungsional (client side)
                btn.disabled = true;
                btn.classList.add('opacity-50', 'cursor-not-allowed');

                // Ubah teks tombol sesuai valuenya (untuk UX)
                if(btn.value === 'save') {
                    btn.innerHTML = 'Menyimpan...';
                } else if (btn.value === 'save_and_copy') {
                    btn.innerHTML = 'Menduplikasi...';
                }
            });

            // CATATAN: Karena kita menggunakan <input type="hidden"> untuk 'action',
            // data aksi tetap akan terkirim dengan aman meskipun tombol di-disable.
        });
    }
});
</script>
@endsection