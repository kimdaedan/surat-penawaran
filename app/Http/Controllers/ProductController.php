<?php

namespace App\Http\Controllers;

use App\Models\Product; // <-- 1. Import Model Product
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // 2. Buat method index
    public function index()
    {
        // Ambil semua data dari tabel products
        $products = Product::latest()->get();

        // Kirim data products ke view harga.index
        return view('harga.index', ['products' => $products]);
    }

    public function create()
    {
        return view('harga.create');
    }

    // Method untuk menyimpan data baru ke database
    public function store(Request $request)
    {
        // 1. Validasi data (opsional tapi sangat direkomendasikan)
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'hasil_akhir' => 'required|string|max:255',
            'performa' => 'required|string|max:255',
            'harga' => 'required|integer',
        ]);

        // 2. Simpan data ke database menggunakan Model Product
        Product::create([
            'nama_produk' => $request->nama_produk,
            'hasil_akhir' => $request->hasil_akhir,
            'performa' => $request->performa,
            'harga' => $request->harga,
        ]);

        // 3. Alihkan kembali ke halaman daftar harga dengan pesan sukses
        return redirect()->route('harga.index')->with('success', 'Data berhasil ditambahkan!');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('harga.index')->with('success', 'Data berhasil dihapus!');
    }

    public function edit(Product $product)
    {
        return view('harga.edit', ['product' => $product]);
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'hasil_akhir' => 'required|string|max:255',
            'performa' => 'required|string|max:255',
            'harga' => 'required|integer',
        ]);

        $product->update([
            'nama_produk' => $request->nama_produk,
            'hasil_akhir' => $request->hasil_akhir,
            'performa' => $request->performa,
            'harga' => $request->harga,
        ]);

        return redirect()->route('harga.index')->with('success', 'Data berhasil diperbarui!');
    }
}
