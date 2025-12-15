@extends('layouts.app')

@section('content')
<div class="container mx-auto my-12 px-4">

    <!-- Header Section -->
    <section class="text-center mb-10">
        <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">
            PEN<span class="text-gray-500">AWARAN</span>.APP
        </h1>
        <p class="text-gray-500 mt-2 text-lg">
            Sistem Manajemen Penawaran & Invoice Terintegrasi
        </p>
    </section>

    <!-- Navigasi Menu (Filter) -->
    <section class="flex flex-wrap justify-center gap-2 md:gap-4 mb-10">
        {{-- Menu 1: Semua Menu (Aktif/Home) --}}
        <a href="{{ url('/') }}" class="px-5 py-2.5 rounded-full text-sm font-semibold bg-gray-900 text-white shadow-lg transform hover:scale-105 transition duration-300">
            Semua Menu
        </a>

        {{-- Menu 2: Histori Penawaran --}}
        <a href="{{ route('histori.index') }}" class="px-5 py-2.5 rounded-full text-sm font-medium bg-white text-gray-600 border border-gray-200 shadow-sm hover:bg-gray-50 hover:text-gray-900 hover:shadow-md transition duration-300">
            Histori Penawaran
        </a>

        {{-- Menu 3: Histori Invoice --}}
        <a href="{{ route('invoice.histori') }}" class="px-5 py-2.5 rounded-full text-sm font-medium bg-white text-gray-600 border border-gray-200 shadow-sm hover:bg-gray-50 hover:text-gray-900 hover:shadow-md transition duration-300">
            Histori Invoice
        </a>

        {{-- Menu 4: Histori BAST (Placeholder) --}}
        <a href="{{ route('bast.index') }}" class="px-5 py-2.5 rounded-full text-sm font-medium bg-white text-gray-600 border border-gray-200 shadow-sm hover:bg-gray-50 hover:text-gray-900 hover:shadow-md transition duration-300">
            Histori BAST
        </a>

        {{-- Menu 5: Hasil Rekapan (Placeholder) --}}
        <a href="#" class="px-5 py-2.5 rounded-full text-sm font-medium bg-white text-gray-400 border border-gray-100 cursor-not-allowed" title="Fitur belum tersedia">
            Hasil Rekapan
        </a>
    </section>

    <!-- Grid Menu Utama -->
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">

        <!-- Card 1: Buat Penawaran -->
        <a href="{{ route('penawaran.create_combined') }}" class="group block">
            <div class="relative overflow-hidden bg-white rounded-2xl shadow-md border border-gray-100 transition-all duration-300 group-hover:shadow-xl group-hover:-translate-y-1">
                <div class="h-48 bg-gray-100 flex items-center justify-center overflow-hidden relative">
                    {{-- Overlay warna saat hover --}}
                    <img src="{{ asset('images/sp-pengecatan.jpg') }}" alt="Surat Penawaran" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-lg font-bold text-gray-900 group-hover:text-blue-600 transition-colors">BUAT PENAWARAN</h3>
                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">Produk + Jasa</span>
                    </div>
                    <p class="text-sm text-gray-500">Buat surat penawaran baru dengan rincian produk dan jasa pengecatan.</p>
                </div>
            </div>
        </a>

        <!-- Card 2: Buat Invoice -->
        <a href="{{ route('invoice.create') }}" class="group block">
            <div class="relative overflow-hidden bg-white rounded-2xl shadow-md border border-gray-100 transition-all duration-300 group-hover:shadow-xl group-hover:-translate-y-1">
                <div class="h-48 bg-gray-100 flex items-center justify-center overflow-hidden relative">
                    <img src="{{ asset('images/sp-jasa.jpg') }}" alt="Invoice" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-lg font-bold text-gray-900 group-hover:text-green-600 transition-colors">BUAT INVOICE</h3>
                        <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">Tagihan</span>
                    </div>
                    <p class="text-sm text-gray-500">Buat invoice tagihan manual atau berdasarkan penawaran yang ada.</p>
                </div>
            </div>
        </a>

        <!-- Card 3: Daftar Harga -->
        <a href="{{ route('harga.index') }}" class="group block">
            <div class="relative overflow-hidden bg-white rounded-2xl shadow-md border border-gray-100 transition-all duration-300 group-hover:shadow-xl group-hover:-translate-y-1">
                <div class="h-48 bg-gray-100 flex items-center justify-center overflow-hidden relative">
                    <!-- Menggunakan gambar JPG 'sp-jasa.jpg' -->
                    <img src="{{ asset('images/sp-produk.png') }}" alt="Daftar Harga" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-lg font-bold text-gray-900 group-hover:text-purple-600 transition-colors">DAFTAR HARGA</h3>
                        <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-2.5 py-0.5 rounded">Database</span>
                    </div>
                    <p class="text-sm text-gray-500">Kelola master data harga produk dan jasa untuk referensi penawaran.</p>
                </div>
            </div>
        </a>

    </section>
</div>
@endsection