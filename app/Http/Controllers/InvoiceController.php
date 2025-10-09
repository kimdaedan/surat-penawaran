<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Menampilkan form untuk membuat invoice baru.
     */
    public function create()
    {
        return view('invoice.create');
    }
}