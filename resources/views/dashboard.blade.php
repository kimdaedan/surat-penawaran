@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 pb-20">

    <div class="bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 text-white pb-24 pt-12 px-4 shadow-xl">
        <div class="container mx-auto max-w-7xl">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0 text-center md:text-left">
                    <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight">
                        Dashboard <span class="text-indigo-400">PENAWARAN</span>.APP
                    </h1>
                    <p class="text-gray-400 mt-2 text-lg">
                        Selamat datang kembali! Apa yang ingin Anda kerjakan hari ini?
                    </p>
                </div>
                <div class="text-right hidden md:block">
                    <p class="text-sm text-gray-400 uppercase tracking-widest font-semibold">Hari ini</p>
                    <p class="text-2xl font-bold">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto max-w-7xl px-4 -mt-16">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

            <a href="{{ route('penawaran.create_combined') }}" class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 overflow-hidden border-t-4 border-blue-500">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-blue-50 rounded-lg text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <span class="text-xs font-bold px-2 py-1 bg-blue-100 text-blue-700 rounded-full uppercase">Utama</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-1">Buat Penawaran</h3>
                    <p class="text-gray-500 text-sm">Input data produk & jasa untuk membuat surat penawaran baru.</p>
                </div>
                <div class="bg-gray-50 px-6 py-3 border-t border-gray-100 flex justify-between items-center group-hover:bg-blue-50 transition-colors">
                    <span class="text-sm font-semibold text-blue-600">Mulai Sekarang</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </a>

            <a href="{{ route('invoice.create') }}" class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 overflow-hidden border-t-4 border-emerald-500">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-emerald-50 rounded-lg text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-xs font-bold px-2 py-1 bg-emerald-100 text-emerald-700 rounded-full uppercase">Keuangan</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-1">Buat Invoice</h3>
                    <p class="text-gray-500 text-sm">Tagih klien secara manual atau tarik data dari penawaran.</p>
                </div>
                <div class="bg-gray-50 px-6 py-3 border-t border-gray-100 flex justify-between items-center group-hover:bg-emerald-50 transition-colors">
                    <span class="text-sm font-semibold text-emerald-600">Buat Tagihan</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </a>

            <a href="{{ route('harga.index') }}" class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 overflow-hidden border-t-4 border-purple-500">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-purple-50 rounded-lg text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                            </svg>
                        </div>
                        <span class="text-xs font-bold px-2 py-1 bg-purple-100 text-purple-700 rounded-full uppercase">Master Data</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-1">Daftar Harga</h3>
                    <p class="text-gray-500 text-sm">Kelola harga satuan produk dan jasa pengecatan.</p>
                </div>
                <div class="bg-gray-50 px-6 py-3 border-t border-gray-100 flex justify-between items-center group-hover:bg-purple-50 transition-colors">
                    <span class="text-sm font-semibold text-purple-600">Kelola Data</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-600 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </a>

        </div>

        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                Pusat Arsip & Monitoring
            </h2>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                <a href="{{ route('histori.index') }}" class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md hover:border-blue-400 transition duration-200 flex flex-col items-center text-center group">
                    <div class="h-12 w-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mb-3 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <span class="font-semibold text-gray-700">Histori Penawaran</span>
                    <span class="text-xs text-gray-400 mt-1">Lihat status penawaran</span>
                </a>

                <a href="{{ route('invoice.histori') }}" class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md hover:border-emerald-400 transition duration-200 flex flex-col items-center text-center group">
                    <div class="h-12 w-12 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mb-3 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <span class="font-semibold text-gray-700">Histori Invoice</span>
                    <span class="text-xs text-gray-400 mt-1">Cek tagihan masuk/keluar</span>
                </a>

                <a href="{{ route('bast.index') }}" class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md hover:border-teal-400 transition duration-200 flex flex-col items-center text-center group">
                    <div class="h-12 w-12 bg-teal-50 text-teal-600 rounded-full flex items-center justify-center mb-3 group-hover:bg-teal-600 group-hover:text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="font-semibold text-gray-700">Histori BAST</span>
                    <span class="text-xs text-gray-400 mt-1">Berita Acara Serah Terima</span>
                </a>

                <a href="{{ route('skp.index') }}" class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md hover:border-indigo-400 transition duration-200 flex flex-col items-center text-center group">
                    <div class="h-12 w-12 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center mb-3 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <span class="font-semibold text-gray-700">Histori SPK</span>
                    <span class="text-xs text-gray-400 mt-1">Surat Perintah Kerja</span>
                </a>

            </div>
        </div>

        <div class="mt-8 bg-white rounded-lg border border-gray-200 p-6 flex items-center justify-between opacity-80">
            <div>
                <h4 class="text-sm font-bold text-gray-700 uppercase">Rekapan Sementara</h4>
                <p class="text-xs text-gray-500">Fitur rekapan otomatis akan segera tersedia.</p>
            </div>
            <button class="text-gray-400 hover:text-gray-600 cursor-not-allowed">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 012 2h2a2 2 0 012-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 012 2h2a2 2 0 012-2z" />
                </svg>
            </button>
        </div>

    </div>
</div>
@endsection