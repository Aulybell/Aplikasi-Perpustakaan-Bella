<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $kategoris = Kategori::orderBy('created_at', 'desc')->paginate(15);
        return view('kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('kategori.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ]);

        Kategori::create($data);

        return redirect()->route('kategori.index')->with('success', 'Kategori ditambahkan.');
    }

    public function show(Kategori $kategori)
    {
        abort(404);
    }

    public function edit(Kategori $kategori)
    {
        return view('kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $data = $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ]);

        $kategori->update($data);

        return redirect()->route('kategori.index')->with('success', 'Kategori diperbarui.');
    }

    public function destroy(Kategori $kategori)
    {
        $kategori->delete();
        return redirect()->route('kategori.index')->with('success', 'Kategori dihapus.');
    }
}
