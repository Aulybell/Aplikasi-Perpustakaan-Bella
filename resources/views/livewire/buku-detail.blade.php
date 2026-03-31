<?php

use App\Livewire\BukuDetail;
use Livewire\Component;

new class extends Component
{
    public Buku $buku;

    public function mount($id)
    {
        $this->buku = Buku::findOrFail($id);
    }

    public function pinjam()
    {
        if ($this->buku->stok > 0) {
            $this->buku->decrement('stok');

            session()->flash('success', 'Buku berhasil dipinjam!');
        } else {
            session()->flash('error', 'Stok buku habis!');
        }
         return redirect()->route('koleksi');}
};
?>

<div>
   <div class="container mt-5">
    <div class="card shadow-lg border-0 p-4 rounded-4">
        <div class="row">

            <!-- Gambar Buku -->
            <div class="col-md-4 text-center">
                <img src="{{ asset('storage/' . $buku->cover) }}"
                     class="img-fluid rounded-3"
                     style="max-height: 500px; object-fit: cover;">
            </div>

            <!-- Detail Buku -->
            <div class="col-md-8">

                <h2 class="fw-bold">{{ $buku->judul }}</h2>

                <hr>

                <p><strong>Pengarang:</strong> {{ $buku->pengarang }}</p>
                <p><strong>Penerbit:</strong> {{ $buku->penerbit }}</p>
                <p><strong>Tahun Terbit:</strong> {{ $buku->tahun_terbit }}</p>

                <p>
                    <strong>Stok:</strong>
                    @if($buku->stok > 0)
                        <span class="badge bg-success">
                            Tersedia ({{ $buku->stok }})
                        </span>
                    @else
                        <span class="badge bg-danger">
                            Habis
                        </span>
                    @endif
                </p>

                <div class="mt-3">
                    <strong>Sinopsis:</strong>
                    <p class="text-muted mt-2">
                        {{ $buku->sinopsis }}
                    </p>
                </div>

                <div class="mt-4">
                    <button wire:click="pinjam"
                            class="btn btn-primary px-4 py-2 rounded-3"
                            @if($buku->stok == 0) disabled @endif>
                        Pinjam
                    </button>
                </div>

                @if (session()->has('success'))
                    <div class="alert alert-success mt-3">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="alert alert-danger mt-3">
                        {{ session('error') }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
</div>