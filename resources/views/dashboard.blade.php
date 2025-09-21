@extends('layouts.app')

@section('content')
<div class="container mx-auto my-12 px-4">
    <section class="text-center">
        <h1 class="text-4xl md:text-5xl font-bold tracking-wider text-gray-800">#SURATPENAWARAN</h1>
        <p class="text-lg text-gray-500 mt-3 max-w-2xl mx-auto">
            Kelola semua surat penawaran Anda di satu tempat. Klik "Buat Penawaran" untuk memulai.
        </p>
    </section>

    <section class="flex flex-wrap justify-center gap-4 md:gap-6 my-10 py-4 border-y border-gray-200">
        <a href="#" class="px-4 py-2 text-sm font-bold text-black border-b-2 border-black">Semua Penawaran</a>
        <a href="#" class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-black">Draft</a>
        <a href="#" class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-black">Terkirim</a>
        <a href="#" class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-black">Disetujui</a>
    </section>

    <section class="flex flex-wrap justify-center gap-4 md:gap-6">

        {{-- Setiap kartu sekarang perlu lebar sendiri karena tidak lagi menggunakan grid --}}
        {{-- Kita gunakan w-full untuk mobile, w-1/4 untuk medium, dan w-[15.5%] untuk large agar muat 6 --}}

        <div class="w-full sm:w-[48%] md:w-[31%] lg:w-[15.5%] bg-white cursor-pointer group transition duration-300 ease-in-out hover:-translate-y-1 hover:shadow-lg">
            <div>
                <img src="{{ asset('images/sp-jasa.jpg') }}" alt="Preview Surat Produk" class="w-full h-48 object-cover">
            </div>
            <div class="p-4">
                <h5 class="font-bold text-sm uppercase truncate">SP jasa</h5>
                <p class="text-xs text-gray-500 mt-1">Detail</p>
            </div>
        </div>

        <div class="w-full sm:w-[48%] md:w-[31%] lg:w-[15.5%] bg-white cursor-pointer group transition duration-300 ease-in-out hover:-translate-y-1 hover:shadow-lg">
            <div>
                <img src="{{ asset('images/sp-produk.png') }}" alt="Preview Surat Produk" class="w-full h-48 object-cover">
            </div>
            <div class="p-4">
                <h5 class="font-bold text-sm uppercase truncate">SP Produk</h5>
                <p class="text-xs text-gray-500 mt-1">18 Sep 2025 - Disetujui</p>
            </div>
        </div>

        <div class="w-full sm:w-[48%] md:w-[31%] lg:w-[15.5%] bg-white cursor-pointer group transition duration-300 ease-in-out hover:-translate-y-1 hover:shadow-lg">
            <div>
                <img src="{{ asset('images/sp-pengecatan.jpg') }}" alt="Preview Surat Produk" class="w-full h-48 object-cover">
            </div>
            <div class="p-4">
                <h5 class="font-bold text-sm uppercase truncate">SP Produk + jasa</h5>
                <p class="text-xs text-gray-500 mt-1">detail</p>
            </div>
        </div>

    </section>
</div>
@endsection