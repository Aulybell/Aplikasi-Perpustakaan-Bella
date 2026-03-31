<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UlasanController extends Controller
{
    /**
     * Tampilkan halaman ulasan untuk sebuah buku.
     */
    public function show($bukuId)
    {
        $buku = Buku::with('kategori')->findOrFail($bukuId);

        // Ulasan milik user yang login (jika ada)
        $userReview = null;
        if (Auth::check()) {
            $userReview = Ulasan::where('user_id', Auth::id())
                ->where('buku_id', $bukuId)
                ->first();
        }

        // Semua ulasan buku ini (paginasi 6 per halaman)
        $reviews = Ulasan::with('user')
            ->where('buku_id', $bukuId)
            ->latest()
            ->paginate(6);

        // Statistik rating
        $averageRating = Ulasan::where('buku_id', $bukuId)->avg('rating') ?? 0;
        $totalReviews  = Ulasan::where('buku_id', $bukuId)->count();

        $ratingCounts = [];
        for ($i = 1; $i <= 5; $i++) {
            $ratingCounts[$i] = Ulasan::where('buku_id', $bukuId)
                ->where('rating', $i)->count();
        }

        return view('ulasan.show', compact(
            'buku', 'userReview', 'reviews',
            'averageRating', 'totalReviews', 'ratingCounts'
        ));
    }

    /**
     * Simpan ulasan baru atau update ulasan yang sudah ada.
     */
    public function store(Request $request, $bukuId)
    {
        // Pastikan buku ada
        $buku = Buku::findOrFail($bukuId);

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'ulasan' => 'nullable|string|max:1000',
        ], [
            'rating.required' => 'Pilih rating bintang terlebih dahulu.',
            'rating.min'      => 'Rating minimal 1 bintang.',
            'rating.max'      => 'Rating maksimal 5 bintang.',
        ]);

        \Log::info('Ulasan store', ['bukuId' => $bukuId, 'user' => Auth::id(), 'rating' => $request->rating]);

        Ulasan::updateOrCreate(
            ['user_id' => Auth::id(), 'buku_id' => $bukuId],
            ['rating' => $request->rating, 'ulasan' => $request->ulasan]
        );

        return redirect()
            ->route('ulasan.show', $bukuId)
            ->with('success', 'Ulasan berhasil ' . (Ulasan::where('user_id', Auth::id())->where('buku_id', $bukuId)->exists() ? 'diperbarui' : 'ditambahkan') . '!');
    }

    /**
     * Hapus ulasan.
     */
    public function destroy($id)
    {
        $ulasan = Ulasan::findOrFail($id);

        // Hanya pemilik atau admin yang bisa hapus
        if (Auth::id() !== $ulasan->user_id && Auth::user()->role !== 'admin') {
            abort(403);
        }

        \Log::info('Ulasan destroy', ['id' => $id, 'user' => Auth::id()]);

        $bukuId = $ulasan->buku_id;
        $ulasan->delete();

        return redirect()
            ->route('ulasan.show', $bukuId)
            ->with('success', 'Ulasan berhasil dihapus.');
    }
}