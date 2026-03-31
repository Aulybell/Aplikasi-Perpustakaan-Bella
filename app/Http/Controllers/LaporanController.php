<?php

namespace App\Http\Controllers;

use App\Models\Pinjam;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function print(Request $request)
    {
        $dateFrom = $request->query('from');
        $dateTo   = $request->query('to');

        $data = Pinjam::with(['buku', 'user'])
            ->where('status', 'dikembalikan')
            ->when($dateFrom, fn($q) => $q->whereDate('tanggal_kembali', '>=', $dateFrom))
            ->when($dateTo,   fn($q) => $q->whereDate('tanggal_kembali', '<=', $dateTo))
            ->latest()
            ->get();

        return view('laporan.print', [
            'data'     => $data,
            'dateFrom' => $dateFrom,
            'dateTo'   => $dateTo,
        ]);
    }
}