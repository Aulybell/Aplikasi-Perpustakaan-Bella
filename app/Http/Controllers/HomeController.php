<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Pinjam;
use Illuminate\Http\Request;
use App\Models\Kategori;

class HomeController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            // Jika user sudah login, tampilkan dashboard
            $data = [
                'totalBuku' => Buku::count(),
                'totalPinjam' => Pinjam::where('status', 'dipinjam')->count(),
                'totalKembali' => Pinjam::where('status', 'dikembalikan')->count(),
            ];
            return view('dashboard', $data);
        }

        // Jika guest, tampilkan landing page
        $bukus = Buku::latest()->take(8)->get(); // Ambil 8 buku terbaru
        $kategoris = Kategori::orderBy('created_at', 'desc')->get(); // Ambil semua kategori dari database
        return view('landing', compact('bukus', 'kategoris'));
    }
}
