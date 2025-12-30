@extends('layouts.app')

@section('content')
<div class="container mx-auto my-12 px-4 flex-grow">
    <div class="max-w-7xl mx-auto">

        {{-- Header & Tombol --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Histori Penawaran</h1>
            <div class="flex gap-2">
                <a href="{{ route('penawaran.create_product') }}" class="bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 transition">+ Buat Penawaran Produk</a>
                <a href="{{ route('penawaran.create_combined') }}" class="bg-gray-800 text-white font-bold py-2 px-4 rounded hover:bg-gray-700 transition">+ Buat Penawaran Proyek</a>
            </div>
        </div>

        {{-- Form Pencarian & Alert --}}
        <form action="{{ route('histori.index') }}" method="GET" class="mb-4">
            <div class="flex">
                <input type="text" name="search" placeholder="Cari berdasarkan nama klien atau No. Surat..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-800 focus:ring-gray-800" value="{{ $search ?? '' }}">
                <button type="submit" class="ml-2 bg-gray-800 text-white font-bold py-2 px-4 rounded hover:bg-gray-700 transition">Cari</button>
            </div>
        </form>
        @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p>{{ session('success') }}</p>
        </div>
        @endif

        {{-- TABEL HISTORI --}}
        <div class="bg-white shadow-md rounded-lg overflow-visible pb-32">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="text-xs text-white uppercase bg-gray-800">
                    <tr>
                        <th scope="col" class="px-6 py-3 rounded-tl-lg">Tanggal</th>
                        <th scope="col" class="px-6 py-3">No. Surat</th>
                        <th scope="col" class="px-6 py-3 text-center">Jenis</th>
                        <th scope="col" class="px-6 py-3">Nama Klien</th>
                        <th scope="col" class="px-6 py-3">Detail</th>
                        <th scope="col" class="px-6 py-3 text-right">Total Harga</th>
                        <th scope="col" class="px-6 py-3 text-center rounded-tr-lg">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($offers as $offer)
                    <tr class="bg-white hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $offer->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 font-medium">
                            SP-{{ $offer->created_at->format('Y') }}/{{ str_pad($offer->id, 4, '0', STR_PAD_LEFT) }}
                        </td>

                        {{-- BADGE JENIS --}}
                        <td class="px-6 py-4 text-center">
                            @if($offer->jenis_penawaran == 'produk')
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded border border-blue-400">Produk</span>
                            @else
                            <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded border border-gray-500">Proyek</span>
                            @endif
                        </td>

                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $offer->nama_klien }}</th>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($offer->client_details, 20) }}</td>
                        <td class="px-6 py-4 text-right whitespace-nowrap font-bold">Rp {{ number_format($offer->total_keseluruhan, 0, ',', '.') }}</td>

                        <td class="px-6 py-4 text-center">
                            {{-- Dropdown Action --}}
                            <div x-data="{ open: false }" class="relative inline-block text-left">
                                <button @click="open = !open" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                                    Actions <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <div x-show="open" @click.away="open = false" x-transition class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50" style="display: none;">
                                    <div class="py-1" role="menu">
                                        {{-- LIHAT DETAIL --}}
                                        <a href="{{ route('histori.show', ['offer' => $offer->id]) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100">Lihat / Print</a>

                                        <div class="border-t border-gray-100 my-1"></div>

                                        {{-- INVOICE --}}
                                        <a href="{{ route('invoice.create_from_offer', ['offer' => $offer->id]) }}" class="text-green-700 block px-4 py-2 text-sm hover:bg-gray-100 font-medium">Buat Invoice</a>

                                        {{-- SPK (Surat Perintah Kerja) --}}
                                        <a href="{{ route('skp.create', ['offer' => $offer->id]) }}" class="text-indigo-700 block px-4 py-2 text-sm hover:bg-gray-100 font-medium">Buat SPK</a>

                                        {{-- BAST (Berita Acara Serah Terima) --}}
                                        <a href="{{ route('bast.create', ['offer' => $offer->id]) }}" class="text-teal-700 block px-4 py-2 text-sm hover:bg-gray-100 font-medium">Buat BAST</a>

                                        {{-- TOMBOL EDIT (LOGIKA CABANG) --}}
                                        @if($offer->jenis_penawaran == 'produk')
                                        {{-- Jika Produk -> Ke Route Baru --}}
                                        <a href="{{ route('penawaran.edit_product', ['offer' => $offer->id]) }}" class="text-yellow-600 block px-4 py-2 text-sm hover:bg-gray-100 font-medium">
                                            Edit Produk
                                        </a>
                                        @else
                                        {{-- Jika Proyek -> Ke Route Lama (histori.edit) --}}
                                        <a href="{{ route('histori.edit', ['offer' => $offer->id]) }}" class="text-yellow-600 block px-4 py-2 text-sm hover:bg-gray-100 font-medium">
                                            Edit Proyek
                                        </a>
                                        @endif

                                        {{-- DELETE --}}
                                        <div class="border-t border-gray-100 my-1"></div>
                                        <form action="{{ route('histori.destroy', ['offer' => $offer->id]) }}" method="POST" onsubmit="return confirm('Hapus penawaran ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="w-full text-left text-red-700 block px-4 py-2 text-sm hover:bg-gray-100">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">Belum ada histori penawaran.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $offers->appends(['search' => $search])->links() }}</div>
    </div>
</div>
@endsection