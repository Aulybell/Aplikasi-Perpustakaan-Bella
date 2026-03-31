<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Pinjam;
use App\Models\Keranjang;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PinjamController extends Controller
{
    // ─────────────────────────────────────────────────
    // Pinjam SATU buku — tidak diubah
    // ─────────────────────────────────────────────────
    public function create($buku)
    {
        $buku = Buku::findOrFail($buku);
        return view('pinjam.create', compact('buku'));
    }

    // ─────────────────────────────────────────────────
    // STEP 1 — Tampilkan form peminjaman multiple
    // GET /pinjam/create-multiple?buku_ids[]=1&buku_ids[]=2
    //
    // Perubahan: tidak lagi terima tanggal_kembali dari keranjang.
    // Tanggal pinjam diisi user di form, kembali dihitung +14 hari
    // (sama seperti halaman create biasa).
    // ─────────────────────────────────────────────────
    public function createMultiple(Request $request)
    {
        $request->validate([
            'buku_ids'    => 'required|array|min:1',
            'buku_ids.*'  => 'exists:bukus,id',
        ], [
            'buku_ids.required' => 'Pilih minimal satu buku.',
        ]);

        $bukus = Buku::with('kategori')
            ->whereIn('id', $request->buku_ids)
            ->get();

        // Cek stok
        $stokHabis = $bukus->filter(fn($b) => $b->stok < 1);
        if ($stokHabis->isNotEmpty()) {
            return redirect()->route('keranjang.index')
                ->with('error', 'Stok buku berikut habis: '
                    . $stokHabis->pluck('judul')->join(', '));
        }

        // Hanya kirim daftar buku ke view,
        // tanggal diurus di form & storeMultiple (persis seperti create biasa)
        return view('pinjam.create-multiple', compact('bukus'));
    }

    // ─────────────────────────────────────────────────
    // STEP 2 — Simpan peminjaman multiple
    // POST /pinjam/multiple
    //
    // Logika SAMA dengan store() satu buku:
    // user input tanggal_pinjam → tanggal_kembali = +14 hari otomatis
    // ─────────────────────────────────────────────────
    public function storeMultiple(Request $request)
    {
        $request->validate([
            'buku_ids'       => 'required|array|min:1',
            'buku_ids.*'     => 'exists:bukus,id',
            'nama_peminjam'  => 'required|string|max:255',
            'no_telp'        => 'required|string|max:20',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
        ], [
            'buku_ids.required'       => 'Tidak ada buku yang dipilih.',
            'nama_peminjam.required'  => 'Nama peminjam wajib diisi.',
            'no_telp.required'        => 'Nomor telepon wajib diisi.',
            'tanggal_pinjam.required' => 'Tanggal pinjam wajib diisi.',
            'tanggal_pinjam.after_or_equal' => 'Tanggal pinjam tidak boleh sebelum hari ini.',
        ]);

        // Hitung tanggal kembali otomatis +14 hari — SAMA dengan store() biasa
        $tanggalPinjam  = Carbon::parse($request->tanggal_pinjam);
        $tanggalKembali = $tanggalPinjam->copy()->addDays(14);

        $bukuIds = $request->buku_ids;
        $bukus   = Buku::whereIn('id', $bukuIds)->get();

        $successCount = 0;
        $errors       = [];

        foreach ($bukus as $buku) {
            if ($buku->stok < 1) {
                $errors[] = "Stok '{$buku->judul}' tidak tersedia";
                continue;
            }

            // Cek apakah user sudah meminjam buku ini dan belum mengembalikan
            $existingPinjam = Pinjam::where('user_id', auth()->id())
                ->where('buku_id', $buku->id)
                ->whereIn('status', ['menunggu', 'dipinjam'])
                ->first();
            if ($existingPinjam) {
                $errors[] = "Anda sudah meminjam buku '{$buku->judul}' dan belum mengembalikannya";
                continue;
            }

            Pinjam::create([
                'user_id'           => auth()->id(),
                'buku_id'           => $buku->id,
                'nama_peminjam'     => $request->nama_peminjam,
                'no_telp'           => $request->no_telp,
                'tanggal_pinjam'    => $tanggalPinjam,
                'perkiraan_kembali' => $tanggalKembali,
                'status'            => 'menunggu',
            ]);

            // Hapus dari keranjang
            Keranjang::where('user_id', auth()->id())
                ->where('buku_id', $buku->id)
                ->delete();

            $successCount++;
        }

        session()->forget('selectedKeranjangItems');

        if ($successCount > 0) {
            $msg = "{$successCount} buku berhasil diajukan. Status: Menunggu persetujuan. 📚";
            if (!empty($errors)) {
                $msg .= ' Gagal: ' . implode(', ', $errors);
            }
            return redirect()->route('riwayat')->with('success', $msg);
        }

        return redirect()->route('keranjang.index')
            ->with('error', 'Tidak ada buku yang berhasil dipinjam: ' . implode(', ', $errors));
    }

    // ─────────────────────────────────────────────────
    // Store SATU buku — tidak diubah
    // ─────────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'buku_id'        => 'required|exists:bukus,id',
            'nama_peminjam'  => 'required|string|max:255',
            'no_telp'        => 'required|string|max:20',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
        ]);

        $tanggalPinjam  = Carbon::parse($validated['tanggal_pinjam']);
        $tanggalKembali = $tanggalPinjam->copy()->addDays(14);

        $buku = Buku::find($validated['buku_id']);
        if (!$buku || $buku->stok < 1) {
            return redirect()->back()->with('error', 'Stok buku tidak tersedia!');
        }

        // Cek apakah user sudah meminjam buku ini dan belum mengembalikan
        $existingPinjam = Pinjam::where('user_id', auth()->id())
            ->where('buku_id', $validated['buku_id'])
            ->whereIn('status', ['menunggu', 'dipinjam'])
            ->first();
        if ($existingPinjam) {
            return redirect()->back()->with('error', 'Anda sudah meminjam buku ini dan belum mengembalikannya.');
        }

        $pinjam                  = new Pinjam();
        $pinjam->user_id         = auth()->id();
        $pinjam->buku_id         = $validated['buku_id'];
        $pinjam->nama_peminjam   = $validated['nama_peminjam'];
        $pinjam->no_telp         = $validated['no_telp'];
        $pinjam->tanggal_pinjam  = $tanggalPinjam;
        $pinjam->tanggal_kembali = $tanggalKembali;
        $pinjam->status          = 'menunggu';
        $pinjam->save();

        return redirect()->route('riwayat')->with('success', 'Peminjaman berhasil disimpan!');
    }

    // ─────────────────────────────────────────────────
    // Print — tidak diubah
    // ─────────────────────────────────────────────────
    public function print($id)
    {
        $pinjam = Pinjam::with(['buku', 'user'])->findOrFail($id);
        return view('pinjam.print', compact('pinjam'));
    }
}