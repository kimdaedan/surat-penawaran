@extends('layouts.app')

@section('content')
{{-- Layout disesuaikan dengan sidebar kiri (ml-64) --}}
<div class="min-h-screen bg-[#f8fafc] ml-0 md:ml-64 p-6 lg:p-10 transition-all duration-300">
    <div class="max-w-[1440px] mx-auto">

        {{-- Top Navigation & Title --}}
        <div class="flex flex-col md:flex-row justify-between items-end mb-10 gap-6">
            <div class="space-y-2">
                <nav class="flex items-center space-x-2 text-[10px] font-bold uppercase tracking-widest text-slate-400">
                    <a href="{{ route('histori.index') }}" class="hover:text-blue-600 transition">Histori</a>
                    <span>/</span>
                    <span class="text-slate-900">Analisis Margin Per Item</span>
                </nav>
                <h1 class="text-4xl font-black text-slate-900 tracking-tight">Analisis Margin Proyek</h1>
                <p class="text-slate-500 font-medium text-sm">Estimasi untuk klien: <span class="text-blue-600 font-bold underline decoration-blue-200 decoration-2 underline-offset-4">{{ $offer->nama_klien }}</span></p>
            </div>

            <a href="{{ route('histori.index') }}" class="group flex items-center gap-3 px-5 py-3 rounded-2xl border border-slate-200 bg-white text-slate-600 text-sm font-bold hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm">
                <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Kembali ke Histori
            </a>
        </div>

        <form action="{{ route('recap.store') }}" method="POST" id="recap-form" class="relative">
            @csrf
            {{-- Hidden Data --}}
            <input type="hidden" name="offer_id" value="{{ $offer->id }}">
            <input type="hidden" name="total_penawaran_klien" value="{{ $grandTotalOffer }}">
            <input type="hidden" name="total_pengeluaran" id="hidden-total-pengeluaran" value="0">

            <div class="grid grid-cols-1 xl:grid-cols-12 gap-10">

                {{-- Bagian Kiri: Tabel Input Biaya --}}
                <div class="xl:col-span-9">
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200/60 overflow-hidden">
                        <div class="px-8 py-6 border-b border-slate-50 flex justify-between items-center bg-white">
                            <div class="flex items-center gap-3">
                                <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
                                <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Rincian Pengeluaran Barang</h3>
                            </div>
                            <button type="button" id="add-row-btn" class="inline-flex items-center gap-2 bg-slate-900 hover:bg-blue-600 text-black px-5 py-2.5 rounded-xl text-xs font-bold transition-all active:scale-95 shadow-lg shadow-slate-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2.5" stroke-linecap="round"/></svg>
                                Tambah Item
                            </button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-slate-50/50 text-slate-400 uppercase text-[9px] font-black tracking-[0.2em] border-b border-slate-50">
                                    <tr>
                                        <th class="py-5 px-8 text-left w-64">Tanggal (Ops)</th>
                                        <th class="py-5 px-4 text-left">Deskripsi Barang</th>
                                        <th class="py-5 px-4 text-center w-40">Harga Satuan</th>
                                        <th class="py-5 px-4 text-center w-20">Qty</th>
                                        <th class="py-5 px-8 text-right w-44">Subtotal</th>
                                        <th class="py-5 px-6 w-12"></th>
                                    </tr>
                                </thead>
                                <tbody id="recap-rows" class="divide-y divide-slate-50">
                                    {{-- JS Rendered Rows --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Bagian Kanan: Summary Minimalis --}}
                <div class="xl:col-span-3">
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200/60 p-8 sticky top-10">
                        <div class="space-y-6">
                            <div class="flex justify-between items-center py-2">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Penawaran</span>
                                <span class="text-xs font-black text-slate-900">Rp {{ number_format($grandTotalOffer, 0, ',', '.') }}</span>
                            </div>

                            <div class="flex justify-between items-center py-2">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Modal</span>
                                <span id="display-total-pengeluaran" class="text-xs font-black text-slate-900">Rp 0</span>
                            </div>

                            {{-- Margin Box --}}
                            <div id="margin-card" class="mt-4 p-6 rounded-[2rem] bg-slate-50 border border-slate-100">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-2">Estimasi Laba</span>
                                <div class="flex items-baseline gap-1">
                                    <span class="text-xs font-bold text-slate-300">Rp</span>
                                    <p id="display-margin" class="text-3xl font-black tracking-tighter text-slate-900">0</p>
                                </div>
                                <span id="margin-status" class="inline-block mt-3 text-[8px] font-black uppercase py-1 px-2.5 rounded-lg bg-emerald-100 text-emerald-600">UNTUNG</span>
                            </div>

                            <button type="submit" class="w-full mt-4 bg-slate-900 hover:bg-blue-600 text-black py-4 rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] transition-all active:scale-[0.98] shadow-xl shadow-slate-200">
                                Simpan Rekapan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Template Baris Dinamis --}}
<template id="row-template">
    <tr class="recap-row group transition-all duration-300">
        <td class="py-6 px-8">
            {{-- Tanggal Item (Opsional) --}}
            <input type="date" name="items[INDEX][tanggal_item]" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-3 py-2 text-[11px] font-bold text-slate-600 outline-none focus:ring-2 focus:ring-blue-100 transition-all">
        </td>
        <td class="py-6 px-4">
            <div class="space-y-1">
                <input type="text" name="items[INDEX][material]" placeholder="Nama item..." class="w-full border-none focus:ring-0 p-0 text-sm bg-transparent font-bold text-slate-800 placeholder-slate-300" required>
                <input type="text" name="items[INDEX][detail]" placeholder="Spesifikasi/Detail" class="w-full border-none focus:ring-0 p-0 text-[11px] bg-transparent font-medium text-slate-400 placeholder-slate-200">
            </div>
        </td>
        <td class="py-6 px-4">
            <div class="flex items-center bg-slate-50 rounded-2xl px-4 py-2 border border-slate-100 group-hover:bg-white group-hover:border-blue-200 transition-all focus-within:ring-2 focus-within:ring-blue-100">
                <span class="text-slate-400 text-[10px] font-bold mr-1">RP</span>
                <input type="number" name="items[INDEX][harga]" placeholder="0" class="input-harga w-full border-none focus:ring-0 p-0 text-sm text-right bg-transparent font-black text-slate-700" required>
            </div>
        </td>
        <td class="py-6 px-4 text-center">
            <input type="number" name="items[INDEX][qty]" value="1" step="0.01" class="input-qty w-full border border-slate-100 bg-slate-50 group-hover:bg-white rounded-xl py-2 text-sm text-center font-black text-slate-700 focus:ring-2 focus:ring-blue-100 transition-all" required>
        </td>
        <td class="py-6 px-8 text-right text-sm font-black text-slate-900 whitespace-nowrap">
            <span class="input-total-text">Rp 0</span>
        </td>
        <td class="py-6 px-6 text-center">
            <button type="button" class="remove-row-btn w-10 h-10 flex items-center justify-center rounded-xl text-slate-300 hover:text-red-500 hover:bg-red-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
            </button>
        </td>
    </tr>
</template>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const rowsContainer = document.getElementById('recap-rows');
    const addRowBtn = document.getElementById('add-row-btn');
    const template = document.getElementById('row-template').innerHTML;
    const grandTotalOffer = parseFloat("{{ $grandTotalOffer }}") || 0;

    let rowIndex = 0;

    function formatRupiah(angka) {
        return Math.floor(angka).toLocaleString('id-ID');
    }

    function calculateTotals() {
        let totalRecap = 0;
        document.querySelectorAll('.recap-row').forEach(row => {
            const harga = parseFloat(row.querySelector('.input-harga').value) || 0;
            const qty = parseFloat(row.querySelector('.input-qty').value) || 0;
            const subtotal = harga * qty;

            row.querySelector('.input-total-text').textContent = 'Rp ' + formatRupiah(subtotal);
            totalRecap += subtotal;
        });

        const margin = grandTotalOffer - totalRecap;

        document.getElementById('display-total-pengeluaran').textContent = 'Rp ' + formatRupiah(totalRecap);
        document.getElementById('hidden-total-pengeluaran').value = totalRecap;
        document.getElementById('display-margin').textContent = formatRupiah(margin);

        const marginCard = document.getElementById('margin-card');
        const statusEl = document.getElementById('margin-status');

        if (margin < 0) {
            statusEl.textContent = 'RUGI';
            statusEl.className = 'inline-block mt-3 text-[8px] font-black uppercase py-1 px-2.5 rounded-lg bg-red-100 text-red-600';
            marginCard.className = 'mt-4 p-6 rounded-[2rem] bg-red-50/30 border border-red-100 transition-all duration-500';
        } else {
            statusEl.textContent = 'UNTUNG';
            statusEl.className = 'inline-block mt-3 text-[8px] font-black uppercase py-1 px-2.5 rounded-lg bg-emerald-100 text-emerald-600';
            marginCard.className = 'mt-4 p-6 rounded-[2rem] bg-emerald-50/30 border border-emerald-100 transition-all duration-500';
        }
    }

    function addNewRow() {
        const html = template.replace(/INDEX/g, rowIndex);
        rowsContainer.insertAdjacentHTML('beforeend', html);
        const newRow = rowsContainer.lastElementChild;

        newRow.querySelector('.remove-row-btn').addEventListener('click', () => {
            newRow.remove();
            calculateTotals();
        });

        newRow.querySelectorAll('input').forEach(input => {
            input.addEventListener('input', calculateTotals);
        });

        rowIndex++;
    }

    for (let i = 0; i < 3; i++) addNewRow();
    addRowBtn.addEventListener('click', addNewRow);
    calculateTotals();
});
</script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
    input::-webkit-outer-spin-button, input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    input[type=number] { -moz-appearance: textfield; }
</style>
@endsection