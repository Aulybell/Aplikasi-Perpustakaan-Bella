<?php

namespace App\Http\Controllers;

use App\Models\Pinjam;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Pengajuan peminjaman yang disetujui (status dipinjam)
        $disetujui = Pinjam::with('buku')
            ->where('user_id', $userId)
            ->where('status', 'dipinjam')
            ->orderBy('updated_at', 'desc')
            ->get();

        // Pengajuan peminjaman yang ditolak
        $ditolak = Pinjam::with('buku')
            ->where('user_id', $userId)
            ->where('status', 'ditolak')
            ->orderBy('updated_at', 'desc')
            ->get();

        // Pengembalian yang disetujui (status dikembalikan)
        $pengembalianDisetujui = Pinjam::with('buku')
            ->where('user_id', $userId)
            ->where('status', 'dikembalikan')
            ->orderBy('updated_at', 'desc')
            ->get();

        // Pengajuan pengembalian buku yang ditolak (belum ada sistem, jadi kosong dulu)
        $pengembalianDitolak = Pinjam::with('buku')
            ->where('user_id', $userId)
            ->where('status', 'pengembalian_ditolak')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('notifikasi.index', compact('disetujui', 'ditolak', 'pengembalianDisetujui', 'pengembalianDitolak'));
    }
}