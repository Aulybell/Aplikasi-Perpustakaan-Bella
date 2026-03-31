<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function kategori($kategori)
    {
        $bukus = Buku::where('kategori', $kategori)->get();
        return view('buku.kategori', compact('bukus', 'kategori'));
    }
}