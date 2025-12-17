@extends('layouts.app')

@section('content')
<div class="container mx-auto my-12 px-4 flex-grow">
    <div class="max-w-7xl mx-auto">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                Histori Surat Perintah Kerja (SKP)
            </h1>
            {{-- Tombol ini mengarah ke Histori Penawaran --}}
            <a href="{{ route('histori.index') }}" class="bg-gray-800 text-white font-bold py-2 px-4 rounded hover:bg-gray-700 transition">
                + Buat SKP Baru
            </a>
        </div>

        <!-- Form Pencarian -->
        <form action="{{ route('skp.index') }}" method="GET" class="mb-4">
            <div class="flex">
                <input type="text"
                       name="search"
                       placeholder="Cari berdasarkan No. Surat, Nama Klien, atau Pekerjaan..."
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-800 focus:ring-gray-800"
                       value="{{ $search ?? '' }}">
                <button type="submit" class="ml-2 bg-gray-800 text-white font-bold py-2 px-4 rounded hover:bg-gray-700 transition">
                    Cari
                </button>
            </div>
        </form>

        <!-- Pesan Sukses -->
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <!-- Tabel Histori -->
        <div class="bg-white shadow-md rounded-lg overflow-visible pb-32">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="text-xs text-white uppercase bg-gray-800">
                    <tr>
                        <th scope="col" class="px-6 py-3">Tanggal Surat</th>
                        <th scope="col" class="px-6 py-3">No. SPK</th>
                        <th scope="col" class="px-6 py-3">Pihak I (Pemberi)</th>
                        <th scope="col" class="px-6 py-3">Judul Pekerjaan</th>
                        <th scope="col" class="px-6 py-3 text-right">Nilai Pekerjaan</th>
                        <th scope="col" class="px-6 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($skps as $skp)
                    <tr class="bg-white hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $skp->tanggal_surat->format('d M Y') }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $skp->no_surat }}</td>
                        <td class="px-6 py-4">
                            <span class="font-semibold block">{{ $skp->pihak_satu_nama }}</span>
                            <span class="text-xs text-gray-500">{{ $skp->pihak_satu_perusahaan }}</span>
                        </td>
                        <td class="px-6 py-4 truncate max-w-xs">{{ $skp->judul_pekerjaan }}</td>
                        <td class="px-6 py-4 text-right">Rp {{ number_format($skp->nilai_pekerjaan, 0, ',', '.') }}</td>

                        <td class="px-6 py-4 text-center">
                            <!-- Dropdown Action -->
                            <div x-data="{ open: false }" class="relative inline-block text-left">
                                <button @click="open = !open" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                                    Options
                                    <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                </button>
                                <div x-show="open" @click.away="open = false" x-transition class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50" style="display: none;">
                                    <div class="py-1" role="menu">
                                        <a href="{{ route('skp.show', $skp->id) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem">
                                            Lihat / Cetak
                                        </a>
                                        <form action="{{ route('skp.destroy', $skp->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus SKP ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full text-left text-red-600 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            Belum ada Surat Perintah Kerja yang dibuat.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $skps->appends(['search' => $search])->links() }}
        </div>

    </div>
</div>
@endsection