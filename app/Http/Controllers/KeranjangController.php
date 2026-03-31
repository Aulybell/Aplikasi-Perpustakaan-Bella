<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Keranjang;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KeranjangController extends Controller
{
    /**
     * Tampilkan halaman keranjang user yang sedang login
     */
    public function index()
    {
        $keranjangs = Keranjang::with(['buku.kategori'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        $totalDipilih = 0;

        return view('keranjang.index', compact('keranjangs', 'totalDipilih'));
    }

    /**
     * Tambah buku ke keranjang
     */
    public function tambah(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:bukus,id',
        ]);

        $buku = Buku::findOrFail($request->buku_id);

        if ($buku->stok <= 0) {
            return back()->with('error', 'Stok buku "' . $buku->judul . '" sedang habis.');
        }

        // Cek apakah sudah ada di keranjang
        $sudahAda = Keranjang::where('user_id', Auth::id())
            ->where('buku_id', $request->buku_id)
            ->exists();

        if ($sudahAda) {
            return back()->with('warning', 'Buku "' . $buku->judul . '" sudah ada di keranjang Anda.');
        }

        Keranjang::create([
            'user_id' => Auth::id(),
            'buku_id' => $request->buku_id,
        ]);

        return back()->with('success', 'Buku "' . $buku->judul . '" berhasil ditambahkan ke keranjang!');
    }

    /**
     * Hapus satu buku dari keranjang
     */
    public function hapus($id)
    {
        $keranjang = Keranjang::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $judul = $keranjang->buku->judul;
        $keranjang->delete();

        return back()->with('success', 'Buku "' . $judul . '" dihapus dari keranjang.');
    }

    /**
     * Hapus semua buku dari keranjang
     */
    public function hapusSemua()
    {
        Keranjang::where('user_id', Auth::id())->delete();

        return back()->with('success', 'Semua buku berhasil dihapus dari keranjang.');
    }

    /**
     * Proses peminjaman dari buku yang dipilih (checkbox)
     */
    public function pinjam(Request $request)
    {
        $request->validate([
            'buku_ids'        => 'required|array|min:1',
            'buku_ids.*'      => 'exists:bukus,id',
            'tanggal_kembali' => 'required|date|after:today',
        ], [
            'buku_ids.required' => 'Pilih minimal satu buku untuk dipinjam.',
            'buku_ids.min'      => 'Pilih minimal satu buku untuk dipinjam.',
            'tanggal_kembali.required' => 'Tanggal pengembalian wajib diisi.',
            'tanggal_kembali.after'    => 'Tanggal pengembalian harus setelah hari ini.',
        ]);

        $bukuIds = $request->buku_ids;

        // Validasi stok semua buku yang dipilih
        $bukus = Buku::whereIn('id', $bukuIds)->get();
        $stokHabis = $bukus->filter(fn($b) => $b->stok <= 0);

        if ($stokHabis->isNotEmpty()) {
            $juduls = $stokHabis->pluck('judul')->join(', ');
            return back()->with('error', 'Stok buku berikut sedang habis: ' . $juduls);
        }

        DB::beginTransaction();

        try {
            // Buat satu record peminjaman
            $peminjaman = Peminjaman::create([
                'user_id'         => Auth::id(),
                'tanggal_pinjam'  => now(),
                'tanggal_kembali' => $request->tanggal_kembali,
                'status'          => 'menunggu', // menunggu | dipinjam | dikembalikan
            ]);

            // Buat detail peminjaman & kurangi stok
            foreach ($bukus as $buku) {
                DetailPeminjaman::create([
                    'peminjaman_id' => $peminjaman->id,
                    'buku_id'       => $buku->id,
                ]);

                $buku->decrement('stok');
            }

            // Hapus buku yang sudah dipinjam dari keranjang
            Keranjang::where('user_id', Auth::id())
                ->whereIn('buku_id', $bukuIds)
                ->delete();

            DB::commit();

            return redirect()->route('peminjaman.index')
                ->with('success', 'Berhasil mengajukan peminjaman ' . count($bukuIds) . ' buku! Silakan tunggu konfirmasi admin.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memproses peminjaman. Silakan coba lagi.');
        }
    }
}